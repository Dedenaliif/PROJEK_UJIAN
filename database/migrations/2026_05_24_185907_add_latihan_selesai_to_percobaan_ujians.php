<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('percobaan_ujians', function (Blueprint $table) {
            $table->boolean('latihan_selesai')->default(false);
        });
    }

    public function down()
    {
        Schema::table('percobaan_ujians', function (Blueprint $table) {
            $table->dropColumn('latihan_selesai');
        });
    }
};
