<?php

namespace Database\Seeders;

use Database\Seeders\SiswaSeeder;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call([
            UserSeeder::class,
            KelasSeeder::class,
            JurusanSeeder::class,
            UjianSeeder::class,
            PertanyaanSeeder::class,
            SiswaSeeder::class,
            SesiSeeder::class,
            DemoUjianSeeder::class
        ]);
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
