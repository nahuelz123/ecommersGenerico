<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = ['Callaway', 'TaylorMade', 'Titleist', 'Ping', 'Nike Golf', 'Adidas', 'Wilson'];

        foreach ($brands as $brand) {
            Brand::create(['name' => $brand]);
        }
    }
}
