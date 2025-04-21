<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use App\Models\Province;
class ProvincesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $response = Http::get('https://apis.datos.gob.ar/georef/api/provincias');

       $provinces= $response->json()['provincias'];
        
            foreach($provinces as $province){
                Province::updateOrCreate(
                [ 'name' => $province['nombre']],
                ['id'=> $province['id']]
                );
            }
    }
}
