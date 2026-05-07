@extends('dashboard.index')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        {{-- HEADER --}}
        <div class="mb-4">
            <h4 class="fw-bold mb-1">{{ $ujian->judul }}</h4>
            <small class="text-muted">
                {{ $siswa->nama_siswa ?? '-' }} • Maksimal {{ $ujian->max_percobaan }}x percobaan
            </small>
        </div>

        {{-- CARD FULL WIDTH --}}
        <div class="card shadow-sm border-0 rounded-4">

            <div class="card-header bg-white border-0 py-3 px-4">
                <h6 class="mb-0 fw-semibold">Riwayat Percobaan</h6>
            </div>

            <div class="card-body px-4 pb-4">

                <div class="table-responsive">
                    <table class="table table-hover align-middle datatable">

                        <thead class="table-light text-center">
                            <tr>
                                <th>#</th>
                                <th class="text-start">Waktu</th>
                                <th>Durasi</th>
                                <th>Nilai</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($attempts as $item)
                                <tr class="{{ $item->nilai_fix == $maxScore ? 'table-warning' : '' }}">

                                    {{-- NO --}}
                                    <td class="text-center">
                                        <span class="badge bg-primary px-3 py-2">
                                            {{ $item->percobaan_ke }}
                                        </span>
                                    </td>

                                    {{-- WAKTU --}}
                                    <td>
                                        <div class="fw-semibold">
                                            {{ \Carbon\Carbon::parse($item->waktu_mulai)->format('d M Y') }}
                                        </div>
                                        <small class="text-muted">
                                            {{ \Carbon\Carbon::parse($item->waktu_mulai)->format('H:i') }}
                                            -
                                            {{ $item->waktu_selesai ? \Carbon\Carbon::parse($item->waktu_selesai)->format('H:i') : '--:--' }}
                                        </small>
                                    </td>

                                    {{-- DURASI --}}
                                    <td class="text-center">
                                        <span class="badge bg-info px-3 py-2">
                                            {{ number_format($item->durasi, 2) }} menit
                                        </span>
                                    </td>

                                    {{-- NILAI --}}
                                    <td class="text-center">
                                        <div class="fw-bold fs-4 text-success">
                                            {{ $item->nilai_fix }}
                                        </div>

                                        <div class="progress mt-1" style="height:6px;">
                                            <div class="progress-bar bg-success" style="width: {{ $item->nilai_fix }}%">
                                            </div>
                                        </div>

                                        @if ($item->nilai_fix == $maxScore && $attempts->count() > 1)
                                            <small class="badge bg-warning text-dark mt-1">
                                                ⭐ Tertinggi
                                            </small>
                                        @endif
                                    </td>

                                    {{-- STATUS --}}
                                    <td class="text-center">
                                        <span
                                            class="badge px-3 py-2
                                {{ $item->nilai_fix >= 75 ? 'bg-success' : 'bg-danger' }}">
                                            {{ $item->nilai_fix >= 75 ? 'LULUS' : 'REMEDIAL' }}
                                        </span>
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

    <style>
        .card {
            border-radius: 16px;
        }

        .table td,
        .table th {
            padding: 14px;
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background: #f4f8ff;
            transform: scale(1.01);
            transition: 0.2s;
        }

        .badge {
            border-radius: 8px;
        }
    </style>
@endsection
