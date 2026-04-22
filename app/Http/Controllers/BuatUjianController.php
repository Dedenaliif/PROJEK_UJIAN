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

    public function report($ujianId)
    {
        $ujian = \App\Models\Ujian::findOrFail($ujianId);

        $data = \App\Models\PercobaanUjian::with(['user.siswa'])
            ->where('ujian_id', $ujianId)
            ->get();

        $query = \App\Models\PercobaanUjian::with([
            'user.siswa.kelas',
            'user.siswa.jurusan'
        ])
            ->where('ujian_id', $ujianId);

        // 🔥 FILTER STATUS
        if (request('status') == 'lulus') {
            $query->where('skor', '>=', 75);
        } elseif (request('status') == 'remedial') {
            $query->where('skor', '<', 75);
        }

        $data = $query->get();
        // 🔥 Statistik
        $totalSiswa = $data->count();
        $lulus = $data->where('skor', '>=', 75)->count();
        $remedial = $data->where('skor', '<', 75)->count();
        $rata = $data->avg('skor');

        return view('ujian.halaman-report', compact(
            'ujian',
            'data',
            'totalSiswa',
            'lulus',
            'remedial',
            'rata'
        ));
    }
    public function exportCsv($ujianId)
    {
        $ujian = \App\Models\Ujian::findOrFail($ujianId);

        $data = \App\Models\PercobaanUjian::with([
            'user.siswa.kelas',
            'user.siswa.jurusan'
        ])
            ->where('ujian_id', $ujianId)
            ->get()
            ->sortByDesc('nilai') // 🔥 optional: ranking
            ->values();

        $filename = "report-" . str_replace(' ', '-', $ujian->judul) . ".csv";

        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');

            // 🔥 FIX UTF-8 (biar Excel normal)
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // HEADER
            fputcsv($file, [
                'No',
                'NIS',
                'Nama',
                'Kelas',
                'Jurusan',
                'Nilai',
                'Status'
            ], ';');

            foreach ($data as $i => $item) {
                fputcsv($file, [
                    $i + 1,
                    $item->user->siswa->nis ?? '-',
                    $item->user->username ?? '-',
                    $item->user->siswa->kelas->nama_kelas ?? '-',
                    $item->user->siswa->jurusan->nama_jurusan ?? '-',
                    $item->skor ?? 0,
                    $item->skor >= 75 ? 'Lulus' : 'Remedial',
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
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
