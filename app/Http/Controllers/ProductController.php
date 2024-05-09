<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function list(string $category = null, $subcategory = null): \Illuminate\Http\Response
    {
//        if ($subcategory) {
//            $filters->setParams($subcategory);
//        }

        $products = Product::query()
//            ->whereHas('category', function ($query) use ($category) {
//                $query->whereFilterName($category);
//            })
//            ->filter($filters)
            ->paginate(9);

        $categories = Category::all();
        $subcategories = Subcategory::all();

        return response()->view('home', compact([
            'products',
            'categories',
            'subcategories',
        ]));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
