<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PasienSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('data_pasien')->insert([

            // ===== DATA 2024 =====
            ['bulan' => 1, 'tahun' => 2024, 'jumlah_pasien' => 1205],
            ['bulan' => 2, 'tahun' => 2024, 'jumlah_pasien' => 1032],
            ['bulan' => 3, 'tahun' => 2024, 'jumlah_pasien' => 1134],
            ['bulan' => 4, 'tahun' => 2024, 'jumlah_pasien' => 977],
            ['bulan' => 5, 'tahun' => 2024, 'jumlah_pasien' => 1350],
            ['bulan' => 6, 'tahun' => 2024, 'jumlah_pasien' => 1317],
            ['bulan' => 7, 'tahun' => 2024, 'jumlah_pasien' => 1302],
            ['bulan' => 8, 'tahun' => 2024, 'jumlah_pasien' => 1185],
            ['bulan' => 9, 'tahun' => 2024, 'jumlah_pasien' => 1289],
            ['bulan' => 10, 'tahun' => 2024, 'jumlah_pasien' => 1143],
            ['bulan' => 11, 'tahun' => 2024, 'jumlah_pasien' => 1273],
            ['bulan' => 12, 'tahun' => 2024, 'jumlah_pasien' => 1099],

            // ===== DATA 2025 =====
            ['bulan' => 1, 'tahun' => 2025, 'jumlah_pasien' => 972],
            ['bulan' => 2, 'tahun' => 2025, 'jumlah_pasien' => 877],
            ['bulan' => 3, 'tahun' => 2025, 'jumlah_pasien' => 1007],
            ['bulan' => 4, 'tahun' => 2025, 'jumlah_pasien' => 1010],
            ['bulan' => 5, 'tahun' => 2025, 'jumlah_pasien' => 1606],
            ['bulan' => 6, 'tahun' => 2025, 'jumlah_pasien' => 1394],
            ['bulan' => 7, 'tahun' => 2025, 'jumlah_pasien' => 1407],
            ['bulan' => 8, 'tahun' => 2025, 'jumlah_pasien' => 1335],
            ['bulan' => 9, 'tahun' => 2025, 'jumlah_pasien' => 1510],
            ['bulan' => 10, 'tahun' => 2025, 'jumlah_pasien' => 1781],
            ['bulan' => 11, 'tahun' => 2025, 'jumlah_pasien' => 1362],
            ['bulan' => 12, 'tahun' => 2025, 'jumlah_pasien' => 1099],

        ]);
    }
}