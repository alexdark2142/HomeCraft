<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Gallery;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProductController extends Controller
{
    /**
     * Remove the specified product from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $product = Product::findOrFail($id);

            // Отримати всі записи галереї, пов'язані з продуктом
            $galleries = Gallery::where('product_id', $id)->get();

            // Видалення зображень з папки
            foreach ($galleries as $gallery) {
                $imagePath = public_path('images/gallery/' . $gallery->tag . '/' . $gallery->name);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            // Видалення директорії продукту, якщо вона порожня
            $productDirectory = public_path('images/gallery/' . $product->id);
            if (is_dir($productDirectory) && count(scandir($productDirectory)) == 2) { // Перевірка, чи директорія порожня
                rmdir($productDirectory);
            }

            // Видалення записів з таблиці Gallery
            Gallery::where('product_id', $id)->delete();

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


    /**
     * Display a listing of the resource.
     */
    public function list(string $category = null, $subcategory = null): \Illuminate\Http\Response
    {
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

        return response()->view($category ? 'list-of-products' : 'home', compact([
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
        $categories = Category::with('subcategories')->whereNull('parent_id')->get();

        return view('admin.add-product', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'photos.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'name' => 'required|string|max:40',
            'size' => 'string|max:40',
            'material' => 'string|max:100',
            'count' => 'required|integer',
            'category' => 'required|integer|exists:categories,id',
            'subcategory' => 'nullable|integer|exists:categories,id',
            'price' => 'required|numeric',
        ]);

        $product = Product::create([
            'name' => $request->name,
            'count' => $request->count,
            'size' => $request->size,
            'material' => $request->material,
            'category_id' => $request->category,
            'subcategory_id' => $request->subcategory == '' ? 0 : $request->subcategory,
            'price' => $request->price,
        ]);

        $product_id = $product->id;        // Обробка зображення
        $images = $request->file('photos');
        $directory = public_path('images/gallery') . '/' . $product_id;
        $firstImage = true;

        if (!File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        foreach ($images as $image) {
            $imageName = time() . '.' . $image->getClientOriginalExtension();

            $manager = new ImageManager(new Driver());
            $image = $manager->read($image->getRealPath())->scale(height: 1200);

            // Зменшити розмір зображення
            $image->toWebp(60);

            // Зберегти зображення у форматі WebP з оптимізованою якістю
            $image->save($directory . '/' . $imageName, 90, 'webp');

            $type = $firstImage ? 'main' : 'additional';
            $firstImage = false;

            Gallery::create([
                'product_id' => $product_id, // Збереження product_id
                'name' => $imageName,
                'type' => $type,
                'tag' => $product_id,
            ]);
        }

        ;

        return response()->json(['message' => 'Product added successfully.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): \Illuminate\Http\Response
    {
        $product = $product->load('category', 'subcategory', 'gallery');
        $categories = Category::with('subcategories')->whereNull('parent_id')->get();

        return response()->view('product', compact(['product', 'categories']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        $subcategories = Category::where('parent_id', $product->category_id)->get();

        return view('admin.products.edit', compact('product', 'categories', 'subcategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        // Validate the incoming data
        $validatedData = $request->validate([
            'name' => 'required|string|max:40',
            'count' => 'required|integer',
            'size' => 'string|max:40',
            'material' => 'string|max:100',
            'category_id' => 'required|integer|exists:categories,id',
            'subcategory_id' => 'nullable|integer|exists:categories,id',
            'price' => 'required|numeric',
        ]);

        // Check if a new image was uploaded
//        if ($request->hasFile('img')) {
//            // Store the new image
//            $imagePath = $request->file('img')->store('products', 'public');
//            // Delete the old image if it exists
//            if ($product->img) {
//                Storage::disk('public')->delete($product->img);
//            }
//            $validatedData['img'] = $imagePath;
//        }

        // Update the product with the validated data
        $product->update($validatedData);

        return response()->json([
            'message' => 'Product updated successfully.'
        ]);
    }

}
