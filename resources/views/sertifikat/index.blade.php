<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat Kelulusan</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&family=Great+Vibes&display=swap');

        body {
            margin: 0;
            padding: 0;
            background-color: #eaeaea;
            font-family: 'Montserrat', sans-serif;
        }

        /* Ukuran standar A4 Landscape */
        .certificate-container {
            width: 297mm;
            height: 210mm;
            background-color: #ffffff;
            padding: 20mm;
            box-sizing: border-box;
            position: relative;
            margin: 10px auto;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        /* Desain Bingkai / Border Elegan */
        .certificate-border {
            width: 100%;
            height: 100%;
            border: 4px solid #1e293b;
            /* Slate Dark */
            box-sizing: border-box;
            position: relative;
            padding: 10mm;
        }

        /* Aksen Sudut Geometris Modern */
        .certificate-border::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 40px;
            height: 40px;
            border-top: 8px solid #d97706;
            /* Amber Gold */
            border-left: 8px solid #d97706;
        }

        .certificate-border::after {
            content: "";
            position: absolute;
            bottom: 0;
            right: 0;
            width: 40px;
            height: 40px;
            border-bottom: 8px solid #d97706;
            border-right: 8px solid #d97706;
        }

        /* Konten Sertifikat */
        .certificate-content {
            text-align: center;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .main-title {
            font-size: 42px;
            font-weight: 800;
            color: #1e293b;
            letter-spacing: 5px;
            margin: 0 0 10px 0;
            text-transform: uppercase;
        }

        .subtitle {
            font-size: 16px;
            font-weight: 600;
            color: #64748b;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin: 0;
        }

        .awarded-to {
            font-size: 14px;
            font-style: italic;
            color: #94a3b8;
            margin: 25px 0 10px 0;
        }

        /* Nama Siswa Menggunakan Font Kaligrafi/Script */
        .student-name {
            font-family: 'Arial', cursive;
            font-size: 56px;
            color: #d97706;
            margin: 0;
            border-bottom: 2px solid #cbd5e1;
            padding-bottom: 10px;
            min-width: 60%;
        }

        .reason {
            font-size: 15px;
            color: #334155;
            line-height: 1.6;
            max-width: 750px;
            margin: 25px 0;
        }

        .exam-name {
            font-weight: 700;
            color: #1e293b;
        }

        /* Badge Nilai */
        .score-badge {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 10px 25px;
            border-radius: 5px;
            font-weight: 700;
            color: #0f172a;
            font-size: 16px;
            margin-bottom: 40px;
        }

        .score-value {
            color: #16a34a;
            /* Hijau Sukses */
            font-size: 18px;
        }

        /* Bagian Tanda Tangan & Tanggal */
        .footer-section {
            width: 80%;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: auto;
        }

        .signature-block,
        .date-block {
            width: 200px;
            text-align: center;
        }

        .signature-line {
            border-bottom: 1px solid #64748b;
            margin-bottom: 8px;
            height: 60px;
            /* Ruang untuk file gambar TTD jika ada */
        }

        .meta-title {
            font-size: 12px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0;
        }

        .meta-value {
            font-size: 14px;
            font-weight: 600;
            color: #334155;
            margin: 4px 0 0 0;
        }
    </style>
</head>

<body>

    <div class="certificate-container">
        <div class="certificate-border">
            <div class="certificate-content">

                <h1 class="main-title">Sertifikat Kelulusan</h1>
                <p class="subtitle">Diberikan Atas Pencapaian Luar Biasa</p>

                <p class="awarded-to">Sertifikat ini secara resmi diberikan kepada:</p>

                <h2 class="student-name">{{ $siswa->nama_siswa }}</h2>

                <p class="reason">
                    Yang bersangkutan dinyatakan LULUS dalam pelaksanaan ujian <span
                        class="exam-name">"{{ $ujian->judul }}"</span> yang diselenggarakan Oleh Auli Learning Center (ALC)
                   , setelah berhasil memenuhi seluruh kriteria penilaian standardisasi yang ditetapkan.
                </p>

                <div class="score-badge">
                    Nilai Hasil Akhir: <span class="score-value">{{ $nilai }} / 100</span>
                </div>

                <div class="footer-section">
                    <div class="date-block">
                        <p class="meta-value">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                        <div style="border-top: 1px solid #64748b; margin-top: 8px;"></div>
                        <p class="meta-title" style="margin-top: 8px;">Tanggal Terbit</p>
                    </div>

                    <div class="signature-block">
                        <div class="signature-line">
                        </div>
                        <p class="meta-value">Nama Instruktur / Kepala</p>
                        <p class="meta-title">NIP.</p>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>

</html>
