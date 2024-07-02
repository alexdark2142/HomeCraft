<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\Gallery;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class MigrateProductImages extends Command
{
    protected $signature = 'migrate:product-images';
    protected $description = 'Міграція зображень продуктів з таблиці Products до таблиці Gallery та переміщення файлів';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        DB::beginTransaction();

        try {
            // Отримати всі продукти з таблиці Products
            $products = Product::all();

            foreach ($products as $product) {
                // Старий шлях до зображення
                $oldImagePath = public_path('images/products') . '/' . $product->img;

                // Новий шлях до директорії
                $newDirectory = public_path('images/gallery') . '/' . $product->id;

                // Перевірка та створення директорії, якщо вона не існує
                if (!File::isDirectory($newDirectory)) {
                    File::makeDirectory($newDirectory, 0755, true);
                }

                // Новий шлях до зображення
                $newImagePath = $newDirectory . '/' . $product->img;

                // Перемістити файл зображення до нової директорії, якщо цільовий файл не існує
                if (File::exists($oldImagePath) && !File::exists($newImagePath)) {
                    File::move($oldImagePath, $newImagePath);
                }

                // Додати зображення з таблиці Products до таблиці Gallery
                Gallery::create([
                    'product_id' => $product->id,
                    'name' => $product->img,
                    'type' => 'main', // Ви можете змінити це значення відповідно до ваших вимог
                    'tag' => $product->id,
                ]);
            }

            DB::commit();

            $this->info("Міграція даних завершена успішно!");
        } catch (\Exception $e) {
            DB::rollback();
            $this->error("Помилка міграції даних: " . $e->getMessage());
        }
    }
}
