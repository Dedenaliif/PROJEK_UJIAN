<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;

use App\Models\Ujian;
use Illuminate\Support\Facades\Auth;
use App\Models\PercobaanUjian;
use Illuminate\Support\Carbon;
use App\Models\Jawaban;
use App\Models\Kelas;

class BuatUjianController extends Controller
{
    public function checkStatus()
    {
        if (Auth::user()->role !== 'siswa') {
            return response()->json(['redirect' => null]);
        }

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

        if ($redirectHasil) {
            return redirect()->route('ujian.hasil', $redirectHasil);
        }

        $ujians = Ujian::withCount([
            'percobaanUjians as jumlah_percobaan' => function ($q) use ($userId) {
                $q->where('user_id', $userId);
            }
        ])->get();

        return view('ujian.index', compact('ujians'));
    }

    public function report($ujianId)
    {
        $ujian = Ujian::findOrFail($ujianId);

        // Ambil data untuk dropdown filter
        $listKelas = Kelas::all();
        $listJurusan = \App\Models\Jurusan::all(); // Tambahkan ini

        $query = PercobaanUjian::with([
            'user.siswa.kelas',
            'user.siswa.jurusan'
        ])
            ->where('ujian_id', $ujianId);

        // FILTER STATUS
        if (request('status') == 'lulus') {
            $query->where('skor', '>=', 75);
        } elseif (request('status') == 'remedial') {
            $query->where('skor', '<', 75);
        }

        // FILTER KELAS
        if (request('kelas_id')) {
            $query->whereHas('user.siswa', function ($q) {
                $q->where('kelas_id', request('kelas_id'));
            });
        }

        // FILTER JURUSAN (BARU)
        if (request('jurusan_id')) {
            $query->whereHas('user.siswa', function ($q) {
                $q->where('jurusan_id', request('jurusan_id'));
            });
        }

        $data = $query->get();

        // Statistik otomatis mengikuti data yang terfilter
        $totalSiswa = $data->count();
        $lulus = $data->where('skor', '>=', 75)->count();
        $remedial = $data->where('skor', '<', 75)->count();
        $rata = $data->avg('skor') ?? 0;

        return view('ujian.halaman-report', compact(
            'ujian',
            'data',
            'totalSiswa',
            'lulus',
            'remedial',
            'rata',
            'listKelas',
            'listJurusan'
        ));
    }
    public function exportCsv($ujianId)
    {
        $ujian = \App\Models\Ujian::findOrFail($ujianId);

        // Membangun query dengan filter yang sama dengan halaman report
        $query = \App\Models\PercobaanUjian::with([
            'user.siswa.kelas',
            'user.siswa.jurusan'
        ])
            ->where('ujian_id', $ujianId);

        // Logic Filter yang sama dengan method report
        if (request('status') == 'lulus') {
            $query->where('skor', '>=', 75);
        } elseif (request('status') == 'remedial') {
            $query->where('skor', '<', 75);
        }

        if (request('kelas_id')) {
            $query->whereHas('user.siswa', function ($q) {
                $q->where('kelas_id', request('kelas_id'));
            });
        }

        if (request('jurusan_id')) {
            $query->whereHas('user.siswa', function ($q) {
                $q->where('jurusan_id', request('jurusan_id'));
            });
        }

        // Ambil data dan urutkan berdasarkan nilai tertinggi
        $data = $query->orderByDesc('skor')->get();

        // Penamaan file yang dinamis
        $filename = "Laporan-" . str_replace(' ', '-', $ujian->judul) . "-" . date('Ymd-Hi') . ".csv";

        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');

            // Menambahkan BOM untuk kompatibilitas Excel (biar tidak berantakan)
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header CSV
            fputcsv($file, [
                'No',
                'NIS',
                'Nama Siswa',
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
