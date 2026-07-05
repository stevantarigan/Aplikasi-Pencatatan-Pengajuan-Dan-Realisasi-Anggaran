<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PengajuanAnggaran;
use Carbon\Carbon;

class PengajuanAnggaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pengajuan = [
            [
                'nomor_pengajuan' => 'PA-2026-001',
                'anggaran_id' => 1,
                'tanggal_pengajuan' => Carbon::create(2026, 1, 5),
                'bulan' => 1,
                'jumlah_diajukan' => 100000000,
                'status' => 'direalisasi',
                'tanggal_realisasi' => Carbon::create(2026, 1, 20),
                'jumlah_realisasi' => 95000000,
                'keterangan_realisasi' => 'Realisasi pembelian operasional bulan Januari',
                'diajukan_oleh' => 2,
                'disetujui_oleh' => 1,
                'tanggal_persetujuan' => Carbon::create(2026, 1, 10),
                'keterangan' => 'Pengajuan anggaran operasional bulan Januari',
            ],
            [
                'nomor_pengajuan' => 'PA-2026-002',
                'anggaran_id' => 2,
                'tanggal_pengajuan' => Carbon::create(2026, 1, 10),
                'bulan' => 1,
                'jumlah_diajukan' => 75000000,
                'status' => 'disetujui',
                'diajukan_oleh' => 3,
                'disetujui_oleh' => 1,
                'tanggal_persetujuan' => Carbon::create(2026, 1, 15),
                'keterangan' => 'Pengajuan anggaran pemeliharaan alat',
            ],
            [
                'nomor_pengajuan' => 'PA-2026-003',
                'anggaran_id' => 1,
                'tanggal_pengajuan' => Carbon::create(2026, 2, 1),
                'bulan' => 2,
                'jumlah_diajukan' => 50000000,
                'status' => 'diajukan',
                'diajukan_oleh' => 2,
                'keterangan' => 'Pengajuan anggaran operasional bulan Februari',
            ],
            [
                'nomor_pengajuan' => 'PA-2026-004',
                'anggaran_id' => 3,
                'tanggal_pengajuan' => Carbon::create(2026, 1, 20),
                'bulan' => 1,
                'jumlah_diajukan' => 20000000,
                'status' => 'ditolak',
                'diajukan_oleh' => 2,
                'disetujui_oleh' => 1,
                'tanggal_persetujuan' => Carbon::create(2026, 1, 25),
                'keterangan' => 'Pengajuan anggaran pelatihan - ditolak karena tidak sesuai prioritas',
            ],
        ];

        foreach ($pengajuan as $data) {
            PengajuanAnggaran::create($data);
        }
    }
}