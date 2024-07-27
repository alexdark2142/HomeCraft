<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::whereNull('parent_id')->with('subcategories')->paginate(9);

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg',
            'name' => 'required|string',
            'subcategories.*' => 'nullable|string|max:255',
        ]);

        $image = $request->file('image');
        $imageName = uniqid() . '_' . time() . '.webp';

        $manager = new ImageManager(new Driver());
        $image = $manager->read($image->getRealPath())->scale(height: 768);
        $image->toWebp(60);
        $image->save(public_path('images/categories') . '/' . $imageName, 90, 'webp');

        $filterName = $this->formatFilterName($request->name);

        $category = Category::create([
            'name' => $request->name,
            'parent_id' => null,
            'image_name' => $imageName,
            'filter_name' => $filterName,
        ]);

        if ($request->subcategories) {
            foreach ($request->subcategories as $subcategory) {
                if ($subcategory) {
                    Category::create([
                        'name' => $subcategory,
                        'parent_id' => $category->id,
                        'image_name' => null,
                        'filter_name' => $this->formatFilterName($subcategory),
                    ]);
                }
            }
        }

        return response()->json(['message' => 'Category added successfully.']);
    }

    function formatFilterName($name): string
    {
        return preg_replace('/\s+/', '-', strtolower(trim($name)));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', [
            'category' => $category->load('subcategories')
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // Додайте інші правила валідації за необхідністю
        ]);

        // Оновлюємо категорію
        $category->update([
            'name' => $request->name,
            // Оновіть інші поля за необхідністю
        ]);

        // Видаляємо підкатегорії, якщо вони були вказані для видалення
        if ($request->has('removed_subcategories')) {
            $removedSubcategories = $request->removed_subcategories;
            Category::whereIn('id', $removedSubcategories)->delete();
        }

        // Оновлюємо або додаємо підкатегорії
        if ($request->has('subcategories')) {
            foreach ($request->subcategories as $id => $name) {
                if (is_numeric($id)) {
                    // Оновлення існуючої підкатегорії
                    $subcategory = Category::find($id);
                    if ($subcategory) {
                        $subcategory->update([
                            'name' => $name,
                            'filter_name' => $this->formatFilterName($request->name)
                        ]);
                    }
                } else {
                    // Додавання нової підкатегорії, уникаючи конфліктів з існуючими ID
                    Category::create([
                        'parent_id' => $category->id,
                        'name' => $name,
                        'filter_name' => $this->formatFilterName($request->name)
                    ]);
                }
            }
        }

        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): \Illuminate\Http\JsonResponse
    {
        try {
            $imagePath = public_path('images/categories/' . $category->image_name);
            if (file_exists($imagePath) && $category->image_name) {
                unlink($imagePath);
            }

            $subcategories = Category::where('parent_id', $category->id)->get();

            foreach ($subcategories as $subcategory) {
                $subcategory->delete();
            }

            $category->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Category and its subcategories deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete category: ' . $e->getMessage(),
            ], 500);
        }
    }


    public function getSubcategories($categoryId): \Illuminate\Http\JsonResponse
    {
        $subcategories = Category::where('parent_id', $categoryId)->get();
        return response()->json($subcategories);
    }
}
