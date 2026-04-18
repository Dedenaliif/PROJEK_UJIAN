@extends('dashboard.index')

@section('content')
@if(!$lulus)
    <div class="alert alert-danger text-center">
        Nilai kamu <strong>{{ $nilai }}</strong>. Minimal lulus adalah <strong>75</strong> <br>
        Sisa percobaan: <strong>{{ $sisaPercobaan }}</strong>
    </div>
@endif

@if($lulus)
    <div class="alert alert-success text-center">
        Selamat! Kamu lulus <br>
        Silakan lanjut ke ujian berikutnya
    </div>
@endif
<div class="container py-5">

    <div class="card shadow-lg border-0">
        <div class="card-body text-center p-5">

            <h2 class="fw-bold text-success mb-3">Ujian Selesai</h2>
            <p class="text-muted">Terima kasih telah mengerjakan ujian</p>

            <hr class="my-4">

            {{-- DATA SISWA --}}
            <h4 class="fw-bold mb-3">Data Peserta</h4>

            <div class="row justify-content-center">
                <div class="col-md-6 text-start">

                    <div class="mb-2">
                        <strong>Nama:</strong> {{ $siswa->nama_siswa ?? '-' }}
                    </div>

                    <div class="mb-2">
                        <strong>NIS:</strong> {{ $siswa->nis ?? '-' }}
                    </div>

                    <div class="mb-2">
                        <strong>Kelas:</strong> {{ $siswa->kelas->nama_kelas ?? '-' }}
                    </div>

                    <div class="mb-2">
                        <strong>Jurusan:</strong> {{ $siswa->jurusan->nama_jurusan ?? '-' }}
                    </div>

                </div>
            </div>

            <hr class="my-4">

            {{-- HASIL --}}
            <h2 class="fw-bold mb-3
                {{ $lulus ? 'text-success' : 'text-danger' }}">
                {{ $lulus ? '🎉 Selamat!' : '😢 Belum Lulus' }}
            </h2>

            <p class="text-muted">
                {{ $lulus ? 'Kamu berhasil menyelesaikan ujian' : 'Kamu harus mencoba lagi' }}
            </p>

            <hr>

            <div class="row text-center mb-4">

                <div class="col-md-3">
                    <div class="card p-3 shadow-sm">
                        <h4 class="fw-bold text-primary">{{ $nilai }}</h4>
                        <small>Nilai</small>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card p-3 shadow-sm">
                        <h4>{{ $totalSoal }}</h4>
                        <small>Total Soal</small>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card p-3 shadow-sm">
                        <h4>{{ $jumlahJawaban }}</h4>
                        <small>Terjawab</small>
                    </div>
                </div>

            </div>

            <div class="mt-5">
                <a href="{{ url('siswa/ujian') }}" class="btn btn-primary px-4">
                    Kembali ke Dashboard
                </a>
            </div>

        </div>
    </div>

</div>
@endsection
