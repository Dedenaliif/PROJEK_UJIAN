@extends('dashboard.index')

@section('content')
    <div class="container-fluid py-4 col-md-12 px-4" >
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4">Monitoring Ujian: Kemampuan Test Word</h2>
            <span class="badge bg-primary px-3 py-2">Ujian Word</span>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">Total Siswa</h6>
                        <h2 class="mb-0">40</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm border-start border-primary border-4">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">Sedang Mengerjakan</h6>
                        <h2 class="mb-0 text-primary">32</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm border-start border-success border-4">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">Selesai</h6>
                        <h2 class="mb-0 text-success">5</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm border-start border-danger border-4">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">Belum Hadir</h6>
                        <h2 class="mb-0 text-danger">3</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="mb-0 font-weight-bold">Daftar Status Siswa</h6>
                    </div>
                    <div class="col-auto">
                        <input type="text" class="form-control form-control-sm" placeholder="Cari Nama Siswa...">
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No. Peserta</th>
                            <th>Nama Siswa</th>
                            <th>Progress</th>
                            <th>Status</th>
                            <th>Durasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>2024-001</td>
                            <td>Andi Wijaya</td>
                            <td style="width: 200px;">
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-primary" style="width: 75%"></div>
                                </div>
                                <small class="text-muted">75% (30/40 Soal)</small>
                            </td>
                            <td><span class="badge rounded-pill bg-primary">Mengerjakan</span></td>
                            <td>01:15:20</td>
                            <td><button class="btn btn-sm btn-outline-danger">Force Logout</button></td>
                        </tr>
                        <tr>
                            <td>2024-002</td>
                            <td>Budi Santoso</td>
                            <td>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-success" style="width: 100%"></div>
                                </div>
                                <small class="text-muted">Lengkap</small>
                            </td>
                            <td><span class="badge rounded-pill bg-success">Selesai</span></td>
                            <td>00:55:10</td>
                            <td><button class="btn btn-sm btn-light" disabled>Sudah Selesai</button></td>
                        </tr>
                        <tr>
                            <td>2024-003</td>
                            <td>Citra Lestari</td>
                            <td>-</td>
                            <td><span class="badge rounded-pill bg-danger">Offline</span></td>
                            <td>--:--</td>
                            <td><button class="btn btn-sm btn-outline-primary">Reset Login</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
