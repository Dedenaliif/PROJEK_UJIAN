<?php

namespace App\Http\Controllers;

use App\Models\Ujian;
use Illuminate\Support\Facades\Auth;
use App\Models\PercobaanUjian;
use Illuminate\Support\Carbon;
use App\Models\Jawaban;
use App\Models\Kelas;
use App\Models\Pertanyaan;

class BuatUjianController extends Controller
{
   private function hitungNilai($percobaan)
    {
        $jawaban = Jawaban::where('percobaan_ujian_id', $percobaan->id)->get();

        $totalSoal = Pertanyaan::where('ujian_id', $percobaan->ujian_id)->count();

        $benar = $jawaban->where('benar', 1)->count();

        $nilai = $totalSoal > 0
            ? round(($benar / $totalSoal) * 100)
            : 0;

        return [
            'skor' => $benar,   // jumlah benar
            'nilai' => $nilai   // persen
        ];
    }

    public function checkStatus()
    {
        if (Auth::user()->role !== 'siswa') {
            return response()->json(['redirect' => null]);
        }

        $aktif = PercobaanUjian::where('user_id', Auth::id())
            ->where('status', 'sedang dikerjakan')
            ->get();

        foreach ($aktif as $p) {

            $ujian = Ujian::find($p->ujian_id);
            if (!$ujian) continue;

            $end = Carbon::parse($p->waktu_mulai)
                ->addMinutes((int) $ujian->waktu);

            if (now()->greaterThan($end)) {

                $hasil = $this->hitungNilai($p);

                $p->update([
                    'status' => 'selesai',
                    'waktu_selesai' => now(),
                    'skor' => $hasil['nilai'],
                    'nilai' => $hasil['nilai']
                ]);

                return response()->json([
                    'redirect' => route('ujian.hasil', $p->ujian_id)
                ]);
            }
        }

        return response()->json(['redirect' => null]);
    }

    // ==============================
    // 🔥 HALAMAN LIST UJIAN
    // ==============================
    public function index()
    {
        if (Auth::user()->role === 'siswa') {

            $aktif = PercobaanUjian::where('user_id', Auth::id())
                ->where('status', 'sedang dikerjakan')
                ->get();

            foreach ($aktif as $p) {

                $ujian = Ujian::find($p->ujian_id);
                if (!$ujian) continue;

                $end = Carbon::parse($p->waktu_mulai)
                    ->addMinutes((int) $ujian->waktu);

                if (now()->greaterThan($end)) {

                    $hasil = $this->hitungNilai($p);

                    $p->update([
                        'status' => 'selesai',
                        'waktu_selesai' => now(),
                        'skor' => $hasil['nilai'],
                        'nilai' => $hasil['nilai']
                    ]);

                    return redirect()->route('ujian.hasil', $p->ujian_id);
                }
            }
        }

        $ujians = Ujian::withCount([
            'percobaanUjians as jumlah_percobaan' => function ($q) {
                $q->where('user_id', Auth::id());
            }
        ])->get();

        return view('ujian.index', compact('ujians'));
    }

    // ==============================
    // 🔥 REPORT
    // ==============================
    public function report($ujianId)
    {
        $ujian = Ujian::findOrFail($ujianId);

        $listKelas = Kelas::all();
        $listJurusan = \App\Models\Jurusan::all();

        $data = PercobaanUjian::with([
            'user.siswa.kelas',
            'user.siswa.jurusan'
        ])
        ->where('ujian_id', $ujianId)
        ->where('status', 'selesai') // 🔥 PENTING
        ->get()
        ->map(function ($item) {

            // 🔥 HITUNG ULANG BIAR 100% AKURAT
            $hasil = $this->hitungNilai($item);

            $item->skor = $hasil['skor'];
            $item->nilai = $hasil['nilai'];

            return $item;
        });

        // FILTER
        if (request('status') == 'lulus') {
            $data = $data->where('nilai', '>=', 75);
        } elseif (request('status') == 'remedial') {
            $data = $data->where('nilai', '<', 75);
        }

        if (request('kelas_id')) {
            $data = $data->filter(fn($d) =>
                optional($d->user->siswa)->kelas_id == request('kelas_id')
            );
        }

        if (request('jurusan_id')) {
            $data = $data->filter(fn($d) =>
                optional($d->user->siswa)->jurusan_id == request('jurusan_id')
            );
        }

        // STATISTIK
        $totalSiswa = $data->count();
        $lulus = $data->where('nilai', '>=', 75)->count();
        $remedial = $data->where('nilai', '<', 75)->count();
        $rata = round($data->avg('nilai') ?? 0, 2);

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

    // ==============================
    // 🔥 EXPORT CSV
    // ==============================
    public function exportCsv($ujianId)
    {
        $ujian = Ujian::findOrFail($ujianId);

        $query = PercobaanUjian::with([
            'user.siswa.kelas',
            'user.siswa.jurusan'
        ])->where('ujian_id', $ujianId);

        if (request('status') == 'lulus') {
            $query->where('nilai', '>=', 75);
        } elseif (request('status') == 'remedial') {
            $query->where('nilai', '<', 75);
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

        $data = $query->orderByDesc('nilai')->get();

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
                    $item->nilai ?? 0,
                    $item->nilai >= 75 ? 'Lulus' : 'Remedial',
                ], ';');
            }

            fclose($file);

        }, 200, [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=\"$filename\"",
        ]);
    }

    // ==============================
    // 🔥 CREATE & STORE
    // ==============================
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
            'waktu' => 'required|integer',
            'max_percobaan' => 'required|integer',
            'waktu_mulai' => 'nullable|date',
            'waktu_selesai' => 'nullable|date',
        ]);

        Ujian::create($validatedData);

        return redirect()->route('ujian.index')
            ->with('success', 'Ujian berhasil dibuat!');
    }
}
