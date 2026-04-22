@extends('dashboard.index')

@section('content')

<div class="container py-5">

    {{-- ALERT --}}
    @if(!$lulus)
        <div class="alert alert-danger text-center shadow-sm rounded-3">
            ❌ Nilai kamu <strong>{{ $nilai }}</strong> (Minimal 75) <br>
            Sisa percobaan: <strong>{{ $sisaPercobaan }}</strong>
        </div>
    @else
        <div class="alert alert-success text-center shadow-sm rounded-3">
            🎉 Selamat! Kamu lulus
        </div>
    @endif

    <div class="card shadow-lg border-0 rounded-4 overflow-hidden">

        {{-- HEADER --}}
        <div class="text-center p-5 text-white"
            style="background: linear-gradient(135deg, #4f46e5, #3b82f6);">

            <h2 class="fw-bold mb-2">Ujian Selesai</h2>

            <h3 class="fw-bold
                {{ $lulus ? 'text-warning' : 'text-light' }}">
                {{ $lulus ? '🎉 LULUS' : '😢 BELUM LULUS' }}
            </h3>

            <p class="mb-0">
                {{ $lulus ? 'Kerja bagus! lanjut ke tahap berikutnya' : 'Jangan menyerah, coba lagi!' }}
            </p>
        </div>

        <div class="card-body p-5">

            {{-- NILAI BESAR --}}
            <div class="text-center mb-5">
                <div class="nilai-box mx-auto">
                    {{ $nilai }}
                </div>
                <small class="text-muted">Nilai Akhir</small>
            </div>

            {{-- DATA SISWA --}}
            <div class="row justify-content-center mb-5">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm p-4 rounded-3">

                        <h5 class="fw-bold mb-3 text-center">👤 Data Peserta</h5>

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

            {{-- STATISTIK --}}
            <div class="row text-center mb-5">

                <div class="col-md-4">
                    <div class="card stat-card shadow-sm border-0 p-4">
                        <h3 class="fw-bold text-primary">{{ $totalSoal }}</h3>
                        <small>Total Soal</small>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card stat-card shadow-sm border-0 p-4">
                        <h3 class="fw-bold text-success">{{ $jumlahJawaban }}</h3>
                        <small>Terjawab</small>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card stat-card shadow-sm border-0 p-4">
                        <h3 class="fw-bold
                            {{ $lulus ? 'text-success' : 'text-danger' }}">
                            {{ $lulus ? 'LULUS' : 'GAGAL' }}
                        </h3>
                        <small>Status</small>
                    </div>
                </div>

            </div>

            {{-- BUTTON --}}
            <div class="text-center">
                <a href="{{ url('siswa/ujian') }}"
                   class="btn btn-lg px-5 py-2 fw-bold text-white"
                   style="background: linear-gradient(90deg, #3b82f6, #2563eb); border-radius: 30px;">
                    ← Kembali ke Dashboard
                </a>
            </div>

        </div>
    </div>

</div>

{{-- STYLE --}}
<style>
/* NILAI BULAT */
.nilai-box {
    width: 130px;
    height: 130px;
    border-radius: 50%;
    background: linear-gradient(135deg, #22c55e, #16a34a);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 40px;
    font-weight: bold;
    color: white;
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

/* STAT CARD */
.stat-card {
    border-radius: 15px;
    transition: 0.25s;
}

.stat-card:hover {
    transform: translateY(-5px);
}

/* RESPONSIVE */
@media(max-width:768px){
    .nilai-box {
        width: 100px;
        height: 100px;
        font-size: 28px;
    }
}
</style>

@endsection
