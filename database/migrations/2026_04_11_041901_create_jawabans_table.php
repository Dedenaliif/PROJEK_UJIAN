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
        Schema::create('jawabans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('percobaan_ujian_id')->constrained('percobaan_ujians')->cascadeOnDelete();
            $table->foreignId('pertanyaan_id')->constrained('pertanyaans')->cascadeOnDelete();

            $table->enum('pilihan_jawaban', ['A', 'B', 'C', 'D'])->nullable();
            $table->boolean('benar')->default(false);
            $table->float('skor')->default(0);

            $table->unique(['percobaan_ujian_id', 'pertanyaan_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jawabans');
    }
};
