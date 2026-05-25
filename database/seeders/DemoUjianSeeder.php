<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Ujian;
use App\Models\Sesi;
use App\Models\PercobaanUjian;
use App\Models\UjianSiswaSesi;

class DemoUjianSeeder extends Seeder
{
    public function run(): void
    {
        $ujians = Ujian::all();
        $sesis = Sesi::all();

        if ($ujians->count() < 2 || $sesis->isEmpty()) {
            return;
        }

        $word = $ujians->where('tipe', 'word')->first();
        $excel = $ujians->where('tipe', 'excel')->first();

        for ($i = 1; $i <= 50; $i++) {

            $user = User::create([
                'username' => 'demo'.$i,
                'role' => 'siswa',
                'password' => Hash::make('123456'),
            ]);

            $siswa = Siswa::create([
                'user_id' => $user->id,
                'nama_siswa' => 'Siswa Demo '.$i,
                'nis' => '2026'.str_pad($i,4,'0',STR_PAD_LEFT),
                'kelas_id' => rand(1,3),
                'jurusan_id' => rand(1,3),
            ]);

            foreach ([$word,$excel] as $ujian) {

                $sesi = $sesis->random();

                UjianSiswaSesi::create([
                    'ujian_id'=>$ujian->id,
                    'siswa_id'=>$siswa->id,
                    'sesi_id'=>$sesi->id
                ]);

                $mode = rand(1,4);

                /*
                1 = langsung lulus
                2 = remedial lalu lulus
                3 = gagal semua
                4 = belum ujian
                */

                if($mode==4){
                    continue;
                }

                $jumlahPercobaan = rand(1,3);

                for($p=1;$p<=$jumlahPercobaan;$p++){

                    if($mode==1){
                        $skor=rand(75,95);
                        $final=$skor;
                    }
                    elseif($mode==2){

                        if($p<$jumlahPercobaan){
                            $skor=rand(40,70);
                            $final=rand(70,74);
                        }else{
                            $skor=rand(50,70);
                            $final=rand(75,90);
                        }

                    }else{

                        $skor=rand(30,70);
                        $final=rand(40,74);

                    }

                    PercobaanUjian::create([
                        'user_id'=>$user->id,
                        'ujian_id'=>$ujian->id,
                        'percobaan_ke'=>$p,
                        'status'=>'selesai',
                        'jawaban_benar'=>rand(5,20),
                        'skor'=>$skor,
                        'skor_final'=>$final,
                        'waktu_mulai'=>now()->subDays(rand(1,30)),
                        'waktu_selesai'=>now()->subDays(rand(1,30)),
                    ]);
                }
            }
        }
    }
}
