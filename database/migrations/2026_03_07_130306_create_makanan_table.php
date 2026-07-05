<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMakananTable extends Migration
{
    public function up()
    {
        Schema::create('makanan', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->default('Makanan Pasien'); // Nama makanan
            $table->decimal('harga', 15, 2)->default(50000); // Harga per pasien
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // Insert data default
        DB::table('makanan')->insert([
            'nama' => 'Makanan Pasien',
            'harga' => 50000,
            'keterangan' => 'Harga makanan standar per pasien',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('makanan');
    }
}
