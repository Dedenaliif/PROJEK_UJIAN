<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Setasign\Fpdi\Fpdi;
use Illuminate\Support\Facades\DB;
use ZipArchive;

class SertifikatController extends Controller
{
    public function layoutDummy($siswaId)
    {
        // =================================================================
        // AMBIL DATA REAL DARI DATABASE
        // =================================================================

        // 1. Ambil data siswa dan jurusannya
        $siswa = DB::table('siswas')
            ->join('jurusans', 'siswas.jurusan_id', '=', 'jurusans.id')
            ->where('siswas.id', $siswaId)
            ->select('siswas.user_id', 'siswas.nama_siswa', 'siswas.nis', 'jurusans.nama_jurusan')
            ->first();

        if (!$siswa) {
            return response()->json(['error' => 'Data siswa tidak ditemukan!'], 404);
        }
        $lulusWord = DB::table('percobaan_ujians')->join('ujians', 'percobaan_ujians.ujian_id', '=', 'ujians.id')
            ->where('percobaan_ujians.user_id', $siswa->user_id)
            ->where(fn($q) => $q->where('ujians.tipe', 'word')->orWhere('ujians.judul', 'like', '%Word%'))
            ->where(fn($q) => $q->where('percobaan_ujians.skor', '>=', 75)->orWhere('percobaan_ujians.skor_final', '>=', 75))
            ->exists();

        $lulusExcel = DB::table('percobaan_ujians')->join('ujians', 'percobaan_ujians.ujian_id', '=', 'ujians.id')
            ->where('percobaan_ujians.user_id', $siswa->user_id)
            ->where(fn($q) => $q->where('ujians.tipe', 'excel')->orWhere('ujians.judul', 'like', '%Excel%'))
            ->where(fn($q) => $q->where('percobaan_ujians.skor', '>=', 75)->orWhere('percobaan_ujians.skor_final', '>=', 75))
            ->exists();

        if (!$lulusWord || !$lulusExcel) {
            abort(403, 'Anda belum memenuhi syarat kelulusan nilai (minimal 75) pada kedua ujian.');
        }

        // 2. Ambil Nilai Word & Excel berdasarkan user_id siswa
        // Catatan: Asumsi nama/judul ujian mengandung kata 'Word' atau 'Excel'
        $nilaiWord = $this->getNilaiSpesifik($siswa->user_id, 'Word');
        $nilaiExcel = $this->getNilaiSpesifik($siswa->user_id, 'Excel');


        // =================================================================
        // PROSES SETTING FPDI
        // =================================================================
        $pdf = new \setasign\Fpdi\Fpdi();
        $templatePath = storage_path('app/templates/template_sertifikat.pdf');

        if (!file_exists($templatePath)) {
            return response()->json([
                'error' => 'File template tidak ditemukan!',
                'solusi' => 'Pastikan Anda sudah menaruh file "template_sertifikat.pdf" di folder: ' . storage_path('app/templates/')
            ], 404);
        }

        // =================================================================
        // HALAMAN 1
        // =================================================================
        $pdf->AddPage('L', 'A4');
        $pdf->setSourceFile($templatePath);
        $tplIdx1 = $pdf->importPage(1);
        $pdf->useTemplate($tplIdx1, 0, 0, 297, 210);

        // Teks Nomor Sertifikat di Halaman 1
        // 1. Ambil ID siswa lalu format menjadi 3 digit (misal: ID 2 -> "002", ID 15 -> "015")
        $nomorUrutTigaDigit = sprintf('%03d', $siswaId);

        // 2. Beri spasi di antara angka (misal: "002" -> "0 0 2")
        $nomorDinamisDenganSpasi = implode(' ', str_split($nomorUrutTigaDigit));

        // 3. Cetak ke PDF
        $pdf->SetFont('Helvetica', 'B', 14);
        $pdf->SetTextColor(26, 54, 93);
        $pdf->SetY(70);
        $pdf->Cell(270, 10, 'Nomor : ' . $nomorDinamisDenganSpasi . ' / UKK / ALC / V / 26', 0, 1, 'C');

        // Teks Nama di Halaman 1
        $pdf->SetFont('Helvetica', 'B', 28);
        $pdf->SetY(97);
        $pdf->Cell(276, 10, strtoupper($siswa->nama_siswa), 0, 1, 'C');


        // =================================================================
        // HALAMAN 2 (LOGIKA ANTI MELUBER & KUNCI KOORDINAT X)
        // =================================================================
        $pdf->AddPage('L', 'A4');
        $tplIdx2 = $pdf->importPage(2);
        $pdf->useTemplate($tplIdx2, 0, 0, 297, 210);

        // --- MATIKAN GARIS BANTU JIKA LAYOUT SUDAH FIX ---
        // $this->gambarGarisBantu($pdf);

        // Pindahkan data real database ke variabel layout
        $namaPeserta = $siswa->nama_siswa;
        $nisn        = $siswa->nis ?? '-';
        $jurusan     = $siswa->nama_jurusan;

        // Koordinat Kotak Identitas (Sisi Kiri)
        $startX = 117;
        $lebarKotak = 148;

        // Koordinat Kotak Nilai (Sisi Kanan)
        $startXnilai = 178;
        $lebarKotakNilai = 87;

        $namaJurusanAsli = $jurusan;
        switch (strtoupper($jurusan)) {
            case 'TJKT':
                $namaJurusanAsli = 'Teknik Jaringan Komputer dan Telekomunikasi';
                break;
            case 'AKL':
                $namaJurusanAsli = 'Akuntansi dan Keuangan Lembaga';
                break;
            case 'MPLB':
                $namaJurusanAsli = 'Manajemen Perkantoran dan Layanan Bisnis';
                break;
            case 'BR':
                $namaJurusanAsli = 'Bisnis Retail';
                break;
        }
        // --- PROSES CETAK DATA IDENTITAS SISWA ---
        $this->cetakTeksAuto($pdf, $namaPeserta, $startX, 58, $lebarKotak, 14);
        $this->cetakTeksAuto($pdf, $nisn, $startX, 75, $lebarKotak, 14);
        $this->cetakTeksAuto($pdf, $namaJurusanAsli, $startX, 92, $lebarKotak, 14);

        // --- PROSES CETAK DATA NILAI ---
        $this->cetakTeksAuto($pdf, $nilaiWord, $startXnilai, 157, $lebarKotakNilai, 14);
        $this->cetakTeksAuto($pdf, $nilaiExcel, $startXnilai, 175, $lebarKotakNilai, 14);


        // =================================================================
        // OUTPUT GENERATE PDF KE BROWSER
        // =================================================================
        return response()->streamDownload(function () use ($pdf, $siswa) {
            $pdf->Output('I', 'Sertifikat_' . $siswa->nama_siswa . '.pdf');
        }, 'Sertifikat_Ujian.pdf', [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="Sertifikat_Ujian.pdf"'
        ]);
    }

