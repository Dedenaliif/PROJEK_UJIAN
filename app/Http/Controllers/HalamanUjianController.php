<?php

namespace App\Http\Controllers;

use App\Models\Jawaban;
use App\Models\PercobaanUjian;
use App\Models\Ujian;
use App\Models\Pertanyaan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;

class HalamanUjianController extends Controller
{
    private function forceSelesai($percobaan)
    {
        $jawaban = Jawaban::where('percobaan_ujian_id', $percobaan->id)->get();

        $totalSoal = Pertanyaan::where('ujian_id', $percobaan->ujian_id)->count();

        // 🔥 FIX DI SINI
        $jawabanBenar = $jawaban->where('benar', 1)->count();

        $nilai = $totalSoal > 0
            ? round(($jawabanBenar / $totalSoal) * 100)
            : 0;

        $percobaan->update([
            'status' => 'selesai',
            'waktu_selesai' => now(),
            'skor' => $jawabanBenar,
            'nilai' => $nilai
        ]);

        return redirect()->route('ujian.hasil', $percobaan->ujian_id)
            ->with('info', 'Waktu habis, ujian otomatis diselesaikan');
    }

    public function show(Request $request, $ujianId)
    {
        $ujian = Ujian::findOrFail($ujianId);
        $current = (int) $request->get('no', 1);

        $percobaan = PercobaanUjian::where('user_id', Auth::id())
            ->where('ujian_id', $ujianId)
            ->where('status', 'sedang dikerjakan')
            ->latest()
            ->first();

        if (!$percobaan) {
            return redirect()->route('siswa.ujian')
                ->with('error', 'Silakan mulai ujian terlebih dahulu');
        }

        // 🔥 AMBIL SOAL DARI SESSION
        $soalIds = session('soal_ujian_'.$percobaan->id);

        if (!$soalIds) {
            $soalIds = Pertanyaan::where('ujian_id', $ujianId)
                ->pluck('id')
                ->toArray();
        }

        $soals = Pertanyaan::whereIn('id', $soalIds)
            ->orderByRaw("FIELD(id," . implode(',', $soalIds) . ")")
            ->get();

        $total = $soals->count();
        $current = max(1, min($current, $total));
        $soal = $soals[$current - 1] ?? null;

        // 🔥 FIX DI SINI
        $waktuMulai = Carbon::parse($percobaan->waktu_mulai);
        $waktuSelesai = $waktuMulai->copy()->addMinutes((int) $ujian->waktu);

        if (now()->greaterThan($waktuSelesai)) {
            return $this->forceSelesai($percobaan);
        }

        $jawabanUser = Jawaban::where('percobaan_ujian_id', $percobaan->id)
            ->pluck('pilihan_jawaban', 'pertanyaan_id');

        return view('ujian.halaman-ujian', compact(
            'ujian',
            'soals',
            'soal',
            'current',
            'jawabanUser',
            'waktuSelesai'
        ));
    }

    public function start($ujianId)
    {
        $userId = Auth::id();
        $ujian = Ujian::findOrFail($ujianId);

        $existing = PercobaanUjian::where('user_id', $userId)
            ->where('ujian_id', $ujianId)
            ->where('status', 'sedang dikerjakan')
            ->latest()
            ->first();

        if ($existing) {

            $waktuMulai = Carbon::parse($existing->waktu_mulai);
            $waktuSelesai = $waktuMulai->copy()->addMinutes((int) $ujian->waktu);

            if (now()->greaterThan($waktuSelesai)) {
                return $this->forceSelesai($existing);
            }

            return redirect()->route('ujianstart.show', $ujianId);
        }

        $jumlahPercobaan = PercobaanUjian::where('user_id', $userId)
            ->where('ujian_id', $ujianId)
            ->count();

        if ($jumlahPercobaan >= $ujian->max_percobaan) {
            return redirect()->route('siswa.ujian')
                ->with('error', 'Percobaan habis');
        }

        $percobaan = PercobaanUjian::create([
            'user_id' => $userId,
            'ujian_id' => $ujianId,
            'percobaan_ke' => $jumlahPercobaan + 1,
            'waktu_mulai' => now(),
            'status' => 'sedang dikerjakan'
        ]);

        // 🔥 ACAK SOAL
        $soalIds = Pertanyaan::where('ujian_id', $ujianId)
            ->pluck('id')
            ->shuffle()
            ->toArray();

        session(['soal_ujian_'.$percobaan->id => $soalIds]);

        return redirect()->route('ujianstart.show', $ujianId);
    }

