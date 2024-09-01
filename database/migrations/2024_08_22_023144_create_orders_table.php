<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->integer('customer_id')->nullable();
            $table->integer('quantity');
            $table->integer('price_per_piece');
            $table->string('payment_status')->default('IN PROCESS');
            $table->string('order_status')->nullable();
            $table->string('transaction_id');
            $table->string('paypal_token')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
