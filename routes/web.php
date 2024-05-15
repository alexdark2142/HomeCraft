<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/{category?}/{subcategory?}', [ProductController::class, 'list'])->name('home');

Route::get('/contact-us', function () {
    return view('contact-us');
});