    public function save(Request $request, $ujianId)
    {
        $percobaan = PercobaanUjian::where('user_id', Auth::id())
            ->where('ujian_id', $ujianId)
            ->where('status', 'sedang dikerjakan')
            ->first();

        if (!$percobaan) {
            return response()->json(['error' => 'Percobaan tidak ditemukan'], 400);
        }

        $soal = Pertanyaan::find($request->soal_id);
        if (!$soal) {
            return response()->json(['error' => 'Soal tidak ditemukan'], 404);
        }

        $jawaban = strtoupper($request->jawaban);

        // 🔥 FIX UTAMA DI SINI
        $benar = strtoupper($jawaban) === strtoupper($soal->jawaban_benar);

        $data = Jawaban::where('percobaan_ujian_id', $percobaan->id)
            ->where('pertanyaan_id', $soal->id)
            ->first();

        if ($data) {
            $data->update([
                'pilihan_jawaban' => $jawaban,
                'benar' => $benar ? 1 : 0,
                'skor' => $benar ? 1 : 0
            ]);
        } else {
            Jawaban::create([
                'percobaan_ujian_id' => $percobaan->id,
                'pertanyaan_id' => $soal->id,
                'pilihan_jawaban' => $jawaban,
                'benar' => $benar ? 1 : 0,
                'skor' => $benar ? 1 : 0
            ]);
        }

        return response()->json(['status' => 'ok']);
    }

    public function selesai($ujianId)
    {
        $percobaan = PercobaanUjian::where('user_id', Auth::id())
            ->where('ujian_id', $ujianId)
            ->where('status', 'sedang dikerjakan')
            ->latest()
            ->first();

        if (!$percobaan) {
            return redirect()->route('siswa.ujian')
                ->with('error', 'Percobaan tidak ditemukan');
        }

        $jawaban = Jawaban::where('percobaan_ujian_id', $percobaan->id)->get();

        if ($jawaban->count() == 0) {
            return redirect()->back()->with('error', 'Belum ada jawaban');
        }

        $totalSoal = Pertanyaan::where('ujian_id', $ujianId)->count();

        // 🔥 FIX DI SINI
        $jawabanBenar = $jawaban->where('benar', 1)->count();

        $nilai = $totalSoal > 0
            ? round(($jawabanBenar / $totalSoal) * 100)
            : 0;

        $percobaan->update([
            'status' => 'selesai',
            'waktu_selesai' => now(),
            'skor' => $jawabanBenar,
            'nilai' => $nilai
        ]);

        return redirect()->route('ujian.hasil', $ujianId);
    }

public function hasil($ujianId)
{
    $percobaan = PercobaanUjian::where('user_id', Auth::id())
        ->where('ujian_id', $ujianId)
        ->latest()
        ->first();

    if (!$percobaan) {
        return redirect()->route('siswa.ujian')
            ->with('error', 'Belum ada hasil ujian');
    }

    $siswa = Siswa::where('user_id', Auth::id())->first();

    $jawaban = Jawaban::where('percobaan_ujian_id', $percobaan->id)->get();

    $totalSoal = Pertanyaan::where('ujian_id', $ujianId)->count();

    $jumlahJawaban = $jawaban->count(); // 🔥 FIX

    $jawabanBenar = $jawaban->where('benar', 1)->count();

    $nilai = $totalSoal > 0
        ? round(($jawabanBenar / $totalSoal) * 100)
        : 0;

    $lulus = $nilai >= 75;

    $jumlahPercobaan = PercobaanUjian::where('user_id', Auth::id())
        ->where('ujian_id', $ujianId)
        ->count();

    $ujian = Ujian::findOrFail($ujianId);
    $sisaPercobaan = $ujian->max_percobaan - $jumlahPercobaan;

    return view('ujian.hasil', compact(
        'siswa',
        'totalSoal',
        'jumlahJawaban', // 🔥 FIX
        'jawabanBenar',
        'nilai',
        'lulus',
        'sisaPercobaan'
    ));
}

}