    /**
     * Helper Function: Mengambil nilai berdasarkan tipe ujian dan aturan skor/skor_final
     */
    private function getNilaiSpesifik($userId, $tipeUjian)
    {
        // Ambil percobaan ujian terakhir/terbaik yang statusnya 'selesai'
        $percobaan = DB::table('percobaan_ujians')
            ->join('ujians', 'percobaan_ujians.ujian_id', '=', 'ujians.id')
            ->where('percobaan_ujians.user_id', $userId)
            ->where('ujians.judul', 'like', '%' . $tipeUjian . '%')
            ->where('percobaan_ujians.status', 'selesai')
            ->select('percobaan_ujians.skor', 'percobaan_ujians.skor_final')
            ->orderBy('percobaan_ujians.skor', 'desc') // Ambil skor tertinggi jika ada multi-percobaan
            ->first();

        if (!$percobaan) {
            return "0"; // Default jika belum ikut ujian
        }

        $skor = $percobaan->skor;
        $skorFinal = $percobaan->skor_final;

        // Aturan Kondisi Anda:
        // 1. Jika ada skor_final (mark), namun murninya >= 75 -> pakai skor murni
        // 2. Jika skor murni < 75 -> ambil dari skor_final
        // 3. Jika skor_final tidak diisi (null), gunakan skor murni apa adanya
        if (!is_null($skorFinal)) {
            if ($skor >= 75) {
                return (string) $skor;
            } else {
                return (string) $skorFinal;
            }
        }

        return (string) ($skor ?? 0);
    }

