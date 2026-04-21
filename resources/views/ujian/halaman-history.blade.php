@extends('dashboard.index')

@section('content')
    <div class="container py-5 col-md-12 px-4">
        <div class="mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Riwayat Ujian</a></li>
                    <li class="breadcrumb-item active">Detail Percobaan</li>
                </ol>
            </nav>
            <h3 class="fw-bold">{{ $ujian->judul }}</h3>
            <p class="text-muted">Nama: <strong>{{ $siswa->nama_siswa }}</strong> | Batas Percobaan:
                {{ $ujian->max_percobaan }} kali</p>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" style="width: 100px;">Percobaan</th>
                            <th>Waktu Pengerjaan</th>
                            <th>Durasi</th>
                            <th>Skor Akhir</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($attempts as $item)
                            <tr class="{{ $item->skor == $maxScore ? 'best-attempt' : '' }}">
                                <td class="ps-4">
                                    <div class="attempt-number border-warning text-warning">{{ $item->percobaan_ke }}</div>
                                </td>
                                <td>
                                    <span
                                        class="d-block fw-bold">{{ \Carbon\Carbon::parse($item->waktu_mulai)->format('d M Y') }}</span>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($item->waktu_mulai)->format('H:i') }}
                                        -
                                        {{ \Carbon\Carbon::parse($item->waktu_mulai) ? \Carbon\Carbon::parse($item->waktu_selesai)->format('H:i') : '...' }}
                                        WIB</small>
                                </td>
                                <td>{{(int) \Carbon\Carbon::parse($item->waktu_mulai)->diffInMinutes($item->waktu_selesai) }} menit </td>
                                <td>
                                    <h5 class="mb-0 fw-bold text-success">{{ number_format($item->skor, 2) }}</h5>
                                    @if ($item->skor == $maxScore && $attempts->count() > 2)
                                        <small class="badge bg-warning text-dark" style="font-size: 0.7rem;">NILAI
                                            TERTINGGI</small>
                                    @endif
                                </td>
                                <td><span class=" {{ $item->skor >= 75 ? 'badge bg-success' : 'badge bg-danger' }}">{{ $item->skor >= 75 ? 'LULUS' : 'REMEDIAL' }}</span>
                                </td>
                                <td class="text-center">-
                                    {{-- <button class="btn btn-sm btn-dark px-3">Lihat Jawaban</button> --}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- <div class="mt-4 p-3 bg-white border rounded shadow-sm">
            <div class="d-flex align-items-center">
                <i class="bi bi-info-circle-fill text-primary me-3 fs-4"></i>
                <div>
                    <p class="mb-0 small text-muted">Sistem mengambil <strong>Nilai Tertinggi</strong> dari semua percobaan
                        sebagai nilai akhir rapor.</p>
                </div>
            </div>
        </div> --}}
    </div>
@endsection
