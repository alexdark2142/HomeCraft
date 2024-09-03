<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Gallery;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function list(string $category = null, $subcategory = null): \Illuminate\Http\Response
    {
        $productsQuery = Product::query();
        $sliders = Slider::with('category')->get();

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

        $productsQuery->orderByRaw('CASE WHEN quantity > 0 THEN 0 ELSE 1 END, created_at DESC');
        $products = $productsQuery->paginate(9);
        $categories = Category::with('subcategories')->whereNull('parent_id')->get();
        $selectedCategory = ucwords(str_replace('-', ' ', $category));
        $selectedSubcategory = ucwords(str_replace('-', ' ', $subcategory));

        return response()->view($category ? 'list-of-products' : 'home', compact([
            'sliders',
            'products',
            'categories',
            'selectedCategory',
            'selectedSubcategory'
        ]));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('category')
            ->with('subcategory')
            ->with('gallery')
            ->paginate(9);

        return view('admin.products.list', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::whereNull('parent_id')->get();
        $categoriesWithSubcategories = $categories->mapWithKeys(function ($category) {
            return [$category->id => $category->subcategories];
        });

        return view('admin.products.create', [
            'categories' => $categories,
            'categoriesWithSubcategories' => $categoriesWithSubcategories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $validatedData = $request->validate([
            'photos.*' => 'required|image|mimes:jpeg,png,jpg',
            'name' => 'required|string|max:100',
            'quantities.*' => 'nullable|integer|min:1',
            'colors.*' => 'nullable|string|max:50',
            'length' => 'nullable|integer',
            'height' => 'nullable|integer',
            'width' => 'nullable|integer',
            'depth' => 'nullable|integer',
            'material' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
            'category_id' => 'required|integer|exists:categories,id',
            'subcategory_id' => 'nullable|integer|exists:categories,id',
            'price' => 'nullable|numeric',
        ]);

        $colors = $request->colors;
        $quantities = $request->quantities;
        $hasColors = count($colors) > 1 || (count($colors) === 1 && !is_null($colors[0]));

        DB::beginTransaction();

        try {
            $product = $this->createProduct($request, $quantities, $hasColors);

            $this->handleImages($request->file('photos'), $product->id);

            if ($hasColors) {
                $this->handleColors($colors, $quantities, $product->id);
            }

            DB::commit();

            return response()->json(['message' => 'Product added successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();

            // Логування помилки
            Log::error('Error adding product: ' . $e->getMessage(), [
                'request' => $request->all(),
                'exception' => $e
            ]);

            return response()->json(['message' => 'Failed to add product.'], 500);
        }
    }

    /**
     * @param Request $request
     * @param array $quantities
     * @param bool $hasColors
     * @return Product
     */
    protected function createProduct(Request $request, array $quantities, bool $hasColors): Product
    {
        return Product::create([
            'name' => $request->name,
            'quantity' => $quantities ? array_sum($quantities) : 0,
            'length' => $request->length,
            'height' => $request->height,
            'width' => $request->width,
            'depth' => $request->depth,
            'material' => $request->material,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id == '' ? 0 : $request->subcategory_id,
            'price' => $request->price,
            'has_colors' => $hasColors
        ]);
    }

    protected function handleImages(array $images, int $productId): void
    {
        $directory = public_path('images/gallery') . '/' . $productId;
        $firstImage = true;

        if (!File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        foreach ($images as $image) {
            $imageName = uniqid() . '_' . time() . '.webp';

            $manager = new ImageManager(new Driver());
            $image = $manager->read($image->getRealPath())->scale(height: 720);

            // Зменшити розмір зображення
            $image->toWebp(60);

            // Зберегти зображення у форматі WebP з оптимізованою якістю
            $image->save($directory . '/' . $imageName, 90, 'webp');

            $type = $firstImage ? 'main' : 'additional';
            $firstImage = false;

            Gallery::create([
                'product_id' => $productId,
                'name' => $imageName,
                'type' => $type,
                'tag' => $productId,
            ]);
        }
    }

    /**
     * @param array $colors
     * @param array $quantities
     * @param int $productId
     * @return void
     */
    protected function handleColors(array $colors, array $quantities, int $productId): void
    {
        foreach ($colors as $index => $color) {
            if ($color && $quantities[$index]) {
                ProductColor::create([
                    'product_id' => $productId,
                    'color' => $color,
                    'quantity' => (int)$quantities[$index],
                ]);
            }
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Product $product): \Illuminate\Http\Response
    {
        $product = $product->load('category', 'subcategory', 'gallery', 'colors');
        $categories = Category::with('subcategories')->whereNull('parent_id')->get();

        return response()->view('product', compact(['product', 'categories']));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::with('subcategories')->whereNull('parent_id')->get();

        $categoriesWithSubcategories = $categories->mapWithKeys(function ($category) {
            return [$category->id => $category->subcategories->map(function ($subcat) {
                return ['id' => $subcat->id, 'name' => $subcat->name];
            })];
        });

        return view('admin.products.edit', [
            'product' => $product,
            'categories' => $categories,
            'categoriesWithSubcategories' => $categoriesWithSubcategories
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product): \Illuminate\Http\JsonResponse
    {
        // Validate the incoming data
        $validatedData = $request->validate([
            'name' => 'required|string|max:100',
            'quantities.*' => 'nullable|integer|max:255',
            'length' => 'nullable|integer',
            'height' => 'nullable|integer',
            'width' => 'nullable|integer',
            'depth' => 'nullable|integer',
            'material' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
            'category_id' => 'required|integer|exists:categories,id',
            'subcategory_id' => 'nullable|integer|exists:categories,id',
            'price' => 'nullable|numeric',
            'colors' => 'nullable|array', // Добавлено для перевірки масиву кольорів
            'colors.*.id' => 'nullable|integer|exists:product_colors,id', // Ідентифікатор кольору
            'colors.*.color' => 'nullable|string|max:50', // Назва кольору
            'colors.*.quantity' => 'nullable|integer|min:0', // Кількість кольору
        ]);

        $quantities = $validatedData['quantities'];
        $colorsIds = $request->get('colorsId');

        foreach ($request->get('colors') as $index => $color) {
            $colors[$index] = [
                'id' => $colorsIds[$index]['id'] ?? null,
                'color' => $color['color'] ?? $color,
                'quantity' => $quantities[$index],
            ];
        }

        $validatedData['quantity'] = array_sum($quantities);
        $hasColors = count($colors) > 1 || (count($colors) === 1 && !is_null($colors[0]));

        if ($hasColors) {
            $validatedData['has_colors'] = true;
        } else {
            $validatedData['has_colors'] = false;
        }

        DB::beginTransaction();

        try {
            $product->update($validatedData);

            if ($hasColors) {
                foreach ($colors as $index => $color) {
                    if ($color) {
                        $updatedColor = ProductColor::updateOrCreate(
                            ['id' => $color['id']],
                            [
                                'product_id' => $product->id,
                                'color' => $color['color'],
                                'quantity' => $color['quantity'],
                            ]
                        );

                        $colorIds[] = $updatedColor->id;
                    }
                }

                ProductColor::where('product_id', $product->id)
                    ->whereNotIn('id', $colorIds)
                    ->delete();
            }

            // Якщо все добре, коммітимо транзакцію
            DB::commit();

            return response()->json([
                'message' => 'Product updated successfully.'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to update product', [
                'error' => $e->getMessage(),
                'product_id' => $product->id,
                'validated_data' => $validatedData,
                'colors' => $colors,
            ]);

            DB::rollBack();

            return response()->json([
                'message' => 'Failed to update product.',
            ], 500);
        }
    }

    function sumQuantities(array $colors): int
    {
        $totalQuantity = 0;

        foreach ($colors as $color) {
            if (is_array($color) && isset($color['quantity'])) {
                // Додаємо quantity до загальної суми
                $totalQuantity += (int) $color['quantity'];
            }
        }

        return $totalQuantity;
    }

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

    public function verifyProduct(Request $request)
    {
        return response()->json(['success' => true]);
    }

}
