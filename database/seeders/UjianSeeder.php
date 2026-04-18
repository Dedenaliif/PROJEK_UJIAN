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
            'deskripsi' => 'Tes kemampuan Microsoft Word',
            'waktu' => 30,
            'max_percobaan' => 3,
            'waktu_mulai' => Carbon::now(),
            'waktu_selesai' => Carbon::now()->addDays(7),
            'tipe' => 'word'
        ]);

        Ujian::create([
            'judul' => 'Ujian Microsoft Excel',
            'deskripsi' => 'Tes kemampuan Microsoft Excel',
            'waktu' => 30,
            'max_percobaan' => 3,
            'waktu_mulai' => Carbon::now(),
            'waktu_selesai' => Carbon::now()->addDays(7),
            'tipe' => 'excel'
        ]);
    }
}
