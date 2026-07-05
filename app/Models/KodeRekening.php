<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KodeRekening extends Model
{
    protected $table = 'kode_rekening';

    protected $fillable = [
        'kode',
        'nama_rekening', // Diubah dari 'nama' menjadi 'nama_rekening'
        'tipe', // Ditambahkan field tipe
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function rencanaAnggaran()
    {
        return $this->hasMany(RencanaAnggaran::class, 'kode_rekening_id');
    }
}