<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\ImageManagerStatic as Image;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        $images = $request->file('images');
        $sessionTag = Session::get('medialibrary');
        $numberImage = 0;
        $mainPhoto = $request->input('main_photo');

        try {
            $files = [];

            foreach ($images as $image) {
                $fileName = Carbon::now()->timestamp . $numberImage++;
                $service = new ImageService($fileName, $image);

                // Обробка та збереження зображення в форматі WebP
                $imageName = $fileName . '.webp';
                $manager = new ImageManager();

                $manager->make($image)->resize(1200, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->encode('webp', 60)
                    ->save(public_path('images/gallery/') . $sessionTag . '/' . $imageName);

                // Визначення типу зображення
                $imageType = ($fileName == pathinfo($mainPhoto, PATHINFO_FILENAME)) ? 'main' : 'additional';

                // Збереження даних у базу, включаючи product_id, якщо наявний у запиті
                Gallery::create([
                    'product_id' => $request->input('product_id'), // Збереження product_id
                    'name' => $fileName,
                    'type' => $imageType,
                    'tag' => $sessionTag,
                ]);

                $files[] = [
                    'name' => $imageName, // Збереження імені файлу WebP у масив результатів
                ];
            }

            return \response()->json($files);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    /**
     * @throws \LogicException
     */
    public function destroy(Request $request): void
    {
        $file = Gallery::whereName(pathinfo($request->get('file_name'), PATHINFO_FILENAME))->first();
        $this->removeMediaFiles($file);

        // Видалення запису з бази даних
        Gallery::whereName(pathinfo($request->get('file_name'), PATHINFO_FILENAME))->delete();

        // Перевірка, чи видалене зображення було головним
        if ($file->type == 'main') {
            // Знайти інше зображення і встановити його як головне
            $additionalFile = Gallery::where('tag', $file->tag)->where('type', 'additional')->first();
            if ($additionalFile) {
                $additionalFile->update(['type' => 'main']);
            }
        }
    }

    public function removeMediaFiles(object $file): string
    {
        try {
            /** @var Gallery $file */
            $path = 'images/gallery/' . $file->tag;
            if (file_exists(public_path("{$path}/{$file->name}.webp"))) {
                unlink(public_path("{$path}/{$file->name}.webp"));
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return 'success';
    }

}