    /**
     * Helper Function: Cetak teks otomatis pakem Rata Kiri ('L') dan menyusut jika kepanjangan
     */
    private function cetakTeksAuto($pdf, $teks, $x, $y, $lebarMaksimal, $fontSizeAwal)
    {
        $teksUcase = strtoupper($teks);

        $pdf->SetFont('Times', 'B', $fontSizeAwal);
        $pdf->SetTextColor(0, 0, 0);

        $batasAman = $lebarMaksimal - 5;

        while ($pdf->GetStringWidth($teksUcase) > $batasAman) {
            $fontSizeAwal -= 0.5;
            $pdf->SetFont('Times', 'B', $fontSizeAwal);

            if ($fontSizeAwal <= 7) {
                break;
            }
        }

        $pdf->SetXY($x, $y);
        $pdf->Cell($lebarMaksimal, 10, $teksUcase, 0, 1, 'L');
    }

    /**
     * Helper Function: Menggambar Grid Merah Pembantu
     */
    private function gambarGarisBantu($pdf)
    {
        $pdf->SetDrawColor(220, 53, 69);
        $pdf->SetTextColor(150, 150, 150);
        $pdf->SetFont('Helvetica', '', 8);

        for ($i = 20; $i < 297; $i += 20) {
            $pdf->Line($i, 0, $i, 210);
            $pdf->Text($i + 1, 5, $i . 'mm');
        }

        for ($j = 20; $j < 210; $j += 20) {
            $pdf->Line(0, $j, 297, $j);
            $pdf->Text(5, $j - 1, $j . 'mm');
        }
    }

    public function downloadSemuaSertifikat()
    {
        // 1. Ambil semua siswa yang datanya valid dan punya jurusan
        $semuaSiswa = DB::table('siswas')
            ->join('jurusans', 'siswas.jurusan_id', '=', 'jurusans.id')
            // Pastikan kita men-select 'siswas.id' dengan jelas
            ->select('siswas.id as siswa_id', 'siswas.user_id', 'siswas.nama_siswa', 'siswas.nis', 'jurusans.nama_jurusan')
            ->get();

        // 2. Siapkan file ZIP sementara di server
        $zip = new ZipArchive;
        $zipFileName = 'Semua_Sertifikat_Ujian_' . date('Y-m-d') . '.zip';
        $zipFilePath = storage_path('app/' . $zipFileName);

        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
            return response()->json(['error' => 'Gagal membuat file ZIP temporary'], 500);
        }

        $templatePath = storage_path('app/templates/template_sertifikat.pdf');
        if (!file_exists($templatePath)) {
            return response()->json(['error' => 'File template sertifikat tidak ditemukan!'], 404);
        }

        $jumlahSertifikatDibuat = 0;

