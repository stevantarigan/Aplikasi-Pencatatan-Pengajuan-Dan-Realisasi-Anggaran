<?php

// app/Models/Anggaran.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggaran extends Model
{
    use HasFactory;

    protected $table = 'anggaran';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'rencana_anggaran_id',
        'tahun',
        'total_anggaran',
        'sisa_anggaran',
        'status', // 'aktif' atau 'ditutup'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tahun' => 'integer',
        'total_anggaran' => 'float',
        'sisa_anggaran' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Boot method - untuk mengisi sisa_anggaran secara otomatis
     */
    protected static function boot()
    {
        parent::boot();

        // Event creating: otomatis mengisi sisa_anggaran = total_anggaran
        static::creating(function ($anggaran) {
            if (empty($anggaran->sisa_anggaran) && !empty($anggaran->total_anggaran)) {
                $anggaran->sisa_anggaran = $anggaran->total_anggaran;
            }

            // 🔴 PASTIKAN status default 'aktif' jika tidak diisi
            if (empty($anggaran->status)) {
                $anggaran->status = 'aktif';
            }
        });

        // Event updating: jika total_anggaran berubah, sesuaikan sisa_anggaran
        static::updating(function ($anggaran) {
            if ($anggaran->isDirty('total_anggaran') && !$anggaran->isDirty('sisa_anggaran')) {
                $selisih = $anggaran->total_anggaran - $anggaran->getOriginal('total_anggaran');
                $anggaran->sisa_anggaran = $anggaran->getOriginal('sisa_anggaran') + $selisih;
            }
        });
    }

    /**
     * Relasi ke rencana anggaran
     */
    public function rencanaAnggaran()
    {
        return $this->belongsTo(RencanaAnggaran::class, 'rencana_anggaran_id');
    }

    /**
     * Relasi ke pengajuan (pengajuan pemakaian anggaran)
     */
    public function pengajuan()
    {
        return $this->hasMany(PengajuanAnggaran::class, 'anggaran_id');
    }

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk filter berdasarkan tahun
     */
    public function scopeTahun($query, $tahun)
    {
        return $query->where('tahun', $tahun);
    }

    /**
     * Scope untuk anggaran aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    /**
     * 🔴 PERBAIKAN: Scope untuk anggaran ditutup
     */
    public function scopeDitutup($query)
    {
        return $query->where('status', 'ditutup');
    }

    /**
     * Accessor: total_anggaran dalam format Rupiah
     */
    public function getTotalAnggaranFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_anggaran, 0, ',', '.');
    }

    /**
     * Accessor: sisa_anggaran dalam format Rupiah
     */
    public function getSisaAnggaranFormattedAttribute()
    {
        return 'Rp ' . number_format($this->sisa_anggaran, 0, ',', '.');
    }

    /**
     * Total realisasi dari pengajuan dengan status 'direalisasi'
     */
    public function getTotalRealisasiAttribute()
    {
        return $this->pengajuan()
            ->where('status', 'direalisasi')
            ->sum('jumlah_realisasi');
    }

    /**
     * Accessor: total realisasi dalam format Rupiah
     */
    public function getTotalRealisasiFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_realisasi, 0, ',', '.');
    }

    /**
     * Persentase realisasi
     */
    public function getPersentaseRealisasiAttribute()
    {
        if ($this->total_anggaran <= 0) {
            return 0;
        }

        $totalRealisasi = $this->total_realisasi;
        return round(($totalRealisasi / $this->total_anggaran) * 100, 2);
    }

    /**
     * 🔴 PERBAIKAN: Method untuk update sisa anggaran berdasarkan realisasi
     */
    public function updateSisaAnggaran()
    {
        $totalRealisasi = $this->total_realisasi;
        $sisaBaru = $this->total_anggaran - $totalRealisasi;

        // Update hanya jika berbeda
        if ($this->sisa_anggaran != $sisaBaru) {
            $this->sisa_anggaran = $sisaBaru;

            // 🔴 PERUBAHAN: Jangan otomatis menutup anggaran, biarkan admin yang menentukan
            // if ($this->sisa_anggaran <= 0) {
            //     $this->status = 'ditutup'; // TIDAK LAGI OTOMATIS
            // }

            return $this->save();
        }

        return true;
    }

    /**
     * 🔴 PERBAIKAN: Cek apakah anggaran masih tersedia (aktif dan masih ada sisa)
     */
    public function isTersedia()
    {
        return $this->status === 'aktif' && $this->sisa_anggaran > 0;
    }

    /**
     * 🔴 PERBAIKAN: Cek apakah anggaran ditutup
     */
    public function isDitutup()
    {
        return $this->status === 'ditutup';
    }

    /**
     * 🔴 PERBAIKAN: Cek apakah anggaran aktif
     */
    public function isAktif()
    {
        return $this->status === 'aktif';
    }

    /**
     * 🔴 PERBAIKAN: Method untuk menutup anggaran
     */
    public function tutup()
    {
        if ($this->status !== 'ditutup') {
            $this->status = 'ditutup';
            return $this->save();
        }
        return true;
    }

    /**
     * 🔴 PERBAIKAN: Method untuk membuka kembali anggaran yang ditutup
     */
    public function buka()
    {
        if ($this->status !== 'aktif') {
            $this->status = 'aktif';
            return $this->save();
        }
        return true;
    }

    /**
     * 🔴 PERBAIKAN: Cek apakah anggaran bisa digunakan untuk pengajuan sejumlah nominal
     */
    public function bisaDigunakan($nominal)
    {
        return $this->isTersedia() && $this->sisa_anggaran >= $nominal;
    }

    /**
     * 🔴 PERBAIKAN: Get label status untuk tampilan
     */
    public function getStatusLabelAttribute()
    {
        return $this->status === 'aktif' ? 'Aktif' : 'Ditutup';
    }

    /**
     * 🔴 PERBAIKAN: Get badge class untuk tampilan
     */
    public function getStatusBadgeClassAttribute()
    {
        return $this->status === 'aktif' ? 'bg-success' : 'bg-secondary';
    }
}