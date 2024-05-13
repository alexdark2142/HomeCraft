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
            'gift-box' => 'Liquor Gift Box',
            'icon' => 'Icon',
            'flower-pots' => 'Flower pots',
            'pet-feeders' => 'Cat and Dog food bowl stand',
        ];

        foreach ($namesCategories as $filterName => $name) {
            DB::table('categories')->insert([
                'name' => $name,
                'filter_name' => $filterName
            ]);
        }
    }
}
