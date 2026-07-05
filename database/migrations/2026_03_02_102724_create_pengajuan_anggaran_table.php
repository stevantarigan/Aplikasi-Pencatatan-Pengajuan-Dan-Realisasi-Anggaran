<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengajuan_anggaran', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->string('nomor_pengajuan')->unique();

            $table->foreignId('anggaran_id')
                ->constrained('anggaran')
                ->restrictOnDelete();

            $table->date('tanggal_pengajuan');
            $table->tinyInteger('bulan');
            $table->decimal('jumlah_diajukan', 15, 2);

            $table->enum('status', [
                'diajukan',
                'disetujui',
                'ditolak',
                'direalisasi'
            ])->default('diajukan');

            $table->date('tanggal_realisasi')->nullable();
            $table->decimal('jumlah_realisasi', 15, 2)->nullable();
            $table->text('keterangan_realisasi')->nullable();

            $table->foreignId('diajukan_oleh')
                ->constrained('users')
                ->restrictOnDelete();

            $table->foreignId('disetujui_oleh')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->date('tanggal_persetujuan')->nullable();

            $table->text('keterangan')->nullable();

            $table->timestamps();

            // Indexes for performance
            $table->index('status');
            $table->index('bulan');
            $table->index('tanggal_pengajuan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_anggaran');
    }
};