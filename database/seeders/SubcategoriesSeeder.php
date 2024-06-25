<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubcategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $planterSubCategories = [
            'small' => 'Small',
            'medium' => 'Medium',
            'large' => 'Large',
        ];

        $petSubCategories = [
            'small-177' => 'Small 177ml',
            'medium-400' => 'Medium 400ml',
        ];

        $flower_pots_id = Category::where('filter_name', 'flower-pots')->first();
        $pet_feeders_id = Category::where('filter_name', 'cat-and-dog-food-bowl-stand')->first();

        foreach ($planterSubCategories as $filterName => $name) {
            DB::table('categories')->insert([
                'parent_id' => $flower_pots_id->id,
                'name' => $name,
                'filter_name' => $filterName
            ]);
        }

        foreach ($petSubCategories as $filterName => $name) {
            DB::table('categories')->insert([
                'parent_id' => $pet_feeders_id->id,
                'name' => $name,
                'filter_name' => $filterName
            ]);
        }
    }
}
