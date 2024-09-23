<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductColor;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CancelOrdersAfterTimeout extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:cancel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel orders with status "in process" after 3 hours and restore product quantities.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orders = Order::where('payment_status', Order::PAYMENT_STATUS_IN_PROCESS)
            ->where('order_status', Order::ORDER_STATUS_NEW)
            ->where('created_at', '<', Carbon::now()->subHours(3))
            ->get();

        foreach ($orders as $order) {
            DB::transaction(function () use ($order) {
                $product = Product::find($order->product_id);

                if ($product) {
                    // Перевірка, чи є кольоровий варіант
                    $color = $order->product_color_id ? ProductColor::find($order->product_color_id) : null;

                    if ($color) {
                        $color->quantity += $order->quantity;
                        $color->save();
                    }

                    $product->quantity += $order->quantity;
                    $product->save();
                }

                $order->payment_status = Order::PAYMENT_STATUS_CANCELLED;
                $order->save();
            });
        }

        $this->info('Orders have been checked and outdated ones were cancelled.');
    }
}
