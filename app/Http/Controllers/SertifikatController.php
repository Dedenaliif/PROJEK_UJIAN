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

        $siswa = DB::table('siswas')
            ->join('jurusans', 'siswas.jurusan_id', '=', 'jurusans.id')
            ->where('siswas.id', $siswaId)
            ->select('siswas.user_id', 'siswas.nama_siswa', 'siswas.nis', 'jurusans.nama_jurusan')
            ->first();

        if (!$siswa) {
            return response()->json(['error' => 'Data siswa tidak ditemukan!'], 404);
        }

        // Cek Syarat Kelulusan Bersyarat
        $lulusWord = $this->cekSyaratKelulusanTipe($siswa->user_id, 'word');
        $lulusExcel = $this->cekSyaratKelulusanTipe($siswa->user_id, 'excel');

        if (!$lulusWord || !$lulusExcel) {
            abort(403, 'Anda belum memenuhi syarat kelulusan nilai pada kedua ujian (Word & Excel).');
        }

        $nilaiWord = $this->getNilaiSpesifik($siswa->user_id, 'word');
        $nilaiExcel = $this->getNilaiSpesifik($siswa->user_id, 'excel');

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

        // HALAMAN 1
        $pdf->AddPage('L', 'A4');
        $pdf->setSourceFile($templatePath);
        $tplIdx1 = $pdf->importPage(1);
        $pdf->useTemplate($tplIdx1, 0, 0, 297, 210);

        $nomorUrutTigaDigit = sprintf('%03d', $siswaId);
        $nomorDinamisDenganSpasi = implode(' ', str_split($nomorUrutTigaDigit));

        $pdf->SetFont('Helvetica', 'B', 14);
        $pdf->SetTextColor(26, 54, 93);
        $pdf->SetY(70);
        $pdf->Cell(270, 10, 'Nomor : ' . $nomorDinamisDenganSpasi . ' / UKK / ALC / V / 26', 0, 1, 'C');

        $pdf->SetFont('Helvetica', 'B', 28);
        $pdf->SetY(97);
        $pdf->Cell(276, 10, strtoupper($siswa->nama_siswa), 0, 1, 'C');

        // HALAMAN 2
        $pdf->AddPage('L', 'A4');
        $tplIdx2 = $pdf->importPage(2);
        $pdf->useTemplate($tplIdx2, 0, 0, 297, 210);

        $namaPeserta = $siswa->nama_siswa;
        $nisn        = $siswa->nis ?? '-';
        $jurusan     = $siswa->nama_jurusan;

        $startX = 117;
        $lebarKotak = 148;
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

        $this->cetakTeksAuto($pdf, $namaPeserta, $startX, 58, $lebarKotak, 14);
        $this->cetakTeksAuto($pdf, $nisn, $startX, 75, $lebarKotak, 14);
        $this->cetakTeksAuto($pdf, $namaJurusanAsli, $startX, 92, $lebarKotak, 14);

        $this->cetakTeksAuto($pdf, $nilaiWord, $startXnilai, 157, $lebarKotakNilai, 14);
        $this->cetakTeksAuto($pdf, $nilaiExcel, $startXnilai, 175, $lebarKotakNilai, 14);

        return response()->streamDownload(function () use ($pdf) {
            $pdf->Output('I', 'Sertifikat.pdf');
        }, 'Sertifikat_Ujian.pdf', [
            'Content-Type' => 'application/pdf',
        ]);
    }

    /**
     * Helper: Validasi Kelulusan Bersyarat (Case-Insensitive)
     */
    private function cekSyaratKelulusanTipe($userId, $tipe)
    {
        return DB::table('percobaan_ujians')
            ->join('ujians', 'percobaan_ujians.ujian_id', '=', 'ujians.id')
            ->where('percobaan_ujians.user_id', $userId)
            ->where('percobaan_ujians.status', 'selesai')
            ->where(function ($q) use ($tipe) {
                $q->where(DB::raw('LOWER(ujians.tipe)'), strtolower($tipe))
                    ->orWhere(DB::raw('LOWER(ujians.judul)'), 'like', '%' . strtolower($tipe) . '%');
            })
            ->whereRaw("
                (percobaan_ujians.skor >= 75) 
                OR 
                (percobaan_ujians.skor < 75 AND percobaan_ujians.skor_final IS NOT NULL)
            ")
            ->exists();
    }

    /**
     * Helper Function: Mengambil nilai untuk ditampilkan di sertifikat (Case-Insensitive)
     */
    private function getNilaiSpesifik($userId, $tipeUjian)
    {
        $percobaan = DB::table('percobaan_ujians')
            ->join('ujians', 'percobaan_ujians.ujian_id', '=', 'ujians.id')
            ->where('percobaan_ujians.user_id', $userId)
            ->where('percobaan_ujians.status', 'selesai')
            ->where(DB::raw('LOWER(ujians.judul)'), 'like', '%' . strtolower($tipeUjian) . '%')
            ->select('percobaan_ujians.skor', 'percobaan_ujians.skor_final')
            ->orderByRaw("percobaan_ujians.skor_final DESC, percobaan_ujians.skor DESC")
            ->first();

        if (!$percobaan) {
            return "0";
        }

        if ($percobaan->skor < 75 && !is_null($percobaan->skor_final)) {
            return (string) $percobaan->skor_final;
        }

        return (string) $percobaan->skor;
    }

    public function downloadSemuaSertifikat()
    {
        $semuaSiswa = DB::table('siswas')
            ->join('jurusans', 'siswas.jurusan_id', '=', 'jurusans.id')
            ->select('siswas.id as siswa_id', 'siswas.user_id', 'siswas.nama_siswa', 'siswas.nis', 'jurusans.nama_jurusan')
            ->get();

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

        foreach ($semuaSiswa as $siswa) {

            $lulusWord = $this->cekSyaratKelulusanTipe($siswa->user_id, 'word');
            $lulusExcel = $this->cekSyaratKelulusanTipe($siswa->user_id, 'excel');

            if (!$lulusWord || !$lulusExcel) {
                continue;
            }

            $nilaiWord = $this->getNilaiSpesifik($siswa->user_id, 'word');
            $nilaiExcel = $this->getNilaiSpesifik($siswa->user_id, 'excel');

            $pdf = new \setasign\Fpdi\Fpdi();

            // HALAMAN 1
            $pdf->AddPage('L', 'A4');
            $pdf->setSourceFile($templatePath);
            $tplIdx1 = $pdf->importPage(1);
            $pdf->useTemplate($tplIdx1, 0, 0, 297, 210);

            $nomorUrutTigaDigit = sprintf('%03d', $siswa->siswa_id);
            $nomorDinamisDenganSpasi = implode(' ', str_split($nomorUrutTigaDigit));

            $pdf->SetFont('Helvetica', 'B', 14);
            $pdf->SetTextColor(26, 54, 93);
            $pdf->SetY(70);
            $pdf->Cell(270, 10, 'Nomor : ' . $nomorDinamisDenganSpasi . ' / UKK / ALC / V / 26', 0, 1, 'C');

            $pdf->SetFont('Helvetica', 'B', 28);
            $pdf->SetY(97);
            $pdf->Cell(276, 10, strtoupper($siswa->nama_siswa), 0, 1, 'C');

            // HALAMAN 2
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

            $pdfString = $pdf->Output('S');
            $cleanNamaSiswa = \Illuminate\Support\Str::slug($siswa->nama_siswa, '_');
            $zip->addFromString('Sertifikat_' . $cleanNamaSiswa . '.pdf', $pdfString);

            $jumlahSertifikatDibuat++;
        }

        $zip->close();

        if ($jumlahSertifikatDibuat === 0) {
            if (file_exists($zipFilePath)) {
                unlink($zipFilePath);
            }
            return redirect()->back()->with('error', 'Belum ada siswa yang memenuhi kriteria kelulusan bersyarat.');
        }

        return response()->download($zipFilePath)->deleteFileAfterSend(true);
    }

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
}
