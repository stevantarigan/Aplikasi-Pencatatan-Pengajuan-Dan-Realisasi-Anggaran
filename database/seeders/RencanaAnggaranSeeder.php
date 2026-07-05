<?php

namespace Database\Seeders;

use App\Models\RencanaAnggaran;
use App\Models\KodeRekening;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class RencanaAnggaranSeeder extends Seeder
{
    public function run(): void
    {
        $kodeRekening = KodeRekening::first();
        $kepalaGizi = User::where('role', 'kepala_gizi')->first();
        $keuangan = User::where('role', 'keuangan')->first();

        if (!$kodeRekening || !$kepalaGizi || !$keuangan) {
            $this->command->warn('Seeder RencanaAnggaran dilewati: data pendukung belum lengkap.');
            return;
        }

        $data = [
            [
                'nama_rencana' => 'Belanja Bahan Makanan Pasien',
                'total_anggaran' => 1200000000,
                'keterangan' => 'Rencana tahunan belanja bahan makanan pasien',
            ],
            [
                'nama_rencana' => 'Pemeliharaan Peralatan Dapur',
                'total_anggaran' => 350000000,
                'keterangan' => 'Rencana biaya pemeliharaan alat dapur dan pendingin',
            ],
            [
                'nama_rencana' => 'Pengadaan Alat Masak Baru',
                'total_anggaran' => 500000000,
                'keterangan' => 'Pengadaan peralatan masak untuk peningkatan kapasitas produksi',
            ],
        ];

        foreach ($data as $item) {
            RencanaAnggaran::create([
                'kode_rekening_id' => $kodeRekening->id,
                'nama_rencana' => $item['nama_rencana'],
                'tahun' => Carbon::now()->year,
                'total_anggaran' => $item['total_anggaran'],
                'status' => 'disetujui',
                'keterangan' => $item['keterangan'],
                'diajukan_oleh' => $kepalaGizi->id,
                'disetujui_oleh' => $keuangan->id,
                'tanggal_persetujuan' => Carbon::now(),
            ]);
        }
    }
}