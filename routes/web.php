<?php

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SliderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Admin\AuthController;

Route::get('/', [ProductController::class, 'list'])->name('home');
Route::get('/paypal/response', function (Request $request) {
    $title = $request->query('title');
    $message = $request->query('message');
    $status = $request->query('status');
    $categories = Category::all();

    return view(
        'paypal.response',
        compact('message', 'status', 'title', 'categories')
    );
})->name('paypal.response');

Route::get('/product/{product}', [ProductController::class, 'show'])->name('product.show');
Route::get('/products/{category?}/{subcategory?}', [ProductController::class, 'list'])->name('products');

/*==============ADMIN==============*/
Route::get('/login', function () {
    return view('admin.login');
})->name('admin-login');

Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// Інші загальнодоступні маршрути, такі як домашня сторінка, контактна форма, і т.д.

// Маршрути адміністративної панелі
Route::middleware(['admin'])->prefix('admin')->group(function () {
    Route::get('/', function () {
        return view('admin.home');
    })->name('admin');

    Route::get('orders/new', [OrderController::class, 'new'])->name('orders.new');
    Route::get('orders/prepared', [OrderController::class, 'prepared'])->name('orders.prepared');
    Route::get('orders/shipped', [OrderController::class, 'shipped'])->name('orders.shipped');
    Route::get('orders/cancelled', [OrderController::class, 'cancelled'])->name('orders.cancelled');
    Route::patch('orders/{order}', [OrderController::class, 'update'])->name('orders.update');
    Route::delete('orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
    Route::get('products', [ProductController::class, 'index'])->name('products.index');
    Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('products', [ProductController::class, 'store'])->name('products.store');
    Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::resource('sliders', SliderController::class);
    Route::resource('categories', CategoryController::class);

    // Інші адміністративні маршрути
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
});
