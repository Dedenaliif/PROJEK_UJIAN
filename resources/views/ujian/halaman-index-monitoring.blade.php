@extends('dashboard.index')

@section('content')

<div class="container-fluid ">

    {{-- HEADER --}}
    <div class="mb-4">
        <h3 class="fw-bold mb-1">📊 Monitoring Ujian</h3>
        <small class="text-muted">Pantau seluruh ujian yang tersedia</small>
    </div>

    {{-- CARD --}}
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body p-4">

            <div class="table-responsive">
                <table class="table align-middle table-hover datatable">

                    <thead class="table-light">
                        <tr class="text-center">
                            <th class="text-center">No</th>
                            <th class="text-center">Ujian</th>
                            <th class="text-center">Durasi</th>
                            <th class="text-center">Percobaan</th>
                            <th class="text-center">Jadwal</th>
                            <th class="text-center">Soal</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($ujians as $key => $item)
                        <tr>

                            {{-- NO --}}
                            <td class="text-center text-muted fw-semibold">
                                {{ $key + 1 }}
                            </td>

                            {{-- NAMA --}}
                            <td>
                                <div class="fw-semibold text-dark mb-1">
                                    {{ $item->judul }}
                                </div>
                                <small class="text-muted text-truncate d-block" style="max-width:250px;">
                                    {{ $item->deskripsi ?? '-' }}
                                </small>
                            </td>

                            {{-- DURASI --}}
                            <td class="text-center">
                                <div class="badge bg-light border text-dark px-3 py-2 w-100">
                                    ⏱ {{ $item->waktu }} mnt
                                </div>
                            </td>

                            {{-- PERCOBAAN --}}
                            <td class="text-center">
                                <div class="badge bg-secondary px-3 py-2 w-100">
                                    {{ $item->max_percobaan }}x
                                </div>
                            </td>

                            {{-- JADWAL --}}
                            <td class="text-center">
                                @if($item->waktu_mulai && $item->waktu_selesai)
                                    <div class="small text-muted">
                                        <div>{{ \Carbon\Carbon::parse($item->waktu_mulai)->format('d M H:i') }}</div>
                                        <div class="text-secondary">—</div>
                                        <div>{{ \Carbon\Carbon::parse($item->waktu_selesai)->format('d M H:i') }}</div>
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            {{-- SOAL --}}
                            <td class="text-center">
                                @php $totalSoal = $item->pertanyaans->count() ?? 0; @endphp

                                @if ($item->tipe == 'word')
                                    <div class="badge bg-primary-subtle text-primary px-3 py-2 w-100">
                                        📄 {{ $totalSoal }}/30
                                    </div>
                                @elseif($item->tipe == 'excel')
                                    <div class="badge bg-success-subtle text-success px-3 py-2 w-100">
                                        📊 {{ $totalSoal }}/30
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            {{-- AKSI --}}
                            <td class="text-center">
                                <a href="{{ route('ujian.monitoring', $item->id) }}"
                                   class="btn btn-sm w-100 fw-semibold
                                   {{ $item->tipe == 'excel' ? 'btn-success' : 'btn-primary' }}">
                                    👁 Monitoring
                                </a>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                Tidak ada data ujian
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>

    </div>

</div>

<style>
/* 🔥 SPACING UTAMA */
.table td, .table th {
    padding: 16px 12px;
    vertical-align: middle;
}

/* 🔥 BIAR TIDAK MEpet */
.table {
    border-collapse: separate;
    border-spacing: 0 8px;
}

/* 🔥 ROW STYLE */
.table tbody tr {
    background: #fff;
    border-radius: 12px;
    transition: 0.2s;
}

.table tbody tr:hover {
    background: #f8f9fa;
}

/* 🔥 BADGE FULL WIDTH */
.badge {
    border-radius: 10px;
    font-weight: 500;
}

/* 🔥 BUTTON */
.btn {
    border-radius: 10px;
    padding: 8px 12px;
}

/* 🔥 TEXT BIAR GA MELEBAR */
.text-truncate {
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
}
</style>

@endsection
