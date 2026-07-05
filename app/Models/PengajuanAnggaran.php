<?php
// app/Models/PengajuanAnggaran.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanAnggaran extends Model
{
    protected $table = 'pengajuan_anggaran';

    protected $fillable = [
        'nomor_pengajuan',
        'anggaran_id',
        'tanggal_pengajuan',
        'bulan',
        'jumlah_diajukan',
        'status',
        'tanggal_realisasi',
        'jumlah_realisasi',
        'keterangan_realisasi',
        'diajukan_oleh',
        'disetujui_oleh',
        'tanggal_persetujuan',
        'keterangan',
    ];

    protected $dates = [
        'tanggal_pengajuan',
        'tanggal_persetujuan',
        'tanggal_realisasi',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'jumlah_diajukan' => 'float',
        'jumlah_realisasi' => 'float',
    ];

    /**
     * Get all available statuses
     */
    public static function getStatuses()
    {
        return [
            'diajukan' => 'Diajukan',
            'disetujui' => 'Disetujui',
            'ditolak' => 'Ditolak',
            'direalisasi' => 'Direalisasi',
        ];
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClassAttribute()
    {
        $classes = [
            'diajukan' => 'bg-warning',
            'disetujui' => 'bg-success',
            'ditolak' => 'bg-danger',
            'direalisasi' => 'bg-info',
        ];
        return $classes[$this->status] ?? 'bg-secondary';
    }

    /**
     * Get status icon
     */
    public function getStatusIconAttribute()
    {
        $icons = [
            'diajukan' => 'clock-history',
            'disetujui' => 'check2-circle',
            'ditolak' => 'x-circle',
            'direalisasi' => 'check2-all',
        ];
        return $icons[$this->status] ?? 'question-circle';
    }

    /**
     * Get sisa anggaran (diajukan - realisasi)
     */
    public function getSisaAnggaranAttribute()
    {
        return (float) ($this->jumlah_diajukan - ($this->jumlah_realisasi ?? 0));
    }

    /**
     * Get nama bulan
     */
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
        return $bulan[$this->bulan] ?? '-';
    }

    /**
     * Relationships
     */
    public function anggaran()
    {
        return $this->belongsTo(Anggaran::class);
    }

    public function pengaju()
    {
        return $this->belongsTo(User::class, 'diajukan_oleh');
    }

    public function penyetuju()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }

    /**
     * Scopes
     */
    public function scopeDiajukan($query)
    {
        return $query->where('status', 'diajukan');
    }

    public function scopeDisetujui($query)
    {
        return $query->where('status', 'disetujui');
    }

    public function scopeDitolak($query)
    {
        return $query->where('status', 'ditolak');
    }

    public function scopeDirealisasi($query)
    {
        return $query->where('status', 'direalisasi');
    }

    public function scopeByBulan($query, $bulan)
    {
        return $query->where('bulan', $bulan);
    }
}