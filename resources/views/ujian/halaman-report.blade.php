@extends('dashboard.index')

@section('content')
    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-0">Laporan Ujian {{ $ujian->judul }}</h3>
                <p class="text-muted">Periode 2026</p>
            </div>
            <div class="d-flex gap-2">
                {{-- <button class="btn btn-outline-secondary"><i class="bi bi-printer"></i> Cetak</button> --}}
                <a href="{{ route('ujian.export', array_merge(['ujian' => $ujian->id], request()->all())) }}"
                    class="btn btn-success shadow-sm">
                    <i class="bi bi-file-earmark-excel me-1"></i> Export ke Excel
                </a>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card p-3 border-start border-primary border-4">
                    <small class="text-muted fw-bold">TOTAL SISWA</small>
                    <h3 class="fw-bold">{{ $totalSiswa }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3 border-start border-success border-4">
                    <small class="text-muted fw-bold">LULUS</small>
                    <h3 class="fw-bold">{{ $lulus }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3 border-start border-warning border-4">
                    <small class="text-muted fw-bold">REMIDIAL</small>
                    <h3 class="fw-bold">{{ $remedial }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3 border-start border-info border-4">
                    <small class="text-muted fw-bold">RATA-RATA NILAI</small>
                    <h3 class="fw-bold">{{ number_format($rata, 1) }}</h3>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3 border-bottom-0">
                <div class="d-flex align-items-center">
                    <i class="bi bi-funnel text-primary me-2"></i>
                    <h6 class="fw-bold mb-0">Filter Laporan</h6>
                </div>
            </div>
            <div class="card-body pt-0">
                <form class="row g-3 align-items-end" method="GET">
                    <!-- Filter Status -->
                    <div class="col-md-3 col-lg-2">
                        <label class="form-label small text-uppercase fw-semibold text-muted">Status Kelulusan</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-check-circle"></i></span>
                            <select name="status" class="form-select border-start-0 ps-0">
                                <option value="">Semua Status</option>
                                <option value="lulus" {{ request('status') == 'lulus' ? 'selected' : '' }}>Lulus</option>
                                <option value="remedial" {{ request('status') == 'remedial' ? 'selected' : '' }}>Remedial
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- Filter Kelas -->
                    <div class="col-md-3 col-lg-2">
                        <label class="form-label small text-uppercase fw-semibold text-muted">Kelas</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-door-open"></i></span>
                            <select name="kelas_id" class="form-select border-start-0 ps-0">
                                <option value="">Semua Kelas</option>
                                @foreach ($listKelas as $k)
                                    <option value="{{ $k->id }}"
                                        {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                                        {{ $k->nama_kelas }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Filter Jurusan -->
                    <div class="col-md-4 col-lg-3">
                        <label class="form-label small text-uppercase fw-semibold text-muted">Jurusan</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-mortarboard"></i></span>
                            <select name="jurusan_id" class="form-select border-start-0 ps-0">
                                <option value="">Semua Jurusan</option>
                                @foreach ($listJurusan as $j)
                                    <option value="{{ $j->id }}"
                                        {{ request('jurusan_id') == $j->id ? 'selected' : '' }}>
                                        {{ $j->nama_jurusan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="col-md-2 col-lg-5 d-flex gap-2 justify-content-md-end">
                        <a href="{{ route('ujian.report', ['ujian' => $ujian->id]) }}" class="btn btn-light btn-sm px-3">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                        </a>
                        <button type="submit" class="btn btn-primary btn-sm px-4 fw-medium shadow-sm">
                            <i class="bi bi-search me-1"></i> Cari Data
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="table-container shadow-sm">
            <div class="table-responsive">
                <table id="table-kelas" class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>NISN</th>
                            <th>Nama Lengkap</th>
                            <th>Kelas</th>
                            <th>Nilai</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $i => $item)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $item->user->siswa->nis ?? '-' }}</td>
                                <td class="fw-bold">{{ $item->user->username }}</td>
                                <td>{{ $item->user->siswa->kelas->nama_kelas . '-' . $item->user->siswa->jurusan->nama_jurusan }}
                                </td>
                                <td>{{ $item->skor }}</td>
                                <td>
                                    @if ($item->skor >= 75)
                                        <span class="badge bg-success status-badge">Lulus</span>
                                    @else
                                        <span class="badge bg-danger status-badge">Remedial</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-light"><i class="bi bi-eye"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
