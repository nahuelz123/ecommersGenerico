<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::pluck('id', 'name');
        $brands = Brand::pluck('id');

        for ($i = 1; $i <= 50; $i++) {
            $categoryName = $i <= 15 ? 'Palos de Golf' : ($i <= 30 ? 'Pelotas de Golf' : 'Ropa de Golf');
            $categoryId = $categories[$categoryName];

            Product::create([
                'name' => $categoryName . ' Modelo ' . $i,
                'description' => 'Producto de calidad para jugadores de golf.',
                'category_id' => $categoryId,
                'brand_id' => $brands->random(),
                'color' => fake()->safeColorName(),
                'size' => fake()->randomElement(['S', 'M', 'L', 'XL', 'Único']),
                'main_image' => 'images/products/default.png', // podés cambiar esto luego por una imagen real
                'price' => fake()->randomFloat(2, 5000, 150000),
                'stock' => fake()->numberBetween(5, 50),
            ]);
        }
    }
}
