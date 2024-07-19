<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Slider;
use Illuminate\Http\Request;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::with('category')->paginate(9);

        return view('admin.sliders.list', compact('sliders'));
    }

    public function create()
    {
        $categories = Category::whereNull('parent_id')->get();

        return view('admin.sliders.create', compact('categories'));
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg',
            'title' => 'nullable|string',
            'description' => 'nullable|string|max:1000',
            'category_id' => 'required|integer',
        ]);

        $image = $request->file('image');
        $imageName = uniqid() . '_' . time() . '.webp';

        $manager = new ImageManager(new Driver());
        $image = $manager->read($image->getRealPath())->scale(height: 1080);

        // Зменшити розмір зображення
        $image->toWebp(60);

        // Зберегти зображення у форматі WebP з оптимізованою якістю
        $image->save(public_path('images/sliders') . '/' . $imageName, 90, 'webp');

        Slider::create([
            'image_name' => $imageName,
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
        ]);

        return response()->json(['message' => 'Picture added successfully.']);
    }

    public function edit(Slider $slider)
    {
        return view('sliders.edit', compact('slider'));
    }

    public function update(Request $request, Slider $slider)
    {
        $request->validate([
            'image_path' => 'nullable|image',
            'title' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('image_path')) {
            $imagePath = $request->file('image_path')->store('sliders', 'public');
            $slider->image_path = $imagePath;
        }

        $slider->title = $request->title;
        $slider->description = $request->description;
        $slider->save();

        return redirect()->route('sliders.index')->with('success', 'Slider image updated successfully.');
    }

    public function destroy(Slider $slider): \Illuminate\Http\JsonResponse
    {
        try {
            $imagePath = public_path('images/sliders/' . $slider->image_name);

            if (file_exists($imagePath)) {
                unlink($imagePath);
            }

            $slider->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Picture deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete picture: ' . $e->getMessage(),
            ], 500);
        }
    }
}
