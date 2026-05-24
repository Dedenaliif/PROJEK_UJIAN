<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLatihanUjiansTable extends Migration
{
    public function up()
    {
        Schema::create('latihan_ujians', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('ujian_id');
            $table->boolean('selesai')->default(false);
            $table->integer('nilai')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('latihan_ujians');
    }
}
