<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataPasienTable extends Migration
{
    public function up()
    {
        Schema::create('data_pasien', function (Blueprint $table) {
            $table->id();

            $table->unsignedTinyInteger('bulan'); // 1 - 12
            $table->unsignedSmallInteger('tahun'); // contoh: 2024
            $table->unsignedInteger('jumlah_pasien');

            $table->timestamps();

            // agar tidak ada data dobel di bulan & tahun yang sama
            $table->unique(['bulan', 'tahun']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('data_pasien');
    }
}
