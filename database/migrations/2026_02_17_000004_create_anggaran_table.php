<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('anggaran', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();

            $table->unsignedBigInteger('rencana_anggaran_id');

            $table->year('tahun');
            $table->decimal('total_anggaran', 15, 2);
            $table->decimal('sisa_anggaran', 15, 2); // ✅ TAMBAHKAN INI

            $table->enum('status', ['aktif', 'ditutup'])->default('aktif');

            $table->timestamps();

            $table->foreign('rencana_anggaran_id')
                ->references('id')
                ->on('rencana_anggaran')
                ->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('anggaran');
    }
};