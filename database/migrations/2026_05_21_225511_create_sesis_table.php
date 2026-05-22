<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSesisTable extends Migration
{
    public function up()
    {
        Schema::create('sesis', function (Blueprint $table) {
            $table->id();

            $table->integer('no_sesi');

            $table->time('jam_mulai');
            $table->time('jam_selesai');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sesis');
    }
}
