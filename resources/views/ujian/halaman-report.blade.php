@extends('dashboard.index')

@section('content')
    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-0">Laporan Ujian</h3>
                <p class="text-muted">Periode 2026</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-secondary"><i class="bi bi-printer"></i> Cetak</button>
                <a href="{{ route('ujian.export', $ujian->id) }}" class="btn btn-excel"><i
                        class="bi bi-file-earmark-excel"></i> Export ke Excel</a>
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

        <div class="card mb-4">
            <div class="card-body">
                <form class="row g-3" method="GET">
                    <div class="col-md-2">
                        <label class="form-label small fw-bold">Status</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="">Semua Status</option>
                            <option value="lulus" {{ request('status') == 'lulus' ? 'selected' : '' }}>Lulus</option>
                            <option value="remedial" {{ request('status') == 'remedial' ? 'selected' : '' }}>Remedial
                            </option>
                        </select>
                    </div>

                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            Filter Data
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="table-container shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
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

            <nav class="d-flex justify-content-between align-items-center mt-3">
                <small class="text-muted">Menampilkan 1-10 dari 1,240 data</small>
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">Next</a></li>
                </ul>
            </nav>
        </div>
    </div>
@endsection
