<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pertanyaan;
use App\Models\Ujian;

class PertanyaanSeeder extends Seeder
{
    public function run()
    {
        $ujians = Ujian::all();

        foreach ($ujians as $ujian) {

            for ($i = 1; $i <= 30; $i++) {

                Pertanyaan::create([
                    'ujian_id' => $ujian->id,
                    'text_pertanyaan' => "Soal {$ujian->tipe} nomor {$i}",
                    'opsi_a' => 'Jawaban A',
                    'opsi_b' => 'Jawaban B',
                    'opsi_c' => 'Jawaban C',
                    'opsi_d' => 'Jawaban D',
                    'jawaban_benar' => ['A','B','C','D'][rand(0,3)],
                    'skor' => 100 / 30
                ]);
            }

        }
    }
}
