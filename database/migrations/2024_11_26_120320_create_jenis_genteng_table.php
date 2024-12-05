<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJenisGentengTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jenis_genteng', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama_jenis'); // Nama jenis genteng, misalnya "Kerpus", "Mantili", "Biasa"
            $table->integer('gaji_per_seribu'); // Gaji per seribu biji genteng
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jenis_genteng');
    }
}