<?php

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Admin\AuthController;

Route::get('/products/{category?}/{subcategory?}', [ProductController::class, 'list'])->name('products');
Route::get('/product/{product}', [ProductController::class, 'show'])->name('products');
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
    Route::get('/admin/add-product', [ProductController::class, 'create'])->name('admin.add-product');
    Route::get('/admin/edit-product/{product}', [ProductController::class, 'edit'])->name('admin.edit-product');
    Route::post('/admin/photo/upload', [App\Http\Controllers\UploadController::class, 'upload'])->name('upload');
    Route::post('/admin/photo/destroy', [App\Http\Controllers\UploadController::class, 'destroy'])->name('destroy');

    Route::get('/admin/product-list', [AdminController::class, 'listOfProducts'])->name('admin.products');
    Route::post('/admin/create-product', [ProductController::class, 'store'])->name('admin.create-product');
    Route::put('/admin/update-product/{product}', [ProductController::class, 'update'])->name('admin.update-product');

//Route::get('/admin/{id}/product-info', [AdminController::class, 'getUser'])->name('admin.products-info');
    Route::delete('/admin/product/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
});


