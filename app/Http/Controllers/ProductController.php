<?php

namespace App\Http\Controllers;

use FFMpeg\FFMpeg;
use App\Models\Slider;
use App\Models\Gallery;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductColor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use FFMpeg\Format\Video\WebM;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
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

        Gallery::whereIn('type', ['main', 'additional'])->update(['type' => 'image']);

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
            'files.*' => 'required',
            'files' => 'max:10',
            'name' => 'required|string|max:100',
            'quantities.*' => 'nullable|integer|min:0',
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

            $this->handleFiles($request->file('files'), $product->id);

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

    protected function handleFiles(array $files, int $productId): void
    {
        $directoryImages = public_path('images/gallery') . '/' . $productId;
        $directoryVideos = public_path('videos/gallery') . '/' . $productId;

        // Створюємо директорії для зображень та відео, якщо їх не існує
        if (!File::isDirectory($directoryImages)) {
            File::makeDirectory($directoryImages, 0755, true);
        }

        if (!File::isDirectory($directoryVideos)) {
            File::makeDirectory($directoryVideos, 0755, true);
        }

        foreach ($files as $file) {
            $mimeType = $file->getMimeType();
            $fileName = null;
            $type = null;

            if (str_starts_with($mimeType, 'image/')) {
                $fileName = uniqid() . '_' . time() . '.webp';
                $type = 'image';

                // Створення зображення з оптимізацією
                $manager = new ImageManager(new Driver());
                $image = $manager->read($file->getRealPath())->scale(height: 720);
                $image->toWebp(60);

                // Зберігання зображення у WebP форматі з оптимізованою якістю
                $image->save($directoryImages . '/' . $fileName, 90, 'webp');
            } elseif (str_starts_with($mimeType, 'video/')) {
                $fileName = uniqid() . '_' . time() . '.webm';
                $type = 'video';

                // Зберігаємо оригінальне відео
                $path = $file->store('videos/original', 'public');
                $ffmpeg = FFMpeg::create();

                // Завантажуємо відео для конвертації
                $videoFile = $ffmpeg->open(Storage::disk('public')->path($path));

                // Вказуємо шлях для збереження нового відео у форматі webm
                $convertedPath = $directoryVideos . '/' . pathinfo($fileName, PATHINFO_FILENAME) . '.webm';

                // Конвертуємо відео у формат webm
                $format = new WebM();
                $format->setKiloBitrate(1000); // Якість відео, за потреби можна змінити
                $videoFile->save($format, $convertedPath); // Зберігаємо за вказаним шляхом

                // Створення зображення-прев'ю з відео
                $thumbnailPath = $directoryVideos . '/' . pathinfo($fileName, PATHINFO_FILENAME) . '.webp'; // Шлях для збереження зображення-прев'ю

                // Отримання кадру з відео
                $duration = $videoFile->getFormat()->get('duration'); // Отримуємо тривалість відео
                $timeСode = \FFMpeg\Coordinate\TimeCode::fromSeconds($duration / 2); // Встановлюємо час кадру в середині відео
                $frame = $videoFile->frame($timeСode); // Отримуємо кадр
                $frame->save($thumbnailPath); // Зберігаємо в webp форматі

                // Видаляємо оригінальний файл (за бажанням)
                Storage::disk('public')->delete($path);
            }


            // Додаємо запис до таблиці галереї
            Gallery::create([
                'product_id' => $productId,
                'name' => $fileName,
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
            'colors.*' => 'nullable|string|max:50', // Назва кольору
        ]);

        $quantities = $validatedData['quantities'];
        $colorsIds = $request->get('colorsId');
        foreach ($request->get('colors') as $index => $color) {
            $colors[$index] = [
                'id' => $colorsIds[$index]['id'] ?? null,
                'color' => $color,
                'quantity' => $quantities[$index],
            ];
        }
        $validatedData['quantity'] = array_sum($quantities);
        $hasColors = !empty($colors[0]['color']);

        if ($hasColors) {
            $validatedData['has_colors'] = true;
        } else {
            $validatedData['has_colors'] = false;
        }

        DB::beginTransaction();

        try {
            $product->update($validatedData);
            if ($hasColors) {
                foreach ($colors as $color) {
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

                if (!empty($colorIds)) {
                    ProductColor::where('product_id', $product->id)
                        ->whereNotIn('id', $colorIds)
                        ->delete();
                }
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

    /**
     * Remove the specified product from storage.
     *
     * @param Product $product
     * @return JsonResponse
     */
    public function destroy(Product $product): JsonResponse
    {
        try {
            File::deleteDirectory(public_path('images/gallery/' . $product->id));
            File::deleteDirectory(public_path('videos/gallery/' . $product->id));

            $product->gallery()->delete();
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
