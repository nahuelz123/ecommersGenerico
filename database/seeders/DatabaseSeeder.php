<?php

namespace Database\Seeders;

use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
{
/*    $admin = User::create([
        'name' => 'Admin',
        'email' => 'admin@admin.com',
        'password' => bcrypt('1234'),
    ]); 
    

    $client = User::create([
        'name' => 'Client',
        'email' => 'client@client.com',
        'password' => bcrypt('1234'),
    ]);*/
   
    $this->call([
      /*   BrandSeeder::class,
        CategorySeeder::class,
        ProductSeeder::class, 
        ProductImageSeeder::class, */
        ProvincesTableSeeder::class,
        CitiesTableSeeder::class
      
       
    ]); 
}

}
