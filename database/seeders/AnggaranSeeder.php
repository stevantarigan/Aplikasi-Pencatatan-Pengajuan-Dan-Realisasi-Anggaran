<?php

namespace Database\Seeders;

use App\Models\Anggaran;
use App\Models\RencanaAnggaran;
use Illuminate\Database\Seeder;

class AnggaranSeeder extends Seeder
{
    public function run(): void
    {
        $rencanaList = RencanaAnggaran::where('status', 'disetujui')->get();

        foreach ($rencanaList as $rencana) {
            Anggaran::create([
                'rencana_anggaran_id' => $rencana->id,
                'tahun' => $rencana->tahun,
                'total_anggaran' => $rencana->total_anggaran,
                'sisa_anggaran' => $rencana->total_anggaran, // penting
                'status' => 'aktif',
            ]);
        }
    }
}