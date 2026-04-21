<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;

use App\Models\Ujian;
use Illuminate\Support\Facades\Auth;
use App\Models\PercobaanUjian;
use Illuminate\Support\Carbon;
use App\Models\Jawaban;

class BuatUjianController extends Controller
{
    public function checkStatus()
    {
        $userId = Auth::id();

        $aktif = PercobaanUjian::where('user_id', $userId)
            ->where('status', 'sedang dikerjakan')
            ->get();

        foreach ($aktif as $p) {

            $ujian = Ujian::find($p->ujian_id);

            $waktuMulai = Carbon::parse($p->waktu_mulai);
            $waktuSelesai = $waktuMulai->copy()->addMinutes($ujian->waktu);

            if (now()->greaterThan($waktuSelesai)) {

                $jawaban = Jawaban::where('percobaan_ujian_id', $p->id)->get();
                $totalSkor = $jawaban->sum('skor');

                $p->update([
                    'status' => 'selesai',
                    'waktu_selesai' => now(),
                    'skor' => $totalSkor,
                    'nilai' => $totalSkor
                ]);

                return response()->json([
                    'redirect' => route('ujian.hasil', $p->ujian_id)
                ]);
            }
        }

        return response()->json(['redirect' => null]);
    }

    public function index()
    {
        $userId = Auth::id();

        // 🔥 CEK UJIAN AKTIF
        $aktif = PercobaanUjian::where('user_id', $userId)
            ->where('status', 'sedang dikerjakan')
            ->get();

        $redirectHasil = null;

        foreach ($aktif as $p) {

            $ujian = Ujian::find($p->ujian_id);

            $waktuMulai = Carbon::parse($p->waktu_mulai);
            $waktuSelesai = $waktuMulai->copy()->addMinutes($ujian->waktu);

            if (now()->greaterThan($waktuSelesai)) {

                $jawaban = Jawaban::where('percobaan_ujian_id', $p->id)->get();
                $totalSkor = $jawaban->sum('skor');

                $p->update([
                    'status' => 'selesai',
                    'waktu_selesai' => now(),
                    'skor' => $totalSkor,
                    'nilai' => $totalSkor
                ]);

                $redirectHasil = $p->ujian_id;
            }
        }

        // 🔥 REDIRECT KE HASIL JIKA ADA YANG EXPIRED
        if ($redirectHasil) {
            return redirect()->route('ujian.hasil', $redirectHasil);
        }

        // 🔥 LOAD DATA UJIAN NORMAL
        $ujians = Ujian::withCount([
            'percobaanUjians as jumlah_percobaan' => function ($q) use ($userId) {
                $q->where('user_id', $userId);
            }
        ])->get();

        return view('ujian.index', compact('ujians'));
    }



    public function create()
    {
        return view('ujian.create');
    }



    public function store()
    {

        // Validasi data yang diterima dari form
        $validatedData = request()->validate([
            'judul' => 'required',
            'tipe' => 'required',
            'deskripsi' => 'nullable',
            'waktu' => 'required',
            'max_percobaan' => 'required',
            'waktu_mulai' => 'nullable|date',
            'waktu_selesai' => 'nullable|date',
        ]);

        // Simpan data ujian ke database
        $ujian = Ujian::create($validatedData);

        // Redirect ke halaman daftar ujian atau halaman detail ujian
        return redirect()->route('ujian.create')->with('success', 'Ujian berhasil dibuat!');
    }
}
