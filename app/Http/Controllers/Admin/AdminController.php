<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function getProduct($id)
    {
        $product = Product::whereId($id)
            ->get();

        return view('admin.product-info', compact('product'));
    }

    public function listProducts()
    {
        $products = Product::with('category')->with('subcategory')->paginate(9);

        return view('admin.product-list', compact('products'));
    }

    public function addProduct()
    {
        $categories = Category::with('subcategories')->whereNull('parent_id')->get();

        return view('admin.add-product', compact('categories'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
