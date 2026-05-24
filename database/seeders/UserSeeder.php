<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
Use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // akun default
        User::create([
            'username' => 'admin',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
        ]);

        User::create([
            'username' => 'penguji',
            'password' => bcrypt('penguji'),
            'role' => 'penguji',
        ]);

        User::create([
            'username' => 'siswa',
            'password' => bcrypt('siswa'),
            'role' => 'siswa',
        ]);

        User::create([
            'username' => 'pengawas',
            'password' => bcrypt('pengawas'),
            'role' => 'pengawas',
        ]);

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

            // skip header
            fgetcsv($handle, 1000, ";");

            while (($data = fgetcsv($handle, 1000, ";")) !== false) {

                if (count($data) < 3) continue;

                $namaLengkap = trim($data[0]);
                $nis         = trim($data[1]);
                $jurusanRaw  = trim($data[2]);

                if (!$namaLengkap || !$nis || !$jurusanRaw) continue;

                /*
                ============================
                AMBIL NAMA BELAKANG
                ============================
                */
                $pecahNama = explode(' ', preg_replace('/\s+/', ' ', $namaLengkap));

                $namaBelakang = count($pecahNama) > 1
                    ? end($pecahNama)
                    : $pecahNama[0];

                /*
                ============================
                SINGKATAN JURUSAN
                ============================
                */
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

                /*
                ============================
                FORMAT USERNAME
                contoh:
                12345.permana.tkj
                ============================
                */
                $usernameRaw = $nis . '.' . $namaBelakang . '.' . $singkatanJurusan;

                $username = Str::slug($usernameRaw, '.');

                /*
                ============================
                PROTEKSI DUPLIKAT
                ============================
                */
                $usernameAsli = $username;
                $counter = 1;

                while (User::where('username', $username)->exists()) {
                    $username = $usernameAsli . $counter;
                    $counter++;
                }

                /*
                ============================
                SIMPAN USER + SISWA
                ============================
                */
                DB::transaction(function () use (
                    $username,
                    $namaLengkap,
                    $nis
                ) {

                    $user = User::create([
                        'username' => $username,
                        'password' => Hash::make($username),
                        'role' => 'siswa',
                    ]);

                    DB::table('siswas')->insert([
                        'user_id' => $user->id,
                        'nama_siswa' => $namaLengkap,
                        'nis' => $nis,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                });

                $this->command->info("Created: $username");
            }

            fclose($handle);
        }
    }

}
