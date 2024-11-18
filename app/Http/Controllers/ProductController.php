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
            'files' => 'max:15',
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

        // Створюємо директорії, якщо вони не існують
        File::ensureDirectoryExists($directoryImages, 0755);
        File::ensureDirectoryExists($directoryVideos, 0755);

        // Збираємо оброблені файли
        $processedFiles = [];

        foreach ($files as $file) {
            $fileName = $file->getClientOriginalName();
            $mimeType = $file->getMimeType();
            $type = null;

            // Перевіряємо, чи файл уже є в базі
            $existingFile = Gallery::where('name', $fileName)->where('product_id', $productId)->first();

            if (!$existingFile) {
                // Це новий файл
                if (str_starts_with($mimeType, 'image/')) {
                    $type = 'image';
                    $savedFileName = uniqid() . '_' . time() . '.webp';

                    // Оптимізація зображення
                    $manager = new ImageManager(new Driver());
                    $image = $manager->read($file->getRealPath())->scale(height: 720);
                    $image->toWebp(60);
                    $image->save($directoryImages . '/' . $savedFileName, 90, 'webp');
                } elseif (str_starts_with($mimeType, 'video/')) {
                    $type = 'video';
                    $savedFileName = uniqid() . '_' . time() . '.webm';

                    // Конвертація відео у webm
                    $path = $file->store('videos/original', 'public');
                    $ffmpeg = FFMpeg::create();
                    $videoFile = $ffmpeg->open(Storage::disk('public')->path($path));
                    $convertedPath = $directoryVideos . '/' . pathinfo($savedFileName, PATHINFO_FILENAME) . '.webm';

                    $format = new WebM();
                    $format->setKiloBitrate(1000);
                    $videoFile->save($format, $convertedPath);

                    // Прев'ю для відео
                    $thumbnailPath = $directoryVideos . '/' . pathinfo($savedFileName, PATHINFO_FILENAME) . '.webp';
                    $frame = $videoFile->frame(\FFMpeg\Coordinate\TimeCode::fromSeconds($videoFile->getFormat()->get('duration') / 2));
                    $frame->save($thumbnailPath);

                    // Видаляємо оригінал
                    Storage::disk('public')->delete($path);
                }

                // Додаємо новий запис до бази
                Gallery::create([
                    'product_id' => $productId,
                    'name' => $savedFileName,
                    'type' => $type,
                    'tag' => $productId,
                ]);

                $processedFiles[] = $savedFileName;
            } else {
                // Файл уже існує, додаємо до списку оброблених
                $processedFiles[] = $existingFile->name;
            }
        }

        // Видалення непотрібних файлів
        $filesToDelete = Gallery::where('product_id', $productId)
            ->whereNotIn('name', $processedFiles)
            ->get();

        foreach ($filesToDelete as $file) {
            if ($file->type === 'image') {
                File::delete($directoryImages . '/' . $file->name);
            } elseif ($file->type === 'video') {
                File::delete($directoryVideos . '/' . $file->name);
                File::delete($directoryVideos . '/' . pathinfo($file->name, PATHINFO_FILENAME) . '.webp'); // Прев'ю
            }
            $file->delete();
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

        $gallery = $product->gallery()->get();

        return view('admin.products.edit', [
            'product' => $product,
            'categories' => $categories,
            'gallery' => $gallery,
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
            'files.*' => 'required',
            'files' => 'max:15',
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

            $this->handleFiles($request->file('files'), $product->id);

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
