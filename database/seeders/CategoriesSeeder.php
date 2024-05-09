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
            'icon' => 'Icons',
            'gift-box' => 'Gift boxes',
            'planters' => 'Planters',
            'pet-feeders' => 'Pet feeders',
        ];

        foreach ($namesCategories as $filterName => $name) {
            DB::table('categories')->insert([
                'name' => $name,
                'filter_name' => $filterName
            ]);
        }
    }
}
