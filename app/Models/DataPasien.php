<?php
// app/Models/DataPasien.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataPasien extends Model
{
    use HasFactory;

    protected $table = 'data_pasien';

    protected $fillable = [
        'bulan',
        'tahun',
        'jumlah_pasien'
    ];

    protected $casts = [
        'bulan' => 'integer',
        'tahun' => 'integer',
        'jumlah_pasien' => 'integer'
    ];

    // Accessor untuk menampilkan nama bulan
    public function getNamaBulanAttribute()
    {
        $bulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        return $bulan[$this->bulan] ?? 'Tidak Diketahui';
    }

    // Accessor untuk menampilkan periode (Bulan Tahun)
    public function getPeriodeAttribute()
    {
        return $this->nama_bulan . ' ' . $this->tahun;
    }

    /**
     * Mendapatkan total pasien per tahun
     */
    public static function getTotalPasienPerTahun($tahun)
    {
        return self::where('tahun', $tahun)->sum('jumlah_pasien');
    }

    /**
     * Mendapatkan rata-rata pasien per bulan untuk tahun tertentu
     */
    public static function getRataRataPasienPerBulan($tahun)
    {
        $total = self::getTotalPasienPerTahun($tahun);

        if ($total == 0) {
            return 0;
        }

        // Hitung jumlah bulan yang memiliki data
        $jumlahBulan = self::where('tahun', $tahun)->count();

        if ($jumlahBulan == 0) {
            return 0;
        }

        return $total / $jumlahBulan;
    }

    /**
     * Mendapatkan harga makanan per pasien dari tabel makanan
     */
    public static function getHargaMakanan()
    {
        $makanan = Makanan::first();
        return $makanan ? $makanan->harga : 50000;
    }

    /**
     * Mendapatkan rekomendasi anggaran berdasarkan data pasien
     * Rumus: Total Pasien * Harga Makanan
     */
    public static function getRekomendasiAnggaran($tahun)
    {
        $totalPasien = self::getTotalPasienPerTahun($tahun);
        $hargaMakanan = self::getHargaMakanan();

        // Jika tidak ada data, kembalikan 0
        if ($totalPasien == 0) {
            return [
                'total_pasien' => 0,
                'rata_rata_per_bulan' => 0,
                'anggaran_per_bulan' => 0,
                'anggaran_per_tahun' => 0,
                'harga_makanan' => $hargaMakanan,
                'ada_data' => false
            ];
        }

        $jumlahBulan = self::where('tahun', $tahun)->count();
        $rataRataPerBulan = $totalPasien / 12; // Standar 12 bulan
        $anggaranPerBulan = $rataRataPerBulan * $hargaMakanan;
        $anggaranPerTahun = $totalPasien * $hargaMakanan;

        return [
            'total_pasien' => $totalPasien,
            'jumlah_bulan' => $jumlahBulan,
            'rata_rata_per_bulan' => round($rataRataPerBulan, 2),
            'anggaran_per_bulan' => round($anggaranPerBulan),
            'anggaran_per_tahun' => round($anggaranPerTahun),
            'harga_makanan' => $hargaMakanan,
            'ada_data' => true
        ];
    }

    /**
     * Mendapatkan rekomendasi keputusan berdasarkan perbandingan anggaran
     * @param int $tahunRencana - Tahun rencana yang diajukan
     * @param float $anggaranDiajukan - Jumlah anggaran yang diajukan
     */
    /**
     * Mendapatkan rekomendasi keputusan berdasarkan perbandingan anggaran
     * @param int $tahunRencana - Tahun rencana yang diajukan
     * @param float $anggaranDiajukan - Jumlah anggaran yang diajukan
     */
    public static function getRekomendasiKeputusan($tahunRencana, $anggaranDiajukan)
    {
        // Gunakan data tahun sebelumnya untuk perhitungan
        $tahunData = $tahunRencana - 1;
        $rekomendasiAnggaran = self::getRekomendasiAnggaran($tahunData);

        if (!$rekomendasiAnggaran['ada_data']) {
            return [
                'status' => 'tidak_ada_data',
                'teks' => 'Tidak ada data pasien untuk tahun ' . $tahunData . ' sebagai dasar perhitungan untuk rencana tahun ' . $tahunRencana . '.',
                'rekomendasi' => 0,
                'batas_atas' => 0
            ];
        }

        $rekomendasi = $rekomendasiAnggaran['anggaran_per_tahun'];
        $batasAtas = $rekomendasi * 1.1; // +10%
        $selisih = $anggaranDiajukan - $rekomendasi;

        // Hitung persentase kelebihan (bukan persentase dari total)
        $persentaseKelebihan = round(($selisih / $rekomendasi) * 100, 2);
        $persentaseDariTotal = round(($anggaranDiajukan / $rekomendasi) * 100, 2);

        if ($anggaranDiajukan <= $rekomendasi) {
            return [
                'status' => 'diterima',
                'teks' => "Rekomendasi sistem: Pengajuan untuk tahun $tahunRencana diterima karena sesuai dengan estimasi kebutuhan pasien berdasarkan data tahun $tahunData. Anggaran diajukan (Rp " . number_format($anggaranDiajukan, 0, ',', '.') . ") lebih kecil atau sama dengan rekomendasi (Rp " . number_format($rekomendasi, 0, ',', '.') . ").",
                'rekomendasi' => $rekomendasi,
                'batas_atas' => $batasAtas,
                'selisih' => $selisih,
                'persentase_kelebihan' => 0,
                'persentase_dari_total' => $persentaseDariTotal
            ];
        } elseif ($anggaranDiajukan <= $batasAtas) {
            return [
                'status' => 'masih_layak',
                'teks' => "Rekomendasi sistem: Pengajuan untuk tahun $tahunRencana masih layak diterima meskipun melebihi estimasi, namun masih dalam batas toleransi 10% berdasarkan data tahun $tahunData. Anggaran diajukan (Rp " . number_format($anggaranDiajukan, 0, ',', '.') . ") melebihi rekomendasi (Rp " . number_format($rekomendasi, 0, ',', '.') . ") sebesar Rp " . number_format($selisih, 0, ',', '.') . " atau " . number_format($persentaseKelebihan, 2) . "%.",
                'rekomendasi' => $rekomendasi,
                'batas_atas' => $batasAtas,
                'selisih' => $selisih,
                'persentase_kelebihan' => $persentaseKelebihan,
                'persentase_dari_total' => $persentaseDariTotal
            ];
        } else {
            return [
                'status' => 'disarankan_tolak',
                'teks' => "Rekomendasi sistem: Pengajuan untuk tahun $tahunRencana melebihi batas toleransi 10% dari estimasi kebutuhan pasien berdasarkan data tahun $tahunData sehingga disarankan untuk ditolak atau direvisi. Anggaran diajukan (Rp " . number_format($anggaranDiajukan, 0, ',', '.') . ") melebihi batas toleransi (Rp " . number_format($batasAtas, 0, ',', '.') . ") dengan kelebihan Rp " . number_format($selisih - ($rekomendasi * 0.1), 0, ',', '.') . " atau " . number_format($persentaseKelebihan, 2) . "% dari rekomendasi.",
                'rekomendasi' => $rekomendasi,
                'batas_atas' => $batasAtas,
                'selisih' => $selisih,
                'persentase_kelebihan' => $persentaseKelebihan,
                'persentase_dari_total' => $persentaseDariTotal
            ];
        }
    }

    /**
     * Mendapatkan daftar tahun yang memiliki data pasien
     */
    public static function getTahunList()
    {
        return self::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');
    }
}