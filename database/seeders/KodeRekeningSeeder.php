<?php

namespace Database\Seeders;

use App\Models\KodeRekening;
use Illuminate\Database\Seeder;

class KodeRekeningSeeder extends Seeder
{
    public function run()
    {
        KodeRekening::create([
            'kode' => '5.1.02.99.99.9999',
            'nama_rekening' => 'Belanja Barang dan Jasa BLUD',
            'tipe' => 'utama',
        ]);
    }
}