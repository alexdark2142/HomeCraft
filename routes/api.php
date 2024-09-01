<?php

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::post('/checkout', [OrderController::class, 'checkout']);
Route::get('/paypal/return', [OrderController::class, 'handlePayPalReturn'])->name('paypal.return');
Route::get('/paypal/cancel', [OrderController::class, 'handlePayPalCancel'])->name('paypal.cancel');

