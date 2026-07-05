<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('rencana_anggaran', function (Blueprint $table) {
            $table->id();

            $table->foreignId('kode_rekening_id')
                ->constrained('kode_rekening')
                ->restrictOnDelete();

            $table->string('nama_rencana');
            $table->year('tahun');
            $table->decimal('total_anggaran', 15, 2);

            $table->enum('status', [
                'diajukan',
                'disetujui',
                'ditolak',
                'revisi'
            ])->default('diajukan');

            $table->text('keterangan')->nullable();

            $table->foreignId('diajukan_oleh')
                ->constrained('users')
                ->restrictOnDelete();

            $table->foreignId('disetujui_oleh')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->date('tanggal_persetujuan')->nullable();

            $table->timestamps();

            // Satu rencana per kode rekening per tahun
            $table->unique(['kode_rekening_id', 'tahun']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('rencana_anggaran');
    }
};