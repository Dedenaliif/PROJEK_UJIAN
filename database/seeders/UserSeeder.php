<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'username' => 'admin',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
        ]);

        \App\Models\User::create([
            'username' => 'penguji',
            'password' => bcrypt('123'),
            'role' => 'penguji',
        ]);

        \App\Models\User::create([
            'username' => 'siswa',
            'password' => bcrypt('123'),
            'role' => 'siswa',
        ]);

        \App\Models\User::create([
            'username' => 'pengawas',
            'password' => bcrypt('123'),
            'role' => 'pengawas',
        ]);
    }
}
