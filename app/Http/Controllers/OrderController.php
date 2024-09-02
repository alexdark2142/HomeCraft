<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class OrderController extends Controller
{
    protected $paypal;

    public function __construct(PayPalClient $paypal)
    {
        $this->paypal = $paypal;
    }

    public function checkout(Request $request)
    {
        $cartData = $request->input('cart', []);
        $totalAmount = 0;

        if (empty($cartData) || !is_array($cartData) || count($cartData) == 0) {
            return response()->json([
                'success' => false,
                'message' => 'Your cart is empty. Please add items to your cart before proceeding to checkout.'
            ]);
        }

        // Using a transaction to ensure data integrity
        DB::beginTransaction();

        try {
            // Check product availability and calculate the total amount
            foreach ($cartData as $item) {
                $product = Product::findOrFail($item['id']);

                if ($product->quantity < $item['cartQuantity']) {
                    throw new \Exception("The product {$product->name} is out of stock.");
                }

                // Subtract the product quantity
                $product->quantity -= $item['cartQuantity'];
                $product->save();

                // Calculate total amount
                $totalAmount += $item['price'] * $item['cartQuantity'];
            }

            // PayPal setup
            $this->paypal->setApiCredentials(config('paypal'));
            $this->paypal->setAccessToken($this->paypal->getAccessToken());

            $referenceId = uniqid();  // Generate a unique order identifier

            // Create the PayPal order
            $orderData = [
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    [
                        'reference_id' => $referenceId,
                        'amount' => [
                            'currency_code' => 'CAD',
                            'value' => $totalAmount,
                            'breakdown' => [
                                'item_total' => [
                                    'currency_code' => 'CAD',
                                    'value' => $totalAmount,
                                ],
                            ],
                        ],
                        'items' => array_map(function ($item) {
                            return [
                                'name' => $item['name'] ?? 'Product',
                                'unit_amount' => [
                                    'currency_code' => 'CAD',
                                    'value' => $item['price'],
                                ],
                                'quantity' => $item['cartQuantity'],
                            ];
                        }, $cartData),
                    ],
                ],
                'application_context' => [
                    'payment_method_preference' => 'IMMEDIATE_PAYMENT_REQUIRED',
                    'brand_name' => 'SB&DT HomeCraft Premium Quality',
                    'locale' => 'en-CA',
                    'landing_page' => 'LOGIN',
                    'shipping_preference' => 'GET_FROM_FILE',
                    'user_action' => 'PAY_NOW',
                    'return_url' => route('paypal.return'),
                    'cancel_url' => route('paypal.cancel'),
                ],
            ];

            $response = $this->paypal->createOrder($orderData);

            if (isset($response['id'])) {
                // Now, create the order in the database with the received PayPal token
                foreach ($cartData as $item) {
                    Order::create([
                        'product_id' => $item['id'],
                        'quantity' => $item['cartQuantity'],
                        'price_per_piece' => $item['price'],
                        'payment_status' => Order::PAYMENT_STATUS_IN_PROCESS,
                        'order_status' => Order::ORDER_STATUS_NEW,
                        'transaction_id' => $referenceId,
                        'paypal_token' => $response['id'],
                    ]);
                }

                // Commit the transaction
                DB::commit();

                return response()->json([
                    'success' => true,
                    'redirect_url' => $response['links'][1]['href'], // Get the PayPal redirect URL
                ]);
            } else {
                throw new \Exception("Couldn't create the PayPal order.");
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error checkout order: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function handlePayPalReturn(Request $request)
    {
        $orderId = $request->query('token');

        if (!$orderId) {
            return redirect()->route(
                'paypal.response',
                [
                    'status' => 'error',
                    'title' => 'Payment error',
                    'message' => 'PayPal Return Error: Missing Order ID. Try again later.'
                ]
            );
        }

        $this->paypal->setApiCredentials(config('paypal'));
        $this->paypal->setAccessToken($this->paypal->getAccessToken());

        try {
            $order = $this->paypal->showOrderDetails($orderId);

            if (isset($order['id']) && $order['status'] === 'APPROVED') {
                $captureResponse = $this->paypal->capturePaymentOrder($orderId);

                if (isset($captureResponse['id']) && $captureResponse['status'] === 'COMPLETED') {
                    $customer = $this->saveCustomer(
                        $captureResponse['purchase_units'][0]['shipping'],
                        $captureResponse['payer']['email_address']
                    );

                    $this->saveOrders($orderId, $customer->id, $captureResponse);

                    return redirect()->route(
                        'paypal.response',
                        [
                            'status' => 'success',
                            'title' => 'Your payment was successful',
                            'message' => 'Thank you for your payment.'
                        ]
                    );
                } else {
                    return redirect()->route(
                        'paypal.response',
                        [
                            'status' => 'error',
                            'title' => 'Payment error',
                            'message' => 'Error completing payment.'
                        ]
                    );
                }
            } else {
                return redirect()->route(
                    'paypal.response',
                    [
                        'status' => 'error',
                        'title' => 'Payment error',
                        'message' => 'Order not confirmed.'
                    ]
                );
            }
        } catch (\Exception $e) {
            Log::error('Error pay for order: ' . $e->getMessage());

            redirect()->route(
                'paypal.response',
                [
                    'status' => 'error',
                    'title' => 'Payment error',
                    'message' => 'An error occurred: ' . $e->getMessage()
                ]
            );
        }
    }

    public function handlePayPalCancel(Request $request)
    {
        if ($request->has('token')) {
            $token = $request->query('token');

            $orders = Order::where('paypal_token', $token)
                ->where('payment_status', Order::PAYMENT_STATUS_IN_PROCESS)
                ->get();

            DB::beginTransaction();

            try {
                foreach ($orders as $order) {
                    $product = Product::find($order->product_id);

                    if ($product) {
                        $product->quantity += $order->quantity;
                        $product->save();
                    }

                    $order->payment_status = Order::PAYMENT_STATUS_CANCELLED;
                    $order->save();
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();

                Log::error('Error cancelled order: ' . $e->getMessage());

                return redirect()->route(
                    'paypal.response',
                    [
                        'status' => 'error',
                        'title' => 'Cancellation error',
                        'message' =>'An error occurred while processing the order cancellation.'
                    ]
                );
            }
        }

        return redirect()->route(
            'paypal.response',
            [
                'status' => 'cancelled',
                'title' => 'Payment cancelled',
                'message' => 'The payment was cancelled.'
            ]
        );
    }

    private function saveCustomer($customerInfo, $email)
    {
        $address = $customerInfo['address'];

        return Customer::updateOrCreate(
            ['email' => $email],
            [
                'full_name' => $customerInfo['name']['full_name'],
                'street' => $address['address_line_1'],
                'city' => $address['admin_area_2'],
                'area' => $address['admin_area_1'],
                'postal_code' => $address['postal_code'],
                'country_code' => $address['country_code']
            ]
        );
    }

    private function saveOrders($orderId, $customerId, $captureResponse): void
    {
        $orders = Order::where('paypal_token', $orderId)->get();

        foreach ($orders as $order) {
            $order->customer_id = $customerId;
            $order->payment_status = $captureResponse['status'];
            $order->save();
        }
    }

    /**
     * @return Factory|View|Application
     */
    public function new(): Factory|View|Application
    {
        $currentStatus = 'new';
        $orderStatuses = $this->getOrderStatuses();
        $orders = Order::with(['customer', 'product.gallery'])
            ->where('payment_status', Order::PAYMENT_STATUS_COMPLETED)
            ->where('order_status', Order::ORDER_STATUS_NEW)
            ->paginate(10);

        return view('admin.orders.index', compact('orders', 'currentStatus', 'orderStatuses'));
    }

    /**
     * @return Factory|View|Application
     */
    public function prepared(): Factory|View|Application
    {
        $currentStatus = 'ready to ship';
        $orderStatuses = $this->getOrderStatuses();
        $orders = Order::with(['customer', 'product.gallery'])
            ->where('payment_status', Order::PAYMENT_STATUS_COMPLETED)
            ->where('order_status', Order::ORDER_STATUS_READY_TO_SHIP)
            ->paginate(10);

        return view('admin.orders.index', compact('orders', 'currentStatus', 'orderStatuses'));
    }

    /**
     * @return Factory|View|Application
     */
    public function shipped(): Factory|View|Application
    {
        $currentStatus = 'shipped';
        $orderStatuses = $this->getOrderStatuses();
        $orders = Order::with(['customer', 'product.gallery'])
            ->where('payment_status', Order::PAYMENT_STATUS_COMPLETED)
            ->where('order_status', Order::ORDER_STATUS_SHIPPED)
            ->paginate(10);

        return view('admin.orders.index', compact('orders', 'currentStatus', 'orderStatuses'));
    }

    /**
     * @return Factory|View|Application
     */
    public function cancelled(): Factory|View|Application
    {
        $currentStatus = 'cancelled';
        $orderStatuses = $this->getOrderStatuses();
        $orders = Order::with(['customer', 'product.gallery'])
            ->where('payment_status', Order::PAYMENT_STATUS_CANCELLED)
            ->paginate(10);

        return view('admin.orders.index', compact('orders', 'currentStatus', 'orderStatuses'));
    }

    public function update(Order $order, Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            // Використовуйте fill і save для більшого контролю і можливості додати більше полів у майбутньому
            $order->fill($request->only('order_status'))->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Order status updated successfully',
            ], 200);
        } catch (\Exception $e) {
            // Логгування включає ідентифікатор замовлення для кращої відстежуваності
            Log::error("Error updating order status for Order ID {$order->id}: " . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update order status: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Order $order): \Illuminate\Http\JsonResponse
    {
        try {
            $order->delete();
        } catch (\Exception $e) {
            Log::error('Error deleting order: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete order: ' . $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Order deleted successfully',
        ], 200);
    }


    private function getOrderStatuses(): array
    {
        return [
            Order::ORDER_STATUS_NEW,
            Order::ORDER_STATUS_READY_TO_SHIP,
            Order::ORDER_STATUS_SHIPPED
        ];
    }
}
