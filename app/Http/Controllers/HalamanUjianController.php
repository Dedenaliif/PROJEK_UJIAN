<?php

namespace App\Http\Controllers;

use App\Models\Jawaban;
use App\Models\PercobaanUjian;
use App\Models\Ujian;
use App\Models\Pertanyaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HalamanUjianController extends Controller
{
    public function show(Request $request, $ujianId)
    {
        $ujian = Ujian::findOrFail($ujianId);

        $soals = Pertanyaan::where('ujian_id', $ujianId)->get();
        $total = $soals->count();

        $current = $request->get('no', 1);
        $current = max(1, min($current, $total));

        $soal = $soals[$current - 1] ?? null;

        // 🔥 ambil / buat percobaan
        $percobaan = PercobaanUjian::firstOrCreate(
            [
                'user_id' => Auth::user()->id,
                'ujian_id' => $ujianId,
            ],
            [
                'status' => 'sedang dikerjakan',
                'waktu_mulai' => now(),
                'percobaan_ke' => 1
            ]
        );

        // 🔥 ambil jawaban user
        $jawabanUser = Jawaban::where('percobaan_ujian_id', $percobaan->id)
            ->pluck('pilihan_jawaban', 'pertanyaan_id');

        return view('ujian.halaman-ujian', compact(
            'ujian',
            'soals',
            'soal',
            'current',
            'percobaan',
            'jawabanUser'
        ));
    }

    public function save(Request $request, $ujianId)
    {
        $percobaan = PercobaanUjian::where('user_id', Auth::user()->id)
            ->where('ujian_id', $ujianId)
            ->where('status', 'sedang dikerjakan')
            ->first();

        if (!$percobaan) {
            return response()->json(['error' => 'Percobaan tidak ditemukan'], 400);
        }

        Jawaban::updateOrCreate(
            [
                'percobaan_ujian_id' => $percobaan->id,
                'pertanyaan_id' => $request->soal_id
            ],
            [
                'pilihan_jawaban' => $request->jawaban
            ]
        );

        return response()->json(['status' => 'ok']);
    }

    public function start($ujianId)
    {
        $userId = Auth::user()->id;

        $percobaan = PercobaanUjian::where('user_id', $userId)
            ->where('ujian_id', $ujianId)
            ->where('status', 'sedang dikerjakan')
            ->first();

        if (!$percobaan) {
            $last = PercobaanUjian::where('user_id', $userId)
                ->where('ujian_id', $ujianId)
                ->max('percobaan_ke');

            PercobaanUjian::create([
                'user_id' => $userId,
                'ujian_id' => $ujianId,
                'percobaan_ke' => $last ? $last + 1 : 1,
                'waktu_mulai' => now(),
                'status' => 'sedang dikerjakan'
            ]);
        }

        return redirect()->route('ujianstart.show', $ujianId);
    }

    public function selesai($ujianId)
    {
        $percobaan = PercobaanUjian::where('user_id', Auth::user()->id)
            ->where('ujian_id', $ujianId)
            ->where('status', 'sedang dikerjakan')
            ->first();

        if ($percobaan) {
            $percobaan->update([
                'status' => 'selesai',
                'waktu_selesai' => now()
            ]);
        }

        return redirect()->route('ujian.index');
    }
}
