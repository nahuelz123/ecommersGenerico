<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use app\Models\Province;
use app\Models\City;

class CitieSeeder extends Seeder
{
   public function Run()
   {
      $response = Http::get('https://apis.datos.gob.ar/georef/api/localidades?max=5000&campos=nombre,provincia');

      $cities = $response->json()['localidades'];

      foreach ($cities as $city) {

         $provinceName = $city['provincia']['nombre'];
         $province = Province::where('name', $provinceName)->first();

         if ($province) {
            City::CreateOrUpdate(
               ['name' => $city['localidadse'], 'province_id' => $province->id]
            );
         }
      }
   }
}
