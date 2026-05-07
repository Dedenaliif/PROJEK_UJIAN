@extends('dashboard.index')

@section('content')

<style>
    /* ===== MODAL ===== */
    .modal {
        z-index: 99999 !important;
    }

    .modal-backdrop {
        z-index: 99998 !important;
    }

    /* ===== CARD ===== */
    .stat-card {
        transition: .2s;
        border-radius: 16px;
    }

    .stat-card:hover {
        transform: translateY(-3px);
    }

    .stat-card.success {
        border-left: 4px solid #28c76f;
    }

    .stat-card.warning {
        border-left: 4px solid #ff9f43;
    }

    .stat-card.info {
        border-left: 4px solid #00cfe8;
    }

    .card {
        border-radius: 16px;
    }

    /* ===== TABLE ===== */
    .custom-table {
        width: 100%;
    }

    .custom-table th,
    .custom-table td {
        vertical-align: middle;
        padding: 14px 12px;
        white-space: nowrap;
    }

    /* ===== MOBILE ===== */
    @media (max-width: 768px) {

        .container-xxl {
            padding-left: 12px !important;
            padding-right: 12px !important;
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .custom-table {
            min-width: 850px;
        }

        .mobile-stack {
            flex-direction: column !important;
        }

        .mobile-stack .btn {
            width: 100%;
        }

        .modal-dialog {
            margin: 85px 12px 20px 12px !important;
        }

        .modal-content {
            border-radius: 18px;
        }
    }
</style>

<div class="container-xxl flex-grow-1 container-p-y">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">

        <div>
            <h4 class="fw-bold mb-1">
                Laporan Ujian
            </h4>

            <small class="text-muted">
                {{ $ujian->judul }} • Tahun 2026
            </small>
        </div>

        <a href="{{ route('ujian.export', array_merge(['ujian' => $ujian->id], request()->all())) }}"
            class="btn btn-success px-4 shadow-sm">

            <i class="bx bx-export me-1"></i>
            Export Excel

        </a>

    </div>

    {{-- STATISTIK --}}
    <div class="row g-3 mb-4">

        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm stat-card p-3">
                <small class="text-muted">TOTAL SISWA</small>
                <h3 class="fw-bold mt-1 mb-0">{{ $totalSiswa }}</h3>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm stat-card success p-3">
                <small class="text-muted">LULUS</small>
                <h3 class="fw-bold mt-1 mb-0 text-success">
                    {{ $lulus }}
                </h3>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm stat-card warning p-3">
                <small class="text-muted">REMEDIAL</small>
                <h3 class="fw-bold mt-1 mb-0 text-warning">
                    {{ $remedial }}
                </h3>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm stat-card info p-3">
                <small class="text-muted">RATA-RATA</small>
                <h3 class="fw-bold mt-1 mb-0 text-info">
                    {{ number_format($rata, 1) }}
                </h3>
            </div>
        </div>

    </div>

    {{-- FILTER --}}
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body p-4">

            <form class="row g-3 align-items-end" method="GET">

                <div class="col-md-3">
                    <label class="form-label fw-semibold small">
                        Status
                    </label>

                    <select name="status" class="form-select">
                        <option value="">Semua</option>

                        <option value="lulus"
                            {{ request('status') == 'lulus' ? 'selected' : '' }}>
                            Lulus
                        </option>

                        <option value="remedial"
                            {{ request('status') == 'remedial' ? 'selected' : '' }}>
                            Remedial
                        </option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold small">
                        Kelas
                    </label>

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
                    <label class="form-label fw-semibold small">
                        Jurusan
                    </label>

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

                <div class="col-md-3">

                    <div class="d-flex gap-2 mobile-stack">

                        <button type="submit" class="btn btn-primary">
                            Cari
                        </button>

                        <a href="{{ route('ujian.report', ['ujian' => $ujian->id]) }}"
                            class="btn btn-outline-secondary">
                            Reset
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>

    {{-- TABLE --}}
    <div class="card shadow-sm border-0">

        <div class="card-body p-0">

            <div class="table-responsive">

                <div class="card shadow-sm border-0">

                    <div class="card-body">

                        <div class="table-responsive">

                            <table id="table-report"
                                class="table table-hover align-middle custom-table mb-0 datatable">

                                <thead class="table-light text-center">

                                    <tr>
                                        <th style="width:70px">No</th>
                                        <th>NIS</th>
                                        <th class="text-start">Nama</th>
                                        <th>Kelas</th>
                                        <th>Percobaan</th>
                                        <th>Nilai</th>
                                        <th>Status</th>
                                        <th style="width:90px">Aksi</th>
                                    </tr>

                                </thead>

                                <tbody>

                                    @forelse ($data as $i => $item)

                                        <tr>

                                            <td class="text-center">
                                                {{ $i + 1 }}
                                            </td>

                                            <td class="text-center">
                                                {{ $item->user->siswa->nis ?? '-' }}
                                            </td>

                                            <td class="text-start fw-semibold">
                                                {{ $item->user->username }}
                                            </td>

                                            <td class="text-center">

                                                {{ $item->user->siswa->kelas->nama_kelas ?? '-' }}

                                                <br>

                                                <small class="text-muted">
                                                    {{ $item->user->siswa->jurusan->nama_jurusan ?? '' }}
                                                </small>

                                            </td>

                                            <td class="text-center">
                                                ke-{{ $item->percobaan_terbaik }}
                                                /{{ $item->total_percobaan }}
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

                                                <button type="button"
                                                    class="btn btn-sm btn-outline-primary btn-detail"

                                                    data-bs-toggle="modal"
                                                    data-bs-target="#detailModal"

                                                    data-nama="{{ $item->user->siswa->nama_siswa ?? '-' }}"
                                                    data-nilai="{{ $item->nilai }}"
                                                    data-percobaan="{{ $item->total_percobaan }}"
                                                    data-terbaik="{{ $item->percobaan_terbaik }}"
                                                    data-status="{{ $item->nilai >= 75 ? 'LULUS' : 'REMEDIAL' }}"
                                                    data-riwayat='@json($item->riwayat_percobaan)'>

                                                    <i class="bx bx-show"></i>

                                                </button>

                                            </td>

                                        </tr>

                                    @empty

                                        

                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>
            </div>

        </div>

    </div>

</div>

{{-- MODAL DETAIL --}}
@include('ujian.modaldetail')

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ===== DETAIL MODAL =====
    const buttons = document.querySelectorAll('.btn-detail');

    buttons.forEach(function (btn) {

        btn.addEventListener('click', function () {

            const nama = this.dataset.nama;
            const nilai = this.dataset.nilai;
            const percobaan = this.dataset.percobaan;
            const terbaik = this.dataset.terbaik;
            const status = this.dataset.status;

            let riwayat = [];

            try {
                riwayat = JSON.parse(this.dataset.riwayat);
            } catch (e) {
                riwayat = [];
            }

            document.getElementById('modalNama').innerText = nama;
            document.getElementById('modalNilai').innerText = nilai;
            document.getElementById('modalPercobaan').innerText = percobaan + 'x';
            document.getElementById('modalTerbaik').innerText =
                'Percobaan ke-' + terbaik;

            document.getElementById('modalStatus').innerHTML =
                status === 'LULUS'
                ? '<span class="badge bg-success">LULUS</span>'
                : '<span class="badge bg-danger">REMEDIAL</span>';

            let html = '';

            if (riwayat.length > 0) {

                riwayat.forEach(function(item) {

                    html += `
                        <tr>
                            <td>Percobaan ke-${item.percobaan}</td>
                            <td>${item.nilai}</td>
                            <td>
                                ${
                                    item.status === 'LULUS'
                                    ? '<span class="badge bg-success">LULUS</span>'
                                    : '<span class="badge bg-danger">REMEDIAL</span>'
                                }
                            </td>
                        </tr>
                    `;
                });

            } else {

                html = `
                    <tr>
                        <td colspan="3" class="text-center text-muted">
                            Tidak ada riwayat
                        </td>
                    </tr>
                `;
            }

            document.getElementById('modalRiwayat').innerHTML = html;

        });

    });

    // ===== DATATABLE =====
    $('#table-report').DataTable({
        responsive: false,
        autoWidth: false,
        scrollX: true
    });

});


</script>

@endsection
