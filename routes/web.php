<?php

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Admin\AuthController;

Route::get('/products/{category?}/{subcategory?}', [ProductController::class, 'list'])->name('products');
Route::get('/', [ProductController::class, 'list'])->name('home');

Route::get('/contact-us', function () {
    return view('contact-us');
});

Route::get('/api/subcategories/{categoryId}', [\App\Http\Controllers\CategoryController::class, 'getSubcategories']);

Route::get('/admin/login', function () {
    return view('admin.login');
})->name('admin-login');

Route::post('/admin/login', [AuthController::class, 'login'])->name('login.post');

Route::middleware(['admin'])->group(function () {
    Route::get('/admin', function () {
        return view('admin.home');
    })->name('admin');
    Route::get('/admin/add-product', [AdminController::class, 'addProduct'])->name('admin.add-product');

    Route::get('/admin/product-list', [AdminController::class, 'listProducts'])->name('admin.products');
    Route::post('/admin/create-product', [ProductController::class, 'store'])->name('admin.create-product');
//Route::get('/admin/{id}/product-info', [AdminController::class, 'getUser'])->name('admin.products-info');
    Route::delete('/admin/product/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
});
