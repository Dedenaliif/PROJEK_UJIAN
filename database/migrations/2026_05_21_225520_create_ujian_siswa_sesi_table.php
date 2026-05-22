<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUjianSiswaSesiTable extends Migration
{
    public function up()
    {
        Schema::create('ujian_siswa_sesi', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('ujian_id');
            $table->unsignedBigInteger('siswa_id');
            $table->unsignedBigInteger('sesi_id');

            $table->timestamps();

            $table->foreign('ujian_id')
                ->references('id')
                ->on('ujians')
                ->onDelete('cascade');

            $table->foreign('siswa_id')
                ->references('id')
                ->on('siswas')
                ->onDelete('cascade');

            $table->foreign('sesi_id')
                ->references('id')
                ->on('sesis')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ujian_siswa_sesi');
    }
}
