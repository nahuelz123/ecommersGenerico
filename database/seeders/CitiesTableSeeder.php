<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use App\Models\City;
use App\Models\Province;


class CitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $response = Http::get('https://apis.datos.gob.ar/georef/api/localidades?max=5000&campos=nombre,provincia');

        $cities = $response->json()['localidades'];
    
        foreach ($cities as $city) {
            $provinceName = $city['provincia']['nombre'];
            $province = Province::where('name', $provinceName)->first();
    
            if ($province) {
                City::updateOrCreate(
                    ['name' => $city['nombre'], 'province_id' => $province->id]
                );
            }
        }
    }
}
