<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\SliderController;
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

Route::middleware(['admin'])->prefix('admin')->group(function () {
    Route::get('/', function () {
        return view('admin.home');
    })->name('admin');

    /*==============PRODUCT==============*/
    Route::get('/add-product', [ProductController::class, 'create'])->name('admin.add-product');
    Route::get('/edit-product/{product}', [ProductController::class, 'edit'])->name('admin.edit-product');
    Route::get('/product-list', [AdminController::class, 'listOfProducts'])->name('admin.products');
    Route::post('/create-product', [ProductController::class, 'store'])->name('admin.create-product');
    Route::put('/update-product/{product}', [ProductController::class, 'update'])->name('admin.update-product');
    Route::delete('/product/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    /*==============PHOTO==============*/
//    Route::post('/photo/upload', [UploadController::class, 'upload'])->name('upload');
//    Route::post('/photo/destroy', [UploadController::class, 'destroy'])->name('destroy');

    /*==============HOME-PICTURE==============*/
    Route::resource('sliders', SliderController::class);

    // Route::get('/{id}/product-info', [AdminController::class, 'getUser'])->name('admin.products-info');

    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
});



