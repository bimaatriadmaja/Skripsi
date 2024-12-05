<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('jenis_genteng_id')->nullable(); 
            $table->foreign('jenis_genteng_id')->references('id')->on('jenis_genteng')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['jenis_genteng_id']);
            $table->dropColumn('jenis_genteng_id');
        });
    }

};