        // 3. Looping cetak sertifikat tiap siswa
        foreach ($semuaSiswa as $siswa) {

            // Cek kelulusan Word untuk siswa ini
            $lulusWord = DB::table('percobaan_ujians')
                ->join('ujians', 'percobaan_ujians.ujian_id', '=', 'ujians.id')
                ->where('percobaan_ujians.user_id', $siswa->user_id)
                ->where(fn($q) => $q->where('ujians.tipe', 'word')->orWhere('ujians.judul', 'like', '%Word%'))
                ->where(fn($q) => $q->where('percobaan_ujians.skor', '>=', 75)->orWhere('percobaan_ujians.skor_final', '>=', 75))
                ->exists();

            // Cek kelulusan Excel untuk siswa ini
            $lulusExcel = DB::table('percobaan_ujians')
                ->join('ujians', 'percobaan_ujians.ujian_id', '=', 'ujians.id')
                ->where('percobaan_ujians.user_id', $siswa->user_id)
                ->where(fn($q) => $q->where('ujians.tipe', 'excel')->orWhere('ujians.judul', 'like', '%Excel%'))
                ->where(fn($q) => $q->where('percobaan_ujians.skor', '>=', 75)->orWhere('percobaan_ujians.skor_final', '>=', 75))
                ->exists();

            // Jika siswa tidak lolos/belum ikut salah satu ujian, lewati (skip)
            if (!$lulusWord || !$lulusExcel) {
                continue;
            }

            // Ambil nilai real
            $nilaiWord = $this->getNilaiSpesifik($siswa->user_id, 'Word');
            $nilaiExcel = $this->getNilaiSpesifik($siswa->user_id, 'Excel');

            // Proses Instansiasi FPDI per Siswa
            $pdf = new \setasign\Fpdi\Fpdi();

            // -------------------------------------------------------------
            // HALAMAN 1 (BAGIAN YANG DIUBAH MENGGUNAKAN ID SISWA)
            // -------------------------------------------------------------
            $pdf->AddPage('L', 'A4');
            $pdf->setSourceFile($templatePath);
            $tplIdx1 = $pdf->importPage(1);
            $pdf->useTemplate($tplIdx1, 0, 0, 297, 210);

            // UBAH DI SINI: Gunakan $siswa->siswa_id (bukan counter looping) untuk nomor dinamis
            $nomorUrutTigaDigit = sprintf('%03d', $siswa->siswa_id);
            $nomorDinamisDenganSpasi = implode(' ', str_split($nomorUrutTigaDigit));

            $pdf->SetFont('Helvetica', 'B', 14);
            $pdf->SetTextColor(26, 54, 93);
            $pdf->SetY(70);
            $pdf->Cell(270, 10, 'Nomor : ' . $nomorDinamisDenganSpasi . ' / UKK / ALC / V / 26', 0, 1, 'C');

            $pdf->SetFont('Helvetica', 'B', 28);
            $pdf->SetY(97);
            $pdf->Cell(276, 10, strtoupper($siswa->nama_siswa), 0, 1, 'C');

            // -------------------------------------------------------------
            // HALAMAN 2
            // -------------------------------------------------------------
            $pdf->AddPage('L', 'A4');
            $tplIdx2 = $pdf->importPage(2);
            $pdf->useTemplate($tplIdx2, 0, 0, 297, 210);

            $startX = 117;
            $lebarKotak = 148;
            $startXnilai = 178;
            $lebarKotakNilai = 87;

            $this->cetakTeksAuto($pdf, $siswa->nama_siswa, $startX, 58, $lebarKotak, 14);
            $this->cetakTeksAuto($pdf, $siswa->nis ?? '-', $startX, 75, $lebarKotak, 14);
            $this->cetakTeksAuto($pdf, $siswa->nama_jurusan, $startX, 92, $lebarKotak, 14);

            $this->cetakTeksAuto($pdf, $nilaiWord, $startXnilai, 157, $lebarKotakNilai, 14);
            $this->cetakTeksAuto($pdf, $nilaiExcel, $startXnilai, 175, $lebarKotakNilai, 14);

            // Masukkan file PDF ke dalam ZIP
            $pdfString = $pdf->Output('S');
            $cleanNamaSiswa = \Illuminate\Support\Str::slug($siswa->nama_siswa, '_');
            $zip->addFromString('Sertifikat_' . $cleanNamaSiswa . '.pdf', $pdfString);

            $jumlahSertifikatDibuat++;
        }

        // 4. Tutup ZIP setelah selesai
        $zip->close();

        if ($jumlahSertifikatDibuat === 0) {
            if (file_exists($zipFilePath)) {
                unlink($zipFilePath);
            }
            return redirect()->back()->with('error', 'Belum ada siswa yang memenuhi syarat kelulusan nilai (diatas 75) untuk dicetak sertifikatnya.');
        }

        // 5. Download file ZIP ke browser admin/penguji
        return response()->download($zipFilePath)->deleteFileAfterSend(true);
    }
}
