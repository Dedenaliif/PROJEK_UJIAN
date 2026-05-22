<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sesi;

class SesiSeeder extends Seeder
{
    public function run()
    {
        Sesi::insert([
            [
                'no_sesi' => 1,
                'jam_mulai' => '07:00:00',
                'jam_selesai' => '08:00:00'
            ],
            [
                'no_sesi' => 2,
                'jam_mulai' => '08:00:00',
                'jam_selesai' => '09:00:00'
            ],
            [
                'no_sesi' => 3,
                'jam_mulai' => '09:00:00',
                'jam_selesai' => '10:00:00'
            ]
        ]);
    }
}
