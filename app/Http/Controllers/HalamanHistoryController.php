<?php

namespace App\Http\Controllers;

use App\Models\PercobaanUjian;
use App\Models\Siswa;
use App\Models\Ujian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pertanyaan;
use Illuminate\Support\Carbon;
use App\Models\Jawaban;

class HalamanHistoryController extends Controller
{
    public function index()
    {
        return view('ujian.halaman-history');
    }
    public function history($ujian_id)
    {
        $ujian = Ujian::findOrFail($ujian_id);

        $siswa = Siswa::where('user_id', Auth::id())->first();

        $attempts = PercobaanUjian::where('user_id', Auth::id())
            ->where('ujian_id', $ujian_id)
            ->orderByDesc('percobaan_ke')
            ->get();

        $totalSoal = Pertanyaan::where('ujian_id', $ujian_id)->count();

        foreach ($attempts as $item) {

            $jawaban = Jawaban::where('percobaan_ujian_id', $item->id)->get();

            $benar = $jawaban->where('benar', 1)->count();

            $nilai = $totalSoal > 0
                ? round(($benar / $totalSoal) * 100)
                : 0;

            // 🔥 SET NILAI LANGSUNG KE OBJECT (TIDAK PERLU SAVE)
            $item->nilai_fix = $nilai;

            // 🔥 DURASI FIX (ANTI ERROR & DESIMAL)
            if ($item->waktu_mulai && $item->waktu_selesai) {
                $item->durasi = Carbon::parse($item->waktu_mulai)
                    ->diffInMinutes(Carbon::parse($item->waktu_selesai));
            } else {
                $item->durasi = 0;
            }
        }

        // 🔥 AMBIL NILAI TERTINGGI
        $maxScore = $attempts->max('nilai_fix');

        return view('ujian.halaman-history', compact(
            'ujian',
            'siswa',
            'attempts',
            'maxScore'
        ));

        }
}
