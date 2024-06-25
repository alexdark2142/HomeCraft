<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $namesCategories = [
            'liquor-gift-box' => 'Liquor Gift Box',
            'icon' => 'Icon',
            'flower-pots' => 'Flower pots',
            'cat-and-dog-food-bowl-stand' => 'Cat and Dog food bowl stand',
            'entertainment' => 'Entertainment',
            'other-products' => 'Other products',
        ];

        foreach ($namesCategories as $filterName => $name) {
            DB::table('categories')->insert([
                'name' => $name,
                'filter_name' => $filterName
            ]);
        }
    }
}
