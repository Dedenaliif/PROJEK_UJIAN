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
    public function show(Request $request, $ujianId)
    {
        $ujian = Ujian::findOrFail($ujianId);

        $soals = Pertanyaan::where('ujian_id', $ujianId)->get();
        $total = $soals->count();

        $current = $request->get('no', 1);
        $current = max(1, min($current, $total));

        $soal = $soals[$current - 1] ?? null;

        $percobaan = PercobaanUjian::where('user_id', Auth::id())
            ->where('ujian_id', $ujianId)
            ->where('status', 'sedang dikerjakan')
            ->first();

        if (!$percobaan) {
            return redirect()->route('siswa.ujian')
                ->with('error', 'Silakan mulai ujian terlebih dahulu');
        }

        $waktuMulai = Carbon::parse($percobaan->waktu_mulai);
        $waktuSelesai = $waktuMulai->copy()->addMinutes($ujian->waktu);

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

        $jumlahPercobaan = PercobaanUjian::where('user_id', $userId)
            ->where('ujian_id', $ujianId)
            ->count();

        if ($jumlahPercobaan >= $ujian->max_percobaan) {
            return redirect()->route('siswa.ujian')
                ->with('error', 'Percobaan habis');
        }

        $existing = PercobaanUjian::where('user_id', $userId)
            ->where('ujian_id', $ujianId)
            ->where('status', 'sedang dikerjakan')
            ->first();

        if ($existing) {
            return redirect()->route('ujianstart.show', $ujianId);
        }

        PercobaanUjian::create([
            'user_id' => $userId,
            'ujian_id' => $ujianId,
            'percobaan_ke' => $jumlahPercobaan + 1,
            'waktu_mulai' => now(),
            'status' => 'sedang dikerjakan'
        ]);

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
        $benar = $jawaban === $soal->jawaban_benar;

        $totalSoal = Pertanyaan::where('ujian_id', $ujianId)->count();
        $skorPerSoal = 100 / $totalSoal;

        // 🔥 update manual biar pasti gak double
        $data = Jawaban::where('percobaan_ujian_id', $percobaan->id)
            ->where('pertanyaan_id', $soal->id)
            ->first();

        if ($data) {
            $data->update([
                'pilihan_jawaban' => $jawaban,
                'benar' => $benar,
                'skor' => $benar ? $skorPerSoal : 0
            ]);
        } else {
            Jawaban::create([
                'percobaan_ujian_id' => $percobaan->id,
                'pertanyaan_id' => $soal->id,
                'pilihan_jawaban' => $jawaban,
                'benar' => $benar,
                'skor' => $benar ? $skorPerSoal : 0
            ]);
        }

        return response()->json(['status' => 'ok']);
    }

    public function selesai($ujianId)
    {
        $userId = Auth::id();

        // 🔥 ambil percobaan aktif
        $percobaan = PercobaanUjian::where('user_id', $userId)
            ->where('ujian_id', $ujianId)
            ->where('status', 'sedang dikerjakan')
            ->latest()
            ->first();

        if (!$percobaan) {
            return redirect()->route('siswa.ujian')
                ->with('error', 'Percobaan tidak ditemukan');
        }

        // 🔥 ambil semua jawaban
        $jawaban = Jawaban::where('percobaan_ujian_id', $percobaan->id)->get();

        if ($jawaban->count() == 0) {
            return redirect()->back()->with('error', 'Belum ada jawaban');
        }

        // 🔥 hitung total skor
        $totalSkor = $jawaban->sum('skor');

        // 🔥 DEBUG WAJIB COBA SEKALI
        // dd($jawaban->toArray(), $totalSkor);

        // 🔥 UPDATE KE DB (INI YANG PENTING)
        $percobaan->update([
            'status' => 'selesai',
            'waktu_selesai' => now(),
            'skor' => $totalSkor,      // 🔥 isi juga skor
            'nilai' => $totalSkor      // 🔥 ini yang dipakai di hasil
        ]);

        return redirect()->route('ujian.hasil', $ujianId);
    }

   public function hasil($ujianId)
    {
        $userId = Auth::id();

        // 🔥 AMBIL YANG SUDAH SELESAI (INI KUNCI FIX)
        $percobaan = PercobaanUjian::where('user_id', $userId)
            ->where('ujian_id', $ujianId)
            ->where('status', 'selesai')
            ->latest()
            ->first();

        if (!$percobaan) {
            return redirect()->route('siswa.ujian')
                ->with('error', 'Belum ada hasil ujian');
        }

        $siswa = Siswa::where('user_id', $userId)->first();

        $totalSoal = Pertanyaan::where('ujian_id', $ujianId)->count();

        $jumlahJawaban = Jawaban::where('percobaan_ujian_id', $percobaan->id)->count();

        // 🔥 NILAI DIAMBIL DARI PERCOBAAN YANG BENAR
        $nilai = $percobaan->skor ?? 0;

        $lulus = $nilai >= 75;

        $jumlahPercobaan = PercobaanUjian::where('user_id', $userId)
            ->where('ujian_id', $ujianId)
            ->count();

        $ujian = Ujian::findOrFail($ujianId);

        $sisaPercobaan = $ujian->max_percobaan - $jumlahPercobaan;

        return view('ujian.hasil', compact(
            'siswa',
            'totalSoal',
            'jumlahJawaban',
            'nilai',
            'lulus',
            'sisaPercobaan'
        ));
    }
}
