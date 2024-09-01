<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static create(array $array)
 * @method static where(string $string, int|string|null $value)
 * @method static whereIsNot(string $string, int|string|null $value)
 */
class Order extends Model
{
    // Constants for payment statuses
    const PAYMENT_STATUS_IN_PROCESS = 'IN PROCESS';
    const PAYMENT_STATUS_COMPLETED = 'COMPLETED';
    const PAYMENT_STATUS_CANCELLED = 'CANCELLED';

    // Constants for delivery statuses
    const ORDER_STATUS_NEW = 'NEW';
    const ORDER_STATUS_READY_TO_SHIP = 'READY TO SHIP';
    const ORDER_STATUS_SHIPPED = 'SHIPPED';

    protected $fillable = [
        'product_id',
        'customer_id',
        'quantity',
        'price_per_piece',
        'payment_status',
        'order_status',
        'transaction_id',
        'paypal_token',
    ];

    /**
     * Відношення: Замовлення належить клієнту.
     *
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Розрахунок загальної вартості замовлення.
     *
     * @return int
     */
    public function getTotalPrice(): int
    {
        return $this->quantity * $this->price_per_piece;
    }

    /**
     * Оновлення статусу оплати.
     *
     * @param string $status
     * @return void
     */
    public function updatePaymentStatus(string $status): void
    {
        $this->payment_status = $status;
        $this->save();
    }

    /**
     * Оновлення статусу доставки.
     *
     * @param string $status
     * @return void
     */
    public function updateOrderStatus(string $status): void
    {
        $this->order_status = $status;
        $this->save();
    }

    /**
     * Пошук замовлення за транзакційним ID.
     *
     * @param string $transactionId
     * @return Order|null
     */
    public static function findByTransactionId(string $transactionId): ?Order
    {
        return self::where('transaction_id', $transactionId)->first();
    }


}
