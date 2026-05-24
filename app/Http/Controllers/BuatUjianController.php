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
            ])
            ->get();

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
        ));
    }

    // ==============================
    // 🔥 REPORT
    // ==============================
    public function report($ujianId)
    {
        $ujian = Ujian::findOrFail($ujianId);

        $listKelas = Kelas::all();
        $listJurusan = Jurusan::all();
        $data = PercobaanUjian::with([
            'user.siswa.kelas',
            'user.siswa.jurusan'
        ])
            ->where('ujian_id', $ujianId)
            ->where('status', 'selesai')
            ->get()

            // hitung ulang nilai
            ->map(function ($item) {

                $hasil = $this->hitungNilai($item);

                $item->skor = $hasil['skor'];
                $item->nilai = $hasil['nilai'];

                return $item;
            })

            // group berdasarkan user
            ->groupBy('user_id')
            ->map(function ($items) {

                // urut berdasarkan nilai tertinggi
                $sorted = $items->sortByDesc('nilai');

                // ambil nilai terbaik
                $best = $sorted->first();

                // hitung percobaan ke berapa
                $percobaanKe = $items
                    ->sortBy('created_at')
                    ->values()
                    ->search(function ($item) use ($best) {
                        return $item->id === $best->id;
                    });

                // simpan info tambahan
                $best->percobaan_terbaik = $percobaanKe + 1;

                // total percobaan
                $best->total_percobaan = $items->count();

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
            // reset collection index
            ->values();
        // FILTER
        if (request('status') == 'lulus') {
            $data = $data->where('nilai', '>=', 75);
        } elseif (request('status') == 'remedial') {
            $data = $data->where('nilai', '<', 75);
        }

        if (request('kelas_id')) {
            $data = $data->filter(
                fn($d) =>
                optional($d->user->siswa)->kelas_id == request('kelas_id')
            );
        }

        if (request('jurusan_id')) {
            $data = $data->filter(
                fn($d) =>
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

        // 1. Ambil data percobaan dengan Eager Loading
        $query = PercobaanUjian::with([
            'user.siswa.kelas',
            'user.siswa.jurusan'
        ])->where('ujian_id', $ujianId);

        // Filter Kelas & Jurusan di tingkat Database
        if (request()->filled('kelas_id')) {
            $query->whereHas('user.siswa', function ($q) {
                $q->where('kelas_id', request('kelas_id'));
            });
        }
        if (request()->filled('jurusan_id')) {
            $query->whereHas('user.siswa', function ($q) {
                $q->where('jurusan_id', request('jurusan_id'));
            });
        }

        $rawPercobaan = $query->get();

        // 2. Hitung nilai aslinya terlebih dahulu lewat fungsi hitungNilai()
        $rawPercobaan = $rawPercobaan->map(function ($item) {
            $hasil = $this->hitungNilai($item);
            $item->nilai = $hasil['nilai'] ?? 0;
            $item->skor = $hasil['skor'] ?? 0;
            return $item;
        });

        // Filter Status Kelulusan jika ada request (Berdasarkan Nilai Tertinggi Siswa)
        // Kelompokkan data berdasarkan User ID (Siswa) terlebih dahulu
        $groupedByUser = $rawPercobaan->groupBy('user_id');

        // 3. Proses penyusunan data per siswa & cari tahu jumlah percobaan maksimal
        $siswaDataList = collect();
        $maxPercobaan = 0;

        foreach ($groupedByUser as $userId => $percobaans) {
            // Urutkan percobaan siswa berdasarkan waktu (tertua ke terbaru)
            $sortedPercobaans = $percobaans->sortBy('created_at')->values();

            // Cari nilai tertinggi dari semua percobaan siswa ini
            $nilaiTertinggi = $sortedPercobaans->max('nilai');

            // Filter status kelulusan di tingkat siswa
            if (request('status') == 'lulus' && $nilaiTertinggi < 75) {
                continue;
            }
            if (request('status') == 'remedial' && $nilaiTertinggi >= 75) {
                continue;
            }

            // Ambil info siswa dari percobaan pertama
            $firstPercobaan = $sortedPercobaans->first();
            $siswa = $firstPercobaan->user->siswa ?? null;

            if (!$siswa) continue;

            // Catat jumlah percobaan terbanyak untuk header nantinya
            $maxPercobaan = max($maxPercobaan, $sortedPercobaans->count());

            $siswaDataList->push([
                'jurusan' => $siswa->jurusan->nama_jurusan ?? '-',
                'kelas' => $siswa->kelas->nama_kelas ?? '-',
                'nis' => $siswa->nis ?? '-',
                'nama' => $siswa->nama_siswa ?? '-',
                'list_nilai' => $sortedPercobaans->pluck('nilai')->toArray(),
                'nilai_tertinggi' => $nilaiTertinggi,
                'status' => $nilaiTertinggi >= 75 ? 'Lulus' : 'Remedial'
            ]);
        }

        // 4. SORTING AKHIR: Urutkan per Jurusan -> per Kelas -> per Nama Siswa
        $siswaDataList = $siswaDataList->sortBy([
            fn($a, $b) => strcmp($a['jurusan'], $b['jurusan']),
            fn($a, $b) => strcmp($a['kelas'], $b['kelas']),
            fn($a, $b) => strcmp($a['nama'], $b['nama']),
        ]);

        // 5. PENAMAAN FILE DINAMIS
        $stringFilter = str_replace(' ', '-', $ujian->judul);
        if (request()->filled('kelas_id')) {
            $kelas = Kelas::find(request('kelas_id'));
            if ($kelas) {
                $stringFilter .= '-' . str_replace(' ', '-', $kelas->nama_kelas);
            }
        }
        if (request()->filled('jurusan_id')) {
            $jurusan = Jurusan::find(request('jurusan_id'));
            if ($jurusan) {
                $stringFilter .= '-' . str_replace(' ', '-', $jurusan->nama_jurusan);
            }
        }
        if (request()->filled('status')) {
            $stringFilter .= '-' . ucfirst(request('status'));
        }

        $filename = "Laporan-{$stringFilter}-" . date('Ymd-Hi') . ".csv";

        // 6. STREAM DOWNLOAD CSV
        return response()->stream(function () use ($siswaDataList, $maxPercobaan) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM

            // Bangun Header Dinamis
            $header = ['No', 'Jurusan', 'Kelas', 'NIS', 'Nama'];

            // Buat kolom percobaan sebanyak percobaan terbanyak (misal: P1, P2, P3)
            for ($i = 1; $i <= $maxPercobaan; $i++) {
                $header[] = "Percobaan $i";
            }

            $header[] = "Nilai Terbaik";
            $header[] = "Status";

            fputcsv($file, $header, ';');

            // Tulis baris data siswa
            $no = 1;
            foreach ($siswaDataList as $data) {
                $row = [
                    $no++,
                    $data['jurusan'],
                    $data['kelas'],
                    $data['nis'],
                    $data['nama'],
                ];

                // Isi nilai tiap percobaan kesamping
                for ($i = 0; $i < $maxPercobaan; $i++) {
                    // Jika siswa punya nilai di percobaan ke-i, masukkan nilainya. Jika tidak, beri tanda '-'
                    $row[] = isset($data['list_nilai'][$i]) ? $data['list_nilai'][$i] : '-';
                }

                $row[] = $data['nilai_tertinggi'];
                $row[] = $data['status'];

                fputcsv($file, $row, ';');
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
        // ==========================================
        // 1. QUERY + FILTER (Eager Loading dioptimalkan)
        // ==========================================
        $query = Siswa::with([
            'kelas',
            'jurusan',
            'user.percobaanUjians.ujian'
        ]);
        $stringFilter = 'Semua';

        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);

            // Ambil data kelas untuk nama file
            $kelas = Kelas::find($request->kelas_id);
            if ($kelas) {
                $stringFilter = $kelas->nama_kelas;
            }
        }

        if ($request->filled('jurusan_id')) {
            $query->where('jurusan_id', $request->jurusan_id);

            // Ambil data jurusan untuk nama file
            $jurusan = Jurusan::find($request->jurusan_id);
            if ($jurusan) {
                // Jika kelas juga dipilih, gabungkan namanya. Jika tidak, pakai nama jurusan saja.
                $stringFilter = $request->filled('kelas_id')
                    ? $stringFilter . '-' . $jurusan->nama_jurusan
                    : $jurusan->nama_jurusan;
            }
        }

        // Bersihkan nama file dari karakter yang dilarang oleh sistem OS (seperti / \ ? * : " < > |) dan spasi
        $slugFilter = str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|', ' '], '-', $stringFilter);

        // Hasilnya akan seperti: Laporan-Ujian-10-RPL-20260523-1726.csv
        $filename = "Laporan-Ujian-{$slugFilter}-" . date('Ymd-Hi') . ".csv";

        // Ambil data dari database
        $siswas = $query->get();

        // ==========================================
        // 2. HITUNG MAKSIMAL KOLOM DYNAMIC (Word & Excel)
        // ==========================================
        $maxWord = 0;
        $maxExcel = 0;

        foreach ($siswas as $siswa) {
            $percobaan = $siswa->user->percobaanUjians ?? collect();

            $wordCount = $percobaan->filter(fn($p) => strtolower(optional($p->ujian)->tipe) == 'word')->count();
            $excelCount = $percobaan->filter(fn($p) => strtolower(optional($p->ujian)->tipe) == 'excel')->count();

            $maxWord = max($maxWord, $wordCount);
            $maxExcel = max($maxExcel, $excelCount);
        }

        // ==========================================
        // 3. SORTING DATA SECARA KONSISTEN
        // ==========================================
        // Di-sort berdasarkan Kelas, lalu Jurusan, lalu Nama Siswa
        $siswas = $siswas->sortBy([
            fn($a, $b) => strcmp(optional($a->kelas)->nama_kelas ?? '', optional($b->kelas)->nama_kelas ?? ''),
            fn($a, $b) => strcmp(optional($a->jurusan)->nama_jurusan ?? '', optional($b->jurusan)->nama_jurusan ?? ''),
            fn($a, $b) => strcmp($a->nama_siswa ?? '', $b->nama_siswa ?? '')
        ]);

        // $filename = "Laporan-Semua-Ujian-" . $jurusan->nama_jurusan . date('Ymd-Hi') . ".csv";

        // ==========================================
        // 4. STREAM DOWNLOAD CSV
        // ==========================================
        return response()->stream(function () use ($siswas, $maxWord, $maxExcel) {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM agar Excel langsung membaca karakter spesial & pemisah dengan benar
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Buat Struktur Header yang Konsisten
            $header = [
                'No',
                'Kelas',
                'Jurusan',
                'NIS',
                'Nama Siswa'
            ];

            // Header Dynamic Word
            for ($i = 1; $i <= $maxWord; $i++) {
                $header[] = "Word Percobaan $i";
            }
            $header[] = "Status Word";

            // Header Dynamic Excel
            for ($i = 1; $i <= $maxExcel; $i++) {
                $header[] = "Excel Percobaan $i";
            }
            $header[] = "Status Excel";

            // Tulis header ke file csv (Rekomendasi gunakan koma ',' agar standar, atau tetap ';' jika regional komputer Indonesia)
            fputcsv($file, $header, ';');

            // Tulis Data Siswa
            $no = 1;
            foreach ($siswas as $siswa) {
                $percobaan = $siswa->user->percobaanUjians ?? collect();

                // Filter & Sort Ujian Word
                $word = $percobaan
                    ->filter(fn($p) => strtolower(optional($p->ujian)->tipe) == 'word')
                    ->sortBy('created_at')
                    ->values();

                // Filter & Sort Ujian Excel
                $excel = $percobaan
                    ->filter(fn($p) => strtolower(optional($p->ujian)->tipe) == 'excel')
                    ->sortBy('created_at')
                    ->values();

                // Set Data Profil Utama di Depan
                $row = [
                    $no++,
                    optional($siswa->kelas)->nama_kelas ?? '-',
                    optional($siswa->jurusan)->nama_jurusan ?? '-',
                    $siswa->nis,
                    $siswa->nama_siswa,
                ];

                // --- PROSES NILAI WORD ---
                $nilaiWordList = [];
                for ($i = 0; $i < $maxWord; $i++) {
                    $nilai = isset($word[$i]) ? $word[$i]->skor : null;
                    $nilaiWordList[] = $nilai;
                    $row[] = $nilai ?? '-';
                }

                $nilaiTerbesarWord = collect($nilaiWordList)->filter(fn($v) => !is_null($v))->max();
                if ($nilaiTerbesarWord === null) {
                    $statusWord = 'Belum Ujian';
                } else {
                    $statusWord = $nilaiTerbesarWord >= 75 ? 'Lulus' : 'Remedial';
                }
                $row[] = $statusWord;

                // --- PROSES NILAI EXCEL ---
                $nilaiExcelList = [];
                for ($i = 0; $i < $maxExcel; $i++) {
                    $nilai = isset($excel[$i]) ? $excel[$i]->skor : null;
                    $nilaiExcelList[] = $nilai;
                    $row[] = $nilai ?? '-';
                }

                $nilaiTerbesarExcel = collect($nilaiExcelList)->filter(fn($v) => !is_null($v))->max();
                if ($nilaiTerbesarExcel === null) {
                    $statusExcel = 'Belum Ujian';
                } else {
                    $statusExcel = $nilaiTerbesarExcel >= 75 ? 'Lulus' : 'Remedial';
                }
                $row[] = $statusExcel;

                // Tulis baris data siswa ke CSV
                fputcsv($file, $row, ';');
            }

            fclose($file);
        }, 200, [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=\"$filename\"",
        ]);
    }
    public function exportDataMarkup(Request $request)
    {
        $query = Siswa::with([
            'kelas',
            'jurusan',
            'user.percobaanUjians.ujian'
        ]);

        // =========================
        // FILTER KELAS
        // =========================
        if ($request->kelas_id) {

            $query->where(
                'kelas_id',
                $request->kelas_id
            );
        }

        // =========================
        // FILTER JURUSAN
        // =========================
        if ($request->jurusan_id) {

            $query->where(
                'jurusan_id',
                $request->jurusan_id
            );
        }

        $siswas = $query->get();

        $filename = 'Data-Markup-' . now()->format('Ymd-His') . '.csv';

        return response()->stream(function () use ($siswas) {

            $file = fopen('php://output', 'w');

            // UTF-8 BOM
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // =========================
            // HEADER
            // =========================
            fputcsv($file, [
                'No',
                'Nama',
                'NIS',
                'Kelas',
                'Jurusan',
                'Nilai Word',
                'Markup Word',
                'Nilai Excel',
                'Markup Excel',
            ], ';');

            $no = 1;

            foreach ($siswas as $siswa) {

                $percobaan = $siswa->user->percobaanUjians ?? collect();

                // =========================
                // WORD TERBESAR
                // =========================
                $wordTerbesar = $percobaan
                    ->filter(
                        fn($p) =>
                        strtolower(optional($p->ujian)->tipe) == 'word'
                    )
                    ->sortByDesc('skor')
                    ->first();

                // =========================
                // EXCEL TERBESAR
                // =========================
                $excelTerbesar = $percobaan
                    ->filter(
                        fn($p) =>
                        strtolower(optional($p->ujian)->tipe) == 'excel'
                    )
                    ->sortByDesc('skor')
                    ->first();

                fputcsv($file, [

                    $no++,

                    $siswa->nama_siswa,

                    $siswa->nis,

                    optional($siswa->kelas)->nama_kelas,

                    optional($siswa->jurusan)->nama_jurusan,

                    // nilai asli word
                    $wordTerbesar?->skor ?? '-',

                    // markup/final word
                    $wordTerbesar?->skor_final ?? '-',

                    // nilai asli excel
                    $excelTerbesar?->skor ?? '-',

                    // markup/final excel
                    $excelTerbesar?->skor_final ?? '-',

                ], ';');
            }

            fclose($file);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' =>
            "attachment; filename=\"$filename\"",
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
