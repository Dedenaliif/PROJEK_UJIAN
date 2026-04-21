<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Jurusan::create([
            'nama_jurusan' => 'TKJ',
        ]);

        \App\Models\Jurusan::create([
            'nama_jurusan' => 'MPLB',
        ]);

        \App\Models\Jurusan::create([
            'nama_jurusan' => 'AKL',
        ]);

        \App\Models\Jurusan::create([
            'nama_jurusan' => 'PR',
        ]);
    }
}
