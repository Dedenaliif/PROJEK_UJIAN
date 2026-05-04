<?php

namespace App\Http\Controllers;

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
        // 🔥 HANYA SISWA
        if (Auth::user()->role !== 'siswa') {
            return response()->json(['redirect' => null]);
        }

        $userId = Auth::id();

        $aktif = PercobaanUjian::where('user_id', $userId)
            ->where('status', 'sedang dikerjakan')
            ->get();

        foreach ($aktif as $p) {

            $ujian = Ujian::find($p->ujian_id);

            if (!$ujian) continue;

            // 🔥 FIX WAJIB
            $waktuMulai = Carbon::parse($p->waktu_mulai);
            $waktuSelesai = $waktuMulai->copy()->addMinutes((int) $ujian->waktu);

            if (now()->greaterThan($waktuSelesai)) {

                $totalSkor = Jawaban::where('percobaan_ujian_id', $p->id)
                    ->sum('skor');

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
        // 🔥 JANGAN CEK STATUS UNTUK NON SISWA
        if (Auth::user()->role === 'siswa') {

            $userId = Auth::id();

            $aktif = PercobaanUjian::where('user_id', $userId)
                ->where('status', 'sedang dikerjakan')
                ->get();

            foreach ($aktif as $p) {

                $ujian = Ujian::find($p->ujian_id);
                if (!$ujian) continue;

                $waktuMulai = Carbon::parse($p->waktu_mulai);
                $waktuSelesai = $waktuMulai->copy()->addMinutes((int) $ujian->waktu);

                if (now()->greaterThan($waktuSelesai)) {

                    $totalSkor = Jawaban::where('percobaan_ujian_id', $p->id)
                        ->sum('skor');

                    $p->update([
                        'status' => 'selesai',
                        'waktu_selesai' => now(),
                        'skor' => $totalSkor,
                        'nilai' => $totalSkor
                    ]);

                    return redirect()->route('ujian.hasil', $p->ujian_id);
                }
            }
        }

        // 🔥 QUERY OPTIMAL
        $ujians = Ujian::withCount([
            'percobaanUjians as jumlah_percobaan' => function ($q) {
                $q->where('user_id', Auth::id());
            }
        ])->get();

        return view('ujian.index', compact('ujians'));
    }

    public function report($ujianId)
    {
        $ujian = Ujian::findOrFail($ujianId);

        $listKelas = Kelas::all();
        $listJurusan = \App\Models\Jurusan::all();

        $query = PercobaanUjian::with([
            'user.siswa.kelas',
            'user.siswa.jurusan'
        ])->where('ujian_id', $ujianId);

        // 🔥 FILTER
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

        $data = $query->get();

        // 🔥 STATISTIK
        $totalSiswa = $data->count();
        $lulus = $data->where('skor', '>=', 75)->count();
        $remedial = $data->where('skor', '<', 75)->count();
        $rata = round($data->avg('skor') ?? 0, 2);

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
        $ujian = Ujian::findOrFail($ujianId);

        $query = PercobaanUjian::with([
            'user.siswa.kelas',
            'user.siswa.jurusan'
        ])->where('ujian_id', $ujianId);

        // 🔥 FILTER
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

        $data = $query->orderByDesc('skor')->get();

        $filename = "Laporan-" . str_replace(' ', '-', $ujian->judul) . "-" . date('Ymd-Hi') . ".csv";

        return response()->stream(function () use ($data) {

            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, [
                'No','NIS','Nama','Kelas','Jurusan','Nilai','Status'
            ], ';');

            foreach ($data as $i => $item) {
                fputcsv($file, [
                    $i + 1,
                    $item->user->siswa->nis ?? '-',
                    $item->user->siswa->nama_siswa ?? '-',
                    $item->user->siswa->kelas->nama_kelas ?? '-',
                    $item->user->siswa->jurusan->nama_jurusan ?? '-',
                    $item->skor ?? 0,
                    $item->skor >= 75 ? 'Lulus' : 'Remedial',
                ], ';');
            }

            fclose($file);

        }, 200, [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=\"$filename\"",
        ]);
    }

    public function create()
    {
        return view('ujian.create');
    }

    public function store()
    {
        $validatedData = request()->validate([
            'judul' => 'required',
            'tipe' => 'required',
            'deskripsi' => 'nullable',
            'waktu' => 'required|integer', // 🔥 FIX
            'max_percobaan' => 'required|integer',
            'waktu_mulai' => 'nullable|date',
            'waktu_selesai' => 'nullable|date',
        ]);

        Ujian::create($validatedData);

        return redirect()->route('ujian.create')
            ->with('success', 'Ujian berhasil dibuat!');
    }
}
