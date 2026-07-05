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
        Schema::table('rencana_anggaran', function (Blueprint $table) {
            // Hapus foreign key constraints yang menggunakan index ini
            $table->dropForeign(['kode_rekening_id']);
            $table->dropForeign(['diajukan_oleh']);
            $table->dropForeign(['disetujui_oleh']);

            // Baru hapus unique index
            $table->dropUnique('rencana_anggaran_kode_rekening_id_tahun_unique');

            // Kembalikan foreign key constraints
            $table->foreign('kode_rekening_id')
                ->references('id')
                ->on('kode_rekening')
                ->restrictOnDelete();

            $table->foreign('diajukan_oleh')
                ->references('id')
                ->on('users')
                ->restrictOnDelete();

            $table->foreign('disetujui_oleh')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rencana_anggaran', function (Blueprint $table) {
            // Hapus foreign keys dulu
            $table->dropForeign(['kode_rekening_id']);
            $table->dropForeign(['diajukan_oleh']);
            $table->dropForeign(['disetujui_oleh']);

            // Kembalikan unique index
            $table->unique(['kode_rekening_id', 'tahun'], 'rencana_anggaran_kode_rekening_id_tahun_unique');

            // Kembalikan foreign keys
            $table->foreign('kode_rekening_id')
                ->references('id')
                ->on('kode_rekening')
                ->restrictOnDelete();

            $table->foreign('diajukan_oleh')
                ->references('id')
                ->on('users')
                ->restrictOnDelete();

            $table->foreign('disetujui_oleh')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }
};