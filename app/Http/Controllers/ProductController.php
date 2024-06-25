<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

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

        if ($subcategory) {
            $productsQuery->whereHas('subcategory', function ($query) use ($subcategory) {
                $query->whereFilterName($subcategory);
            });
        }

        $products = $productsQuery->paginate(9);
        $categories = Category::with('subcategories')->whereNull('parent_id')->get();
        $selectedCategory = ucwords(str_replace('-', ' ', $category));
        $selectedSubcategory = ucwords(str_replace('-', ' ', $subcategory));

        return response()->view($category ? 'products' : 'home', compact([
            'products',
            'categories',
            'selectedCategory',
            'selectedSubcategory'
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
            'img' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'name' => 'required|string|max:255',
            'count' => 'required|integer',
            'category' => 'required|integer|exists:categories,id',
            'subcategory' => 'nullable|integer|exists:categories,id',
            'price' => 'required|numeric',
        ]);

        // Обробка зображення
        $image = $request->file('img');
        $imageName = time() . '.' . $image->getClientOriginalExtension();

        $manager = new ImageManager(new Driver());
        $image = $manager->read($image->getRealPath())->scale(height: 1200);

        // Зменшити розмір зображення
        $image->toWebp(60);

        // Зберегти зображення у форматі WebP з оптимізованою якістю
        $image->save(public_path('images/products') . '/' . $imageName, 90, 'webp');

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
            $img = $product->img;

            // Видалення зображення з папки
            if ($img && file_exists(public_path('images/products/' . $img))) {
                unlink(public_path('images/products/' . $img));
            }

            // Видалення продукту з бази даних
            $product->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Product deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete product: ' . $e->getMessage(),
            ], 500);
        }
    }

}
