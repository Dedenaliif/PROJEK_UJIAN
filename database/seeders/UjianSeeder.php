<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ujian;
use Carbon\Carbon;

class UjianSeeder extends Seeder
{
    public function run()
    {
        Ujian::create([
            'judul' => 'Ujian Microsoft Word',
            'deskripsi' => 'pengelolaan dokumen, format teks/paragraf, tabel, gambar, referensi, review, dan studi kasus dokumen.',
            'waktu' => 30,
            'max_percobaan' => 3,
            'waktu_mulai' => Carbon::now(),
            'waktu_selesai' => Carbon::now()->addDays(7),
            'tipe' => 'word'
        ]);

        Ujian::create([
            'judul' => 'Ujian Microsoft Excel',
            'deskripsi' => 'sel, rumus, fungsi dasar-menengah, pengolahan data, visualisasi, PivotTable, dan studi kasus spreadsheet',
            'waktu' => 30,
            'max_percobaan' => 3,
            'waktu_mulai' => Carbon::now(),
            'waktu_selesai' => Carbon::now()->addDays(7),
            'tipe' => 'excel'
        ]);
    }
}
