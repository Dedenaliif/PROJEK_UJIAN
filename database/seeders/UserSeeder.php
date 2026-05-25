<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Siswa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'password' => Hash::make('admin123'),
                'role' => 'admin'
            ]
        );

        User::updateOrCreate(
            ['username' => 'penguji'],
            [
                'password' => Hash::make('penguji'),
                'role' => 'penguji'
            ]
        );

        User::updateOrCreate(
            ['username' => 'siswa'],
            [
                'password' => Hash::make('siswa'),
                'role' => 'siswa'
            ]
        );

        User::updateOrCreate(
            ['username' => 'pengawas'],
            [
                'password' => Hash::make('pengawas'),
                'role' => 'pengawas'
            ]
        );

        $files = [
            database_path('seeders/csv/template_siswa_tjkt1.csv'),
            database_path('seeders/csv/template_siswa_akl2.csv'),
            database_path('seeders/csv/template_siswa_mplb3.csv'),
            database_path('seeders/csv/template_siswa_pb4.csv'),
        ];

        foreach ($files as $file) {

            if (!file_exists($file)) {
                $this->command->warn("File tidak ditemukan: $file");
                continue;
            }

            $handle = fopen($file, 'r');

            fgetcsv($handle, 1000, ";");

            while (($data = fgetcsv($handle, 1000, ";")) !== false) {

                if (count($data) < 3) continue;

                $namaLengkap = trim($data[0]);
                $nis = trim($data[1]);
                $jurusanRaw = trim($data[2]);

                if (!$namaLengkap || !$nis || !$jurusanRaw) continue;

                $pecahNama = explode(' ', preg_replace('/\s+/', ' ', $namaLengkap));

                $namaBelakang = count($pecahNama) > 1
                    ? end($pecahNama)
                    : $pecahNama[0];

                $pecahJurusan = explode(
                    ' ',
                    preg_replace('/\s+/', ' ', $jurusanRaw)
                );

                $singkatanJurusan = '';

                $kataDiabaikan = [
                    'dan',
                    'atau',
                    '&',
                    'of',
                    'in'
                ];

                foreach ($pecahJurusan as $kataJurusan) {

                    $kataBersih = strtolower(trim($kataJurusan));

                    if (
                        in_array($kataBersih, $kataDiabaikan)
                        || empty($kataBersih)
                    ) {
                        continue;
                    }

                    $singkatanJurusan .= substr($kataBersih, 0, 1);
                }

                $singkatanJurusan = strtolower($singkatanJurusan);

                $username = Str::slug(
                    $nis . '.' . $namaBelakang . '.' . $singkatanJurusan,
                    '.'
                );

                DB::transaction(function () use (
                    $username,
                    $namaLengkap,
                    $nis
                ) {

                    $user = User::updateOrCreate(
                        ['username' => $username],
                        [
                            'password' => Hash::make($username),
                            'role' => 'siswa'
                        ]
                    );

                    Siswa::updateOrCreate(
                        ['user_id' => $user->id],
                        [
                            'nama_siswa' => $namaLengkap,
                            'nis' => $nis
                        ]
                    );
                });

                $this->command->info("Synced: $username");
            }

            fclose($handle);
        }
    }
}
