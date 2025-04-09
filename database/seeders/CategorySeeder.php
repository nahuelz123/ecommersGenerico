<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['Palos de Golf', 'Pelotas de Golf', 'Ropa de Golf'];

        foreach ($categories as $category) {
            Category::create(['name' => $category]);
        }
    }
}
