@extends('dashboard.index')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="card shadow-sm border-0">

            {{-- HEADER --}}
            <div class="card-body text-center bg-label-primary rounded-top">

                <h4 class="fw-bold mb-1">
                    Ujian Selesai
                </h4>

                <h2 class="fw-bold {{ $lulus ? 'text-success' : 'text-danger' }}">
                    {{ $lulus ? 'LULUS 🎉' : 'BELUM LULUS 😢' }}
                </h2>

                <p class="mb-0 text-muted">
                    {{ $lulus ? 'Kerja bagus!' : 'Silakan coba lagi' }}
                </p>

            </div>

            <div class="card-body">

                {{-- NILAI --}}
                <div class="text-center mb-5">

                    <h1 class="fw-bold text-primary display-3">
                        {{ $nilai }}
                    </h1>

                    <small class="text-muted fs-6">
                        Nilai Akhir
                    </small>

                </div>

                {{-- NOTIFIKASI KLAIM SERTIFIKAT (HANYA MUNCUL JIKA LULUS) --}}
                @if ($lulus)
                    <div class="row justify-content-center mb-4">
                        <div class="col-lg-6">
                            <div class="alert alert-success d-flex align-items-center p-3 mb-0 border-0 shadow-sm"
                                role="alert">
                                <i class="bx bx-award fs-3 me-2"></i>
                                <div>
                                    <span class="fw-bold">Selamat!</span> Anda berhak mengunduh sertifikat resmi atas
                                    pencapaian kelulusan ini.
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- DATA SISWA --}}
                <div class="row justify-content-center mb-4">

                    <div class="col-lg-6">

                        <div class="border rounded-3 p-4">

                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Nama</span>
                                <strong>{{ $siswa->nama_siswa ?? '-' }}</strong>
                            </div>

                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">NIS</span>
                                <strong>{{ $siswa->nis ?? '-' }}</strong>
                            </div>

                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Kelas</span>

                                <span class="badge bg-primary">
                                    {{ $siswa->kelas->nama_kelas ?? '-' }}
                                </span>
                            </div>

                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Jurusan</span>

                                <span class="badge bg-success">
                                    {{ $siswa->jurusan->nama_jurusan ?? '-' }}
                                </span>
                            </div>

                        </div>

                    </div>

                </div>

                {{-- STATISTIK --}}
                <div class="row text-center g-3 mb-4">

                    <div class="col-md-3">
                        <div class="border rounded-3 p-4 h-100">

                            <h3 class="fw-bold mb-1">
                                {{ $totalSoal }}
                            </h3>

                            <small class="text-muted">
                                Total Soal
                            </small>

                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="border rounded-3 p-4 h-100">

                            <h3 class="fw-bold text-primary mb-1">
                                {{ $jumlahJawaban }}
                            </h3>

                            <small class="text-muted">
                                Terjawab
                            </small>

                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="border rounded-3 p-4 h-100">

                            <h3 class="fw-bold text-success mb-1">
                                {{ $jawabanBenar }}
                            </h3>

                            <small class="text-muted">
                                Jawaban Benar
                            </small>

                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="border rounded-3 p-4 h-100">

                            <h3 class="fw-bold text-danger mb-1">
                                {{ $jawabanSalah }}
                            </h3>

                            <small class="text-muted">
                                Jawaban Salah
                            </small>

                        </div>
                    </div>

                </div>

                {{-- STATUS --}}
                <div class="text-center mb-4">

                    <span class="badge fs-6 px-4 py-2 {{ $lulus ? 'bg-success' : 'bg-danger' }}">
                        {{ $lulus ? 'Lulus' : 'Tidak Lulus' }}
                    </span>

                </div>

                {{-- SISA PERCOBAAN --}}
                <div class="text-center mb-4">

                    <small class="text-muted">
                        Sisa Percobaan:
                        <strong>{{ $sisaPercobaan }}</strong>
                    </small>

                </div>

                {{-- BUTTON AKSI --}}
                <div class="text-center gap-2 d-flex justify-content-center align-items-center">

                    <a href="{{ url('siswa/ujian') }}" class="btn btn-outline-secondary px-4">
                        <i class="bx bx-arrow-back me-1"></i>
                        Kembali
                    </a>

                    {{-- TOMBOL DOWNLOAD JIKA SISWA LULUS NILAI MENCAPAI STANDARD --}}
                    @if ($lulus)
                        <a href="{{ route('ujian.sertifikat', $ujian->id) }}" target="_blank"
                            class="btn btn-warning px-4 shadow-sm fw-semibold text-dark">
                            <i class="bx bx-download me-1"></i>
                            Cetak Sertifikat
                        </a>
                    @endif

                </div>

            </div>

        </div>

    </div>
@endsection
