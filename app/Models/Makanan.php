<?php
// app/Models/Makanan.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Makanan extends Model
{
    use HasFactory;

    protected $table = 'makanan';

    protected $fillable = [
        'nama',
        'harga',
        'keterangan'
    ];

    protected $casts = [
        'harga' => 'float'
    ];

    /**
     * Mendapatkan harga makanan (selalu ambil data pertama)
     * Karena hanya 1 data yang dibutuhkan
     */
    public static function getHarga()
    {
        $makanan = self::first();

        if (!$makanan) {
            // Buat data default jika belum ada
            $makanan = self::create([
                'nama' => 'Makanan Pasien',
                'harga' => 50000,
                'keterangan' => 'Harga makanan standar per pasien'
            ]);
        }

        return $makanan->harga;
    }

    /**
     * Update harga makanan
     */
    public static function updateHarga($hargaBaru)
    {
        $makanan = self::first();

        if (!$makanan) {
            $makanan = new self();
            $makanan->nama = 'Makanan Pasien';
        }

        $makanan->harga = $hargaBaru;
        $makanan->save();

        return $makanan;
    }

    /**
     * Mendapatkan harga dalam format Rupiah
     */
    public function getHargaFormattedAttribute()
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }
}