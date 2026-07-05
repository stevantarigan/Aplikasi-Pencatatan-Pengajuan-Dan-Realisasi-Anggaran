<?php
// app/Models/RencanaAnggaran.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RencanaAnggaran extends Model
{
    use HasFactory;

    protected $table = 'rencana_anggaran';

    protected $fillable = [
        'kode_rekening_id',
        'nama_rencana',
        'tahun',
        'total_anggaran',
        'status',
        'keterangan',
        'diajukan_oleh',
        'disetujui_oleh',
        'tanggal_persetujuan',
    ];

    protected $casts = [
        'tanggal_persetujuan' => 'datetime',
        'tahun' => 'integer',
        'total_anggaran' => 'float',
    ];

    /**
     * Setter untuk total_anggaran - otomatis membersihkan format Rupiah
     */
    public function setTotalAnggaranAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['total_anggaran'] = 0;
            return;
        }

        if (is_string($value)) {
            // Hapus "Rp" jika ada
            $value = str_replace(['Rp', 'rp', 'RP'], '', $value);
            // Hapus spasi
            $value = str_replace(' ', '', $value);
            // Hapus titik (pemisah ribuan)
            $value = str_replace('.', '', $value);
            // Ganti koma dengan titik (untuk desimal)
            $value = str_replace(',', '.', $value);
        }

        $this->attributes['total_anggaran'] = (float) $value;
    }

    /**
     * Accessor untuk total anggaran terformat
     */
    public function getTotalAnggaranFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_anggaran, 0, ',', '.');
    }

    /**
     * Relasi ke kode rekening
     */
    public function kodeRekening()
    {
        return $this->belongsTo(KodeRekening::class, 'kode_rekening_id');
    }

    /**
     * Relasi ke user pengaju
     */
    public function pengaju()
    {
        return $this->belongsTo(User::class, 'diajukan_oleh');
    }

    /**
     * Relasi ke user penyetuju
     */
    public function penyetuju()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }

    /**
     * Relasi ke anggaran (hanya jika status disetujui)
     */
    public function anggaran()
    {
        return $this->hasOne(Anggaran::class, 'rencana_anggaran_id');
    }

    /**
     * Scope untuk filter status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk filter tahun
     */
    public function scopeTahun($query, $tahun)
    {
        return $query->where('tahun', $tahun);
    }
}