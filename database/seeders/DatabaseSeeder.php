<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UserSeeder::class,
            KodeRekeningSeeder::class,
            RencanaAnggaranSeeder::class,
            AnggaranSeeder::class,
            PengajuanAnggaranSeeder::class,
            
        ]);
    }
}