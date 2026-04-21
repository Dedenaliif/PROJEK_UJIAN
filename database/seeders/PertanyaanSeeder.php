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

            // SOAL WORD
            if ($ujian->tipe == 'word') {

                $soals = [
                    [
                        'text' => 'Shortcut untuk copy di Microsoft Word adalah?',
                        'a' => 'Ctrl + V',
                        'b' => 'Ctrl + C',
                        'c' => 'Ctrl + X',
                        'd' => 'Ctrl + P',
                        'jawaban' => 'B'
                    ],
                    [
                        'text' => 'Untuk membuat teks menjadi tebal menggunakan?',
                        'a' => 'Italic',
                        'b' => 'Underline',
                        'c' => 'Bold',
                        'd' => 'Strikethrough',
                        'jawaban' => 'C'
                    ],
                    [
                        'text' => 'Fungsi dari Ctrl + Z adalah?',
                        'a' => 'Redo',
                        'b' => 'Undo',
                        'c' => 'Save',
                        'd' => 'Print',
                        'jawaban' => 'B'
                    ],
                    [
                        'text' => 'Menu untuk mengatur margin di Word adalah?',
                        'a' => 'Insert',
                        'b' => 'Design',
                        'c' => 'Layout',
                        'd' => 'View',
                        'jawaban' => 'C'
                    ],
                    [
                        'text' => 'Ekstensi file Microsoft Word adalah?',
                        'a' => '.xls',
                        'b' => '.ppt',
                        'c' => '.docx',
                        'd' => '.pdf',
                        'jawaban' => 'C'
                    ],
                ];

            }

            // SOAL EXCEL
            elseif ($ujian->tipe == 'excel') {

                $soals = [
                    [
                        'text' => 'Rumus untuk menjumlahkan di Excel adalah?',
                        'a' => '=SUM()',
                        'b' => '=ADD()',
                        'c' => '=TOTAL()',
                        'd' => '=PLUS()',
                        'jawaban' => 'A'
                    ],
                    [
                        'text' => 'Rumus untuk rata-rata di Excel?',
                        'a' => '=SUM()',
                        'b' => '=AVERAGE()',
                        'c' => '=COUNT()',
                        'd' => '=MAX()',
                        'jawaban' => 'B'
                    ],
                    [
                        'text' => 'Untuk menghitung jumlah data digunakan?',
                        'a' => '=COUNT()',
                        'b' => '=SUM()',
                        'c' => '=IF()',
                        'd' => '=VLOOKUP()',
                        'jawaban' => 'A'
                    ],
                    [
                        'text' => 'Fungsi IF digunakan untuk?',
                        'a' => 'Penjumlahan',
                        'b' => 'Logika kondisi',
                        'c' => 'Rata-rata',
                        'd' => 'Mengurutkan',
                        'jawaban' => 'B'
                    ],
                    [
                        'text' => 'Ekstensi file Excel adalah?',
                        'a' => '.docx',
                        'b' => '.ppt',
                        'c' => '.xlsx',
                        'd' => '.txt',
                        'jawaban' => 'C'
                    ],
                ];
            }

            // 🔥 INSERT KE DATABASE
            foreach ($soals as $s) {
                Pertanyaan::create([
                    'ujian_id' => $ujian->id,
                    'text_pertanyaan' => $s['text'],
                    'opsi_a' => $s['a'],
                    'opsi_b' => $s['b'],
                    'opsi_c' => $s['c'],
                    'opsi_d' => $s['d'],
                    'jawaban_benar' => $s['jawaban'],
                    'skor' => 100 / 5
                ]);
            }
        }
    }
}
