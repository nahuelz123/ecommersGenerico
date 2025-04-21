<?php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use App\Models\Province;
class ProvincesSeeder extends Seeder
{

    public function run()
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

?>