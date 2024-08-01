<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SliderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Admin\AuthController;

Route::get('/products/{category?}/{subcategory?}', [ProductController::class, 'list'])->name('products');
Route::get('/product/{product}', [ProductController::class, 'show'])->name('product.show');
Route::get('/', [ProductController::class, 'list'])->name('home');

Route::get('/api/subcategories/{categoryId}', [\App\Http\Controllers\CategoryController::class, 'getSubcategories']);

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

