<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $imageNames = ['icon', 'vine', 'box', 'bench'];
        $imageName = $this->faker->randomElement($imageNames);

        $extension = 'png'; // Assuming all images are PNG

        return [
            'name' => $this->faker->sentence(),
            'img' => $imageName . '.' . $extension,
            'category_id' => $this->faker->randomFloat(1, 1, 5),
            'subcategory_id' => $this->faker->randomFloat(1, 1, 3),
            'price' => $this->faker->randomFloat(2, 10, 100),
        ];
    }
}
