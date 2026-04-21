<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PercobaanUjian;
use App\Models\Ujian;
use App\Models\Jawaban;
use Carbon\Carbon;

class AutoSubmitUjian extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:auto-submit-ujian';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */


    public function handle()
    {
        $percobaans = PercobaanUjian::where('status', 'sedang dikerjakan')->get();

        foreach ($percobaans as $p) {

            $ujian = Ujian::find($p->ujian_id);

            if (!$ujian) continue;

            $waktuMulai = Carbon::parse($p->waktu_mulai);
            $waktuSelesai = $waktuMulai->copy()->addMinutes($ujian->waktu);

            if (now()->greaterThanOrEqualTo($waktuSelesai)) {

                $totalSkor = Jawaban::where('percobaan_ujian_id', $p->id)->sum('skor');

                $p->DB::update([
                    'status' => 'selesai',
                    'waktu_selesai' => now(),
                    'skor' => $totalSkor,
                    'nilai' => $totalSkor
                ]);
            }
        }
    }
}
