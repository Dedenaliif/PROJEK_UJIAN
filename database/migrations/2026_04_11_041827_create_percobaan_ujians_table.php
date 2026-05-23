<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('percobaan_ujians', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ujian_id')->constrained()->cascadeOnDelete();

            $table->integer('percobaan_ke')->default(1);

            $table->timestamp('waktu_mulai')->nullable();
            $table->timestamp('waktu_selesai')->nullable();

            $table->enum('status', ['sedang dikerjakan', 'selesai'])->default('sedang dikerjakan');

            $table->integer('jawaban_benar')->nullable();
            $table->float('skor')->nullable();
            $table->float('skor_final')->nullable();
            $table->foreignId('dinilai_oleh')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('percobaan_ujians');
    }
};
