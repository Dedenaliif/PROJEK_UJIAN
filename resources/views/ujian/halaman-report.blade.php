@extends('dashboard.index')

@section('content')

<div class="container-xxl container-p-y">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1">Laporan Ujian</h4>
            <small class="text-muted">{{ $ujian->judul }} • Tahun 2026</small>
        </div>

        <a href="{{ route('ujian.export', array_merge(['ujian' => $ujian->id], request()->all())) }}"
            class="btn btn-success px-4 shadow-sm">
            📊 Export Excel
        </a>
    </div>

    {{-- STATISTIK --}}
    <div class="row g-3 mb-4">

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 stat-card">
                <small class="text-muted">TOTAL SISWA</small>
                <h3 class="fw-bold mt-1">{{ $totalSiswa }}</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 stat-card success">
                <small class="text-muted">LULUS</small>
                <h3 class="fw-bold mt-1">{{ $lulus }}</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 stat-card warning">
                <small class="text-muted">REMIDIAL</small>
                <h3 class="fw-bold mt-1">{{ $remedial }}</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 stat-card info">
                <small class="text-muted">RATA-RATA</small>
                <h3 class="fw-bold mt-1">{{ number_format($rata, 1) }}</h3>
            </div>
        </div>

    </div>

    {{-- FILTER --}}
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body p-4">

            <form class="row g-3 align-items-end" method="GET">

                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua</option>
                        <option value="lulus" {{ request('status') == 'lulus' ? 'selected' : '' }}>Lulus</option>
                        <option value="remedial" {{ request('status') == 'remedial' ? 'selected' : '' }}>Remedial</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Kelas</label>
                    <select name="kelas_id" class="form-select">
                        <option value="">Semua</option>
                        @foreach ($listKelas as $k)
                            <option value="{{ $k->id }}"
                                {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Jurusan</label>
                    <select name="jurusan_id" class="form-select">
                        <option value="">Semua</option>
                        @foreach ($listJurusan as $j)
                            <option value="{{ $j->id }}"
                                {{ request('jurusan_id') == $j->id ? 'selected' : '' }}>
                                {{ $j->nama_jurusan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        Cari
                    </button>

                    <a href="{{ route('ujian.report', ['ujian' => $ujian->id]) }}"
                        class="btn btn-outline-secondary w-100">
                        Reset
                    </a>
                </div>

            </form>

        </div>
    </div>

    {{-- TABLE --}}
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">

            <div class="table-responsive">
                <table class="table table-hover align-middle">

                    <thead class="table-light text-center">
                        <tr>
                            <th style="width:50px;">No</th>
                            <th>NIS</th>
                            <th class="text-start">Nama</th>
                            <th>Kelas</th>
                            <th>Nilai</th>
                            <th>Status</th>
                            <th style="width:100px;">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($data as $i => $item)
                        <tr>

                            <td class="text-center">{{ $i + 1 }}</td>

                            <td class="text-center">
                                {{ $item->user->siswa->nis ?? '-' }}
                            </td>

                            <td class="fw-semibold">
                                {{ $item->user->username }}
                            </td>

                            <td class="text-center">
                                {{ $item->user->siswa->kelas->nama_kelas ?? '-' }}
                                <br>
                                <small class="text-muted">
                                    {{ $item->user->siswa->jurusan->nama_jurusan ?? '' }}
                                </small>
                            </td>

                            <td class="text-center fw-bold text-primary">
                                {{ $item->nilai }}
                            </td>

                            <td class="text-center">
                                <span class="badge px-3 py-2
                                    {{ $item->nilai >= 75 ? 'bg-success' : 'bg-danger' }}">
                                    {{ $item->nilai >= 75 ? 'LULUS' : 'REMEDIAL' }}
                                </span>
                            </td>

                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-primary">
                                    👁
                                </button>
                            </td>

                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</div>

<style>
.container-xxl {
    max-width: 1200px;
    margin: auto;
}

.stat-card {
    transition: 0.2s;
}

.stat-card:hover {
    transform: translateY(-3px);
}

.stat-card.success {
    border-left: 4px solid #28a745;
}

.stat-card.warning {
    border-left: 4px solid #ffc107;
}

.stat-card.info {
    border-left: 4px solid #0dcaf0;
}

.card {
    border-radius: 14px;
}

.table td, .table th {
    vertical-align: middle;
}
</style>

@endsection
