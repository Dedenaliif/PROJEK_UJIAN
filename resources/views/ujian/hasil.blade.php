@extends('dashboard.index')

@section('content')

<div class="card shadow-sm">

    {{-- HEADER --}}
    <div class="card-body text-center bg-label-primary">

        <h4 class="fw-bold mb-1">Ujian Selesai</h4>

        <h3 class="fw-bold {{ $lulus ? 'text-success' : 'text-danger' }}">
            {{ $lulus ? 'LULUS 🎉' : 'BELUM LULUS 😢' }}
        </h3>

        <p class="mb-0">
            {{ $lulus ? 'Kerja bagus!' : 'Silakan coba lagi' }}
        </p>

    </div>

    <div class="card-body">

        {{-- NILAI --}}
        <div class="text-center mb-4">
            <h1 class="fw-bold text-primary">{{ $nilai }}</h1>
            <small class="text-muted">Nilai Akhir</small>
        </div>

        {{-- DATA --}}
        <div class="row justify-content-center mb-4">
            <div class="col-md-6">

                <div class="border rounded p-3">

                    <div class="d-flex justify-content-between mb-2">
                        <span>Nama</span>
                        <strong>{{ $siswa->nama_siswa ?? '-' }}</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>NIS</span>
                        <strong>{{ $siswa->nis ?? '-' }}</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Kelas</span>
                        <strong>{{ $siswa->kelas->nama_kelas ?? '-' }}</strong>
                    </div>

                    <div class="d-flex justify-content-between">
                        <span>Jurusan</span>
                        <strong>{{ $siswa->jurusan->nama_jurusan ?? '-' }}</strong>
                    </div>

                </div>

            </div>
        </div>

        {{-- STAT --}}
        <div class="row text-center mb-4">

            <div class="col-md-4">
                <div class="border rounded p-3">
                    <h5 class="fw-bold">{{ $totalSoal }}</h5>
                    <small>Total Soal</small>
                </div>
            </div>

            <div class="col-md-4">
                <div class="border rounded p-3">
                    <h5 class="fw-bold">{{ $jumlahJawaban }}</h5>
                    <small>Terjawab</small>
                </div>
            </div>

            <div class="col-md-4">
                <div class="border rounded p-3">
                    <h5 class="fw-bold {{ $lulus ? 'text-success' : 'text-danger' }}">
                        {{ $lulus ? 'Lulus' : 'Gagal' }}
                    </h5>
                    <small>Status</small>
                </div>
            </div>

        </div>

        {{-- BUTTON --}}
        <div class="text-center">
            <a href="{{ url('siswa/ujian') }}" class="btn btn-primary px-4">
                ← Kembali
            </a>
        </div>

    </div>

</div>

@endsection
