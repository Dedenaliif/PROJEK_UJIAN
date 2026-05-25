<?php

namespace App\Http\Controllers;

use App\Models\Ujian;
use Illuminate\Support\Facades\Auth;
use App\Models\PercobaanUjian;
use Illuminate\Support\Carbon;
use App\Models\Jawaban;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\Pertanyaan;
use App\Models\UjianSiswaSesi;
use App\Models\Sesi;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $userId = Auth::id();
        $bisaDownloadSertifikat = false;

        // Pastikan user adalah siswa sebelum mengecek sertifikat
        if (Auth::user()->role == 'siswa') {

            // 1. Cek Kelulusan Ujian Word (Murni >= 75 ATAU Skor Final IS NOT NULL)
            $lulusWord = DB::table('percobaan_ujians')
                ->join('ujians', 'percobaan_ujians.ujian_id', '=', 'ujians.id')
                ->where('percobaan_ujians.user_id', $userId)
                ->where('percobaan_ujians.status', 'selesai') // Pastikan ujian sudah selesai
                ->where(function ($query) {
                    $query->where('ujians.tipe', 'word')
                        ->orWhere('ujians.judul', 'like', '%Word%');
                })
                ->where(function ($query) {
                    $query->where('percobaan_ujians.skor', '>=', 75)
                        ->orWhereNotNull('percobaan_ujians.skor_final'); // Kolom skor_final asal terisi langsung lulus
                })
                ->exists();

            // 2. Cek Kelulusan Ujian Excel (Murni >= 75 ATAU Skor Final IS NOT NULL)
            $lulusExcel = DB::table('percobaan_ujians')
                ->join('ujians', 'percobaan_ujians.ujian_id', '=', 'ujians.id')
                ->where('percobaan_ujians.user_id', $userId)
                ->where('percobaan_ujians.status', 'selesai') // Pastikan ujian sudah selesai
                ->where(function ($query) {
                    $query->where('ujians.tipe', 'excel')
                        ->orWhere('ujians.judul', 'like', '%Excel%');
                })
                ->where(function ($query) {
                    $query->where('percobaan_ujians.skor', '>=', 75)
                        ->orWhereNotNull('percobaan_ujians.skor_final'); // Kolom skor_final asal terisi langsung lulus
                })
                ->exists();

            // Tombol unlock HANYA JIKA kedua ujian lulus/terpenuhi nilainya
            $bisaDownloadSertifikat = $lulusWord && $lulusExcel;
        }
        $kelas = Kelas::all();
        $jurusan = Jurusan::all();
        // cek ujian aktif siswa
        if (Auth::user()->role === 'siswa') {

            $aktif = PercobaanUjian::where('user_id', Auth::id())
                ->where('status', 'sedang dikerjakan')
                ->get();

            foreach ($aktif as $p) {

                $ujian = Ujian::find($p->ujian_id);

                if (!$ujian) continue;

                $end = Carbon::parse($p->waktu_mulai)
                    ->addMinutes((int)$ujian->waktu);

                if (now()->greaterThan($end)) {

                    $hasil = $this->hitungNilai($p);

                    $p->update([
                        'status' => 'selesai',
                        'waktu_selesai' => now(),
                        'skor' => $hasil['nilai'],
                        'nilai' => $hasil['nilai']
                    ]);

                    return redirect()
                        ->route('ujian.hasil', $p->ujian_id);
                }
            }
        }

        // ambil semua ujian
        $ujians = Ujian::with([
            'pertanyaans',
            'sesiSiswa'
        ])
            ->withCount([
                'percobaanUjians as jumlah_percobaan' => function ($q) {
                    $q->where('user_id', Auth::id());
                }
            ]);

        if (request()->filled('sesi_id')) {
            $ujians->whereHas('sesiSiswa', function ($q) {
                $q->where('sesi_id', request('sesi_id'));
            });
        }

        $ujians = $ujians->get();

        // data sesi
        $sesis = Sesi::all();

        // semua siswa (untuk modal penguji)
        $siswas = Siswa::all();

        $sesiSaya = [];

        if (
            Auth::user()->role == 'siswa'
            && Auth::user()->siswa
        ) {

            $data = UjianSiswaSesi::with('sesi')
                ->where('siswa_id', Auth::user()->siswa->id)
                ->get();

            foreach ($data as $item) {
                $sesiSaya[$item->ujian_id] = $item->sesi;
            }
        }

        return view('ujian.index', compact(
            'ujians',
            'sesis',
            'siswas',
            'sesiSaya',
            'kelas',
            'jurusan',
            'bisaDownloadSertifikat',
        ));
    }

    // ==============================
    // 🔥 REPORT
    // ==============================
    public function report(Request $request, $ujianId)
    {
        $ujian = Ujian::findOrFail($ujianId);

        $listKelas = Kelas::all();
        $listJurusan = Jurusan::all();
        $listSesi = Sesi::all();

        $query = PercobaanUjian::with([
            'user.siswa.kelas',
            'user.siswa.jurusan'
        ])
            ->where('ujian_id', $ujianId)
            ->where('status', 'selesai');

        /*
        ==========================
        FILTER SESI
        ==========================
        */
        if ($request->filled('sesi_id')) {

            $query->whereHas('user.siswa', function ($q) use ($request, $ujianId) {

                $q->whereHas('ujianSesi', function ($qq) use ($request, $ujianId) {
                    $qq->where('ujian_id', $ujianId)
                        ->where('sesi_id', $request->sesi_id);
                });
            });
        }

        $data = $query->get()

            ->map(function ($item) {

                $hasil = $this->hitungNilai($item);

                $item->skor = $hasil['skor'];
                $item->nilai = $hasil['nilai'];

                return $item;
            })

            ->groupBy('user_id')

            ->map(function ($items) {

                $sorted = $items->sortByDesc('nilai');

                $best = $sorted->first();

                $percobaanKe = $items
                    ->sortBy('created_at')
                    ->values()
                    ->search(fn($x) => $x->id === $best->id);

                $best->percobaan_terbaik = $percobaanKe + 1;
                $best->total_percobaan = $items->count();

                $best->riwayat_percobaan = $items
                    ->sortBy('created_at')
                    ->values()
                    ->map(function ($x, $index) {

                        return [
                            'percobaan' => $index + 1,
                            'nilai' => $x->nilai,
                            'status' => $x->nilai >= 75
                                ? 'LULUS'
                                : 'REMEDIAL'
                        ];
                    });

                return $best;
            })

            ->values();

        /*
        ==========================
        FILTER STATUS
        ==========================
        */
        if ($request->status == 'lulus') {
            $data = $data->where('nilai', '>=', 75);
        }

        if ($request->status == 'remedial') {
            $data = $data->where('nilai', '<', 75);
        }

        /*
        ==========================
        FILTER KELAS
        ==========================
        */
        if ($request->kelas_id) {
            $data = $data->filter(
                fn($d) =>
                optional($d->user->siswa)->kelas_id == $request->kelas_id
            );
        }

        /*
        ==========================
        FILTER JURUSAN
        ==========================
        */
        if ($request->jurusan_id) {
            $data = $data->filter(
                fn($d) =>
                optional($d->user->siswa)->jurusan_id == $request->jurusan_id
            );
        }

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
            'listJurusan',
            'listSesi'
        ));
    }

    // ==============================
    // 🔥 EXPORT CSV
    // ==============================
    public function exportCsv(Request $request, $ujianId)
    {
        $ujian = Ujian::findOrFail($ujianId);

        $query = PercobaanUjian::with([
            'user.siswa.kelas',
            'user.siswa.jurusan'
        ])->where('ujian_id', $ujianId);

        if ($request->filled('kelas_id')) {
            $query->whereHas('user.siswa', function ($q) {
                $q->where('kelas_id', request('kelas_id'));
            });
        }

        if ($request->filled('jurusan_id')) {
            $query->whereHas('user.siswa', function ($q) {
                $q->where('jurusan_id', request('jurusan_id'));
            });
        }

        $rawPercobaan = $query->get()->map(function ($item) {
            $hasil = $this->hitungNilai($item);
            $item->nilai = $hasil['nilai'] ?? 0;
            return $item;
        });

        $grouped = $rawPercobaan->groupBy('user_id');

        $dataList = collect();
        $maxPercobaan = 0;

        foreach ($grouped as $userId => $percobaans) {

            $sorted = $percobaans->sortBy('created_at')->values();

            $nilaiTertinggi = $sorted->max(function ($p) {
                return $p->skor_final ?? $p->nilai;
            });

            if (request('status') == 'lulus' && $nilaiTertinggi < 75) continue;
            if (request('status') == 'remedial' && $nilaiTertinggi >= 75) continue;

            $siswa = $sorted->first()->user->siswa ?? null;
            if (!$siswa) continue;

            $maxPercobaan = max($maxPercobaan, $sorted->count());

            $status = $sorted->count() == 0
                ? 'BELUM UJIAN'
                : ($nilaiTertinggi >= 75 ? 'LULUS' : 'REMEDIAL');

            $dataList->push([
                'jurusan' => $siswa->jurusan->nama_jurusan ?? '-',
                'kelas' => $siswa->kelas->nama_kelas ?? '-',
                'nis' => $siswa->nis,
                'nama' => $siswa->nama_siswa,
                'nilai' => $sorted->map(fn($x) => $x->skor_final ?? $x->nilai)->toArray(),
                'terbaik' => $nilaiTertinggi,
                'status' => $status
            ]);
        }

        $dataList = $dataList->sortBy('nama');

        $filename = 'Laporan-' . $ujian->judul . '-' . now()->format('YmdHi') . '.xls';

        return response()->stream(function () use ($dataList, $maxPercobaan, $ujian) {

            echo '
            <html><head><meta charset="UTF-8">
            <style>
                table{border-collapse:collapse;width:100%;font-family:Arial;}
                th{background:#4472C4;color:white;border:1px solid black;padding:8px;}
                td{border:1px solid black;padding:6px;text-align:center;}
                .nama{text-align:left;}
                .title{font-size:18px;font-weight:bold;text-align:center;padding:14px;}
                .lulus{color:green;font-weight:bold;}
                .remedial{color:red;font-weight:bold;}
                .belum{color:orange;font-weight:bold;}
            </style></head><body><table>';

            echo '<tr><td colspan="' . (6 + $maxPercobaan) . '" class="title">
            LAPORAN UJIAN ' . $ujian->judul . '
            </td></tr>';

            echo '<tr>
                <th>No</th>
                <th>Jurusan</th>
                <th>Kelas</th>
                <th>NIS</th>
                <th>Nama</th>';

            for ($i = 1; $i <= $maxPercobaan; $i++) {
                echo "<th>Percobaan $i</th>";
            }

            echo '<th>Nilai Terbaik</th><th>Status</th></tr>';

            $no = 1;

            foreach ($dataList as $d) {

                echo '<tr>';
                echo '<td>' . $no++ . '</td>';
                echo '<td>' . $d['jurusan'] . '</td>';
                echo '<td>' . $d['kelas'] . '</td>';
                echo '<td>' . $d['nis'] . '</td>';
                echo '<td class="nama">' . $d['nama'] . '</td>';

                for ($i = 0; $i < $maxPercobaan; $i++) {
                    echo '<td>' . ($d['nilai'][$i] ?? '-') . '</td>';
                }

                $class = strtolower($d['status']) == 'lulus'
                    ? 'lulus'
                    : (strtolower($d['status']) == 'remedial'
                        ? 'remedial'
                        : 'belum');

                echo '<td>' . $d['terbaik'] . '</td>';
                echo '<td class="' . $class . '">' . $d['status'] . '</td>';
                echo '</tr>';
            }

            echo '</table></body></html>';
        }, 200, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => "attachment; filename=\"$filename\""
        ]);
    }

    // ==============================
    // 🔥 CREATE & STORE
    // ==============================
    public function create()
    {
        return view('ujian.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
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


    public function simpanSesi(Request $request)
    {
        $request->validate([
            'ujian_id' => 'required',
            'sesi_id' => 'required'
        ]);

        // hapus data lama sesi ini
        UjianSiswaSesi::where('ujian_id', $request->ujian_id)
            ->where('sesi_id', $request->sesi_id)
            ->delete();

        // insert ulang checkbox terpilih
        if ($request->has('siswa')) {

            foreach ($request->siswa as $siswaId) {

                UjianSiswaSesi::create([
                    'ujian_id' => $request->ujian_id,
                    'sesi_id' => $request->sesi_id,
                    'siswa_id' => $siswaId
                ]);
            }
        }

        return back()->with('success', 'Sesi berhasil disimpan');
    }

    public function exportSemuaNilai(Request $request)
    {
        $query = Siswa::with([
            'kelas',
            'jurusan'
        ]);

        if ($request->kelas_id) {
            $query->where('kelas_id', $request->kelas_id);
        }

        if ($request->jurusan_id) {
            $query->where('jurusan_id', $request->jurusan_id);
        }

        /*
        =============================
        FILTER SESI
        =============================
        */
        if ($request->sesi_id) {
            $query->whereHas('ujianSesi', function ($q) use ($request) {
                $q->where('sesi_id', $request->sesi_id);
            });
        }

        $siswas = $query->get()
            ->unique('user_id')
            ->sortBy(fn($s) => strtoupper(trim($s->nama_siswa)))
            ->values();

        // Mengambil data percobaan ujian yang sudah selesai
        $percobaanAll = PercobaanUjian::with('ujian')
            ->whereIn('user_id', $siswas->pluck('user_id'))
            ->where('status', 'selesai') // Filter agar hanya mengambil yang sudah selesai
            ->orderBy('percobaan_ke')
            ->get()
            ->groupBy('user_id');

        $filename = "Laporan-Semua-Nilai-" . now()->format('Ymd-His') . ".xls";

        return response()->stream(function () use ($siswas, $percobaanAll) {

            echo '
            <html>
            <head>
            <meta charset="UTF-8">
            <style>
                table{
                    border-collapse:collapse;
                    width:100%;
                    font-family:Arial;
                }

                th{
                    background:#4472C4;
                    color:#fff;
                    border:1px solid #000;
                    padding:8px;
                    text-align:center;
                }

                td{
                    border:1px solid #000;
                    padding:6px;
                    text-align:center;
                }

                .nama{text-align:left;}
                .title{
                    font-size:18px;
                    font-weight:bold;
                    text-align:center;
                    padding:14px;
                    background:#D9E2F3;
                }

                .lulus{color:green;font-weight:bold;}
                .remedial{color:red;font-weight:bold;}
                .belum{color:gray;font-weight:bold;}
            </style>
            </head>
            <body>
            <table>';

            echo '
            <tr>
                <td colspan="16" class="title">
                    LAPORAN NILAI SEMUA SISWA
                </td>
            </tr>';

            echo '
            <tr>
                <th>No</th>
                <th>Sesi</th>
                <th>Kelas</th>
                <th>Jurusan</th>
                <th>NIS</th>
                <th>Nama</th>
                <th>Word 1</th>
                <th>Word 2</th>
                <th>Word 3</th>
                <th>Markup Word</th>
                <th>Status Word</th>
                <th>Excel 1</th>
                <th>Excel 2</th>
                <th>Excel 3</th>
                <th>Markup Excel</th>
                <th>Status Excel</th>
            </tr>';

            $no = 1;

            foreach ($siswas as $siswa) {

                $percobaan = $percobaanAll[$siswa->user_id] ?? collect();

                $word = $percobaan
                    ->filter(fn($p) => optional($p->ujian)->tipe === 'word')
                    ->values();

                $excel = $percobaan
                    ->filter(fn($p) => optional($p->ujian)->tipe === 'excel')
                    ->values();

                $sesi = optional($siswa->ujianSesi->first()?->sesi)->no_sesi ?? '-';

                echo '<tr>';

                echo '<td>' . $no++ . '</td>';
                echo '<td>' . $sesi . '</td>';
                echo '<td>' . optional($siswa->kelas)->nama_kelas . '</td>';
                echo '<td>' . optional($siswa->jurusan)->nama_jurusan . '</td>';
                echo '<td>' . $siswa->nis . '</td>';
                echo '<td class="nama">' . $siswa->nama_siswa . '</td>';

                /*
                =================================================================
                LOGIKA BARU - WORD
                =================================================================
                */
                $nilaiWordTertinggi = null;

                for ($i = 0; $i < 3; $i++) {
                    $nilai = $word[$i]->skor ?? '-';
                    if (is_numeric($nilai)) {
                        $nilaiWordTertinggi = max($nilaiWordTertinggi ?? 0, $nilai);
                    }
                    echo "<td>$nilai</td>";
                }

                // Ambil percobaan dengan skor murni tertinggi untuk acuan data markup
                $bestWord = $word->sortByDesc('skor')->first();
                $markupWord = $bestWord?->skor_final ?? '-';

                if (is_null($nilaiWordTertinggi)) {
                    $statusWord = 'BELUM UJIAN';
                    $classWord = 'belum';
                } else {
                    // LULUS jika nilai murni >= 75 ATAU kolom skor_final terisi angka berapapun
                    if ($nilaiWordTertinggi >= 75 || $markupWord != '-') {
                        $statusWord = 'LULUS';
                        $classWord = 'lulus';
                    } else {
                        $statusWord = 'REMEDIAL';
                        $classWord = 'remedial';
                    }
                }

                echo "<td>$markupWord</td>";
                echo "<td class='$classWord'>$statusWord</td>";

                /*
                =================================================================
                LOGIKA BARU - EXCEL
                =================================================================
                */
                $nilaiExcelTertinggi = null;

                for ($i = 0; $i < 3; $i++) {
                    $nilai = $excel[$i]->skor ?? '-';
                    if (is_numeric($nilai)) {
                        $nilaiExcelTertinggi = max($nilaiExcelTertinggi ?? 0, $nilai);
                    }
                    echo "<td>$nilai</td>";
                }

                // Ambil percobaan dengan skor murni tertinggi untuk acuan data markup
                $bestExcel = $excel->sortByDesc('skor')->first();
                $markupExcel = $bestExcel?->skor_final ?? '-';

                if (is_null($nilaiExcelTertinggi)) {
                    $statusExcel = 'BELUM UJIAN';
                    $classExcel = 'belum';
                } else {
                    // LULUS jika nilai murni >= 75 ATAU kolom skor_final terisi angka berapapun
                    if ($nilaiExcelTertinggi >= 75 || $markupExcel != '-') {
                        $statusExcel = 'LULUS';
                        $classExcel = 'lulus';
                    } else {
                        $statusExcel = 'REMEDIAL';
                        $classExcel = 'remedial';
                    }
                }

                echo "<td>$markupExcel</td>";
                echo "<td class='$classExcel'>$statusExcel</td>";

                echo '</tr>';
            }

            echo '</table></body></html>';
        }, 200, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => "attachment; filename=\"$filename\""
        ]);
    }

    public function exportDataMarkup(Request $request)
    {
        $query = Siswa::with([
            'kelas',
            'jurusan',
            'user.percobaanUjians.ujian'
        ]);

        if ($request->kelas_id) {
            $query->where('kelas_id', $request->kelas_id);
        }

        if ($request->jurusan_id) {
            $query->where('jurusan_id', $request->jurusan_id);
        }

        $siswas = $query->get()
            ->sortBy(fn($s) => strtoupper(trim($s->nama_siswa)));

        $filename = 'Data-Markup-' . now()->format('YmdHi') . '.xls';

        return response()->stream(function () use ($siswas) {

            echo '
            <html><head><meta charset="UTF-8">
            <style>
                table{border-collapse:collapse;width:100%;font-family:Arial;}
                th{background:#4472C4;color:white;border:1px solid black;padding:8px;}
                td{border:1px solid black;padding:6px;text-align:center;}
                .nama{text-align:left;}
                .title{font-size:18px;font-weight:bold;text-align:center;padding:14px;}
                .lulus{color:green;font-weight:bold;}
                .remedial{color:red;font-weight:bold;}
                .belum{color:orange;font-weight:bold;}
            </style></head><body><table>';

            echo '<tr><td colspan="11" class="title">
            DATA MARKUP NILAI SISWA
            </td></tr>';

            echo '
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>NIS</th>
                <th>Kelas</th>
                <th>Jurusan</th>
                <th>Nilai Word</th>
                <th>Markup Word</th>
                <th>Status Word</th>
                <th>Nilai Excel</th>
                <th>Markup Excel</th>
                <th>Status Excel</th>
            </tr>';

            $no = 1;

            foreach ($siswas as $siswa) {

                $percobaan = $siswa->user->percobaanUjians ?? collect();

                // Ambil percobaan ujian yang sudah selesai (agar datanya valid)
                $word = $percobaan
                    ->filter(fn($p) => optional($p->ujian)->tipe == 'word' && $p->status == 'selesai')
                    ->sortByDesc('skor')
                    ->first();

                $excel = $percobaan
                    ->filter(fn($p) => optional($p->ujian)->tipe == 'excel' && $p->status == 'selesai')
                    ->sortByDesc('skor')
                    ->first();

                $nilaiWord = $word?->skor ?? '-';
                $markupWord = $word?->skor_final ?? '-';

                $nilaiExcel = $excel?->skor ?? '-';
                $markupExcel = $excel?->skor_final ?? '-';

                // =================================================================
                // PENYESUAIAN LOGIKA STATUS BARU
                // =================================================================

                // Status Word
                if ($nilaiWord == '-') {
                    $statusWord = 'BELUM UJIAN';
                } else {
                    // Lulus jika skor murni >= 75 ATAU kolom markup/skor_final terisi (bukan '-')
                    $statusWord = ($nilaiWord >= 75 || $markupWord != '-') ? 'LULUS' : 'REMEDIAL';
                }

                // Status Excel
                if ($nilaiExcel == '-') {
                    $statusExcel = 'BELUM UJIAN';
                } else {
                    // Lulus jika skor murni >= 75 ATAU kolom markup/skor_final terisi (bukan '-')
                    $statusExcel = ($nilaiExcel >= 75 || $markupExcel != '-') ? 'LULUS' : 'REMEDIAL';
                }
                // =================================================================

                echo '<tr>';

                echo '<td>' . $no++ . '</td>';
                echo '<td class="nama">' . $siswa->nama_siswa . '</td>';
                echo '<td>' . $siswa->nis . '</td>';
                echo '<td>' . optional($siswa->kelas)->nama_kelas . '</td>';
                echo '<td>' . optional($siswa->jurusan)->nama_jurusan . '</td>';

                echo '<td>' . $nilaiWord . '</td>';
                echo '<td>' . $markupWord . '</td>';
                echo '<td>' . $statusWord . '</td>';

                echo '<td>' . $nilaiExcel . '</td>';
                echo '<td>' . $markupExcel . '</td>';
                echo '<td>' . $statusExcel . '</td>';

                echo '</tr>';
            }

            echo '</table></body></html>';
        }, 200, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => "attachment; filename=\"$filename\""
        ]);
    }
    public function markupnilai()
    {
        $siswas = Siswa::with([
            'kelas',
            'jurusan',
            'user.percobaanUjians.ujian',
        ])->get();

        foreach ($siswas as $siswa) {
            $percobaan = $siswa->user->percobaanUjians ?? collect();

            // ==========================================
            // WORD TERBESAR (Berdasarkan Skor Tertinggi)
            // ==========================================
            $wordTerbesar = $percobaan
                ->filter(fn($p) => strtolower(optional($p->ujian)->tipe) == 'word')
                ->sortByDesc('skor')
                ->first();

            // ==========================================
            // EXCEL TERBESAR (Berdasarkan Skor Tertinggi)
            // ==========================================
            $excelTerbesar = $percobaan
                ->filter(fn($p) => strtolower(optional($p->ujian)->tipe) == 'excel')
                ->sortByDesc('skor')
                ->first();

            // Simpan objek percobaan ke properti dinamis siswa
            $siswa->word_terbesar = $wordTerbesar;
            $siswa->excel_terbesar = $excelTerbesar;

            // Tampilkan nilai asli di kolom nilai
            $siswa->nilai_word = $wordTerbesar?->skor;
            $s_skor = $excelTerbesar?->skor;
            $siswa->nilai_excel = $excelTerbesar?->skor;

            // PERBAIKAN LOGIKA: Ambil skor_final LANGSUNG dari percobaan terbesar agar sinkron saat disimpan
            $siswa->nilaiMarkupWord = $wordTerbesar?->skor_final;
            $siswa->nilaiMarkupExcel = $excelTerbesar?->skor_final;
        }

        $kelas = Kelas::all();
        $jurusan = Jurusan::all();

        return view('ujian.markupnilai', compact('siswas', 'kelas', 'jurusan'));
    }

    public function simpanMarkup(Request $request)
    {
        // Validasi data input demi keamanan database
        $request->validate([
            'word_id' => 'nullable|exists:percobaan_ujians,id',
            'excel_id' => 'nullable|exists:percobaan_ujians,id',
            'markup_word' => 'nullable|numeric|min:0|max:100',
            'markup_excel' => 'nullable|numeric|min:0|max:100',
        ]);

        // Update WORD jika ID ada
        if ($request->word_id) {
            \App\Models\PercobaanUjian::where('id', $request->word_id)
                ->update([
                    'skor_final' => $request->markup_word
                ]);
        }

        // Update EXCEL jika ID ada
        if ($request->excel_id) {
            \App\Models\PercobaanUjian::where('id', $request->excel_id)
                ->update([
                    'skor_final' => $request->markup_excel
                ]);
        }

        return back()->with('success', 'Markup berhasil disimpan ke database!');
    }

    public function checkLatihan()
    {
        $user = Auth::user();

        if ($user->role !== 'siswa') {
            return redirect()->route('ujian.index');
        }

        $ujian = Ujian::first();

        if (!$ujian) {
            return redirect()
                ->route('siswa.ujian')
                ->with('error', 'Belum ada ujian tersedia.');
        }

        $latihanSelesai = PercobaanUjian::where('user_id', $user->id)
            ->where('ujian_id', $ujian->id)
            ->where('status', 'selesai')
            ->exists();

        if (!$latihanSelesai) {
            return redirect()->route('ujian.latihan.show', $ujian->id);
        }

        return redirect()->route('siswa.ujian');
    }
}
