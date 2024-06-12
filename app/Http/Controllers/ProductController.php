<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
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

        $productsQuery = Product::query();

        if ($category) {
            $productsQuery->whereHas('category', function ($query) use ($category) {
                $query->whereFilterName($category);
            });
        }

        $products = $productsQuery->paginate(9);
        $categories = Category::with('subcategories')->whereNull('parent_id')->get();
        $activeCategory = $category;

        return response()->view($category ? 'products' : 'home', compact([
            'products',
            'categories',
            'activeCategory'
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
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'img' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required|string|max:255',
            'count' => 'required|integer',
            'category' => 'required|integer|exists:categories,id',
            'subcategory' => 'nullable|integer|exists:categories,id',
            'price' => 'required|numeric',
        ]);

        $imageName = time().'.'.$request->img->extension();
        $request->img->move(public_path('images'), $imageName);

        Product::create([
            'img' => $imageName,
            'name' => $request->name,
            'count' => $request->count,
            'category_id' => $request->category,
            'subcategory_id' => $request->subcategory == '' ? 0 : $request->subcategory,
            'price' => $request->price,
        ]);

        return response()->json(['success' => 'Product added successfully.']);
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
     * Remove the specified product from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Product deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete product: ' . $e->getMessage(),
            ], 50);
        }
    }
}
