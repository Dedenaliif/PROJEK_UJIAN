@extends('dashboard.index')

@section('content')

<section class="content-header">
    <div class="container-fluid px-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h3 class="fw-bold mb-1">Monitoring Ujian</h3>
                <small class="text-muted">Pantau seluruh ujian yang tersedia</small>
            </div>
        </div>
    </div>
</section>

<section class="content">
<div class="container-fluid">

    <div class="card shadow-sm border-0">

        {{-- HEADER --}}
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold">Daftar Ujian</h5>
        </div>

        {{-- BODY --}}
        <div class="card-body p-0">
            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-dark text-white text-center">
                        <tr>
                            <th width="50">#</th>
                            <th class="text-start">Ujian</th>
                            <th>Durasi</th>
                            <th>Percobaan</th>
                            <th>Jadwal</th>
                            <th>Soal</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($ujians as $key => $item)
                        <tr>

                            {{-- NO --}}
                            <td class="text-center fw-bold">
                                {{ $key + 1 }}
                            </td>

                            {{-- NAMA --}}
                            <td>
                                <div class="fw-semibold text-dark">
                                    {{ $item->judul }}
                                </div>
                                <small class="text-muted">
                                    {{ $item->deskripsi }}
                                </small>
                            </td>

                            {{-- DURASI --}}
                            <td class="text-center">
                                <span class="badge bg-info px-3 py-2">
                                    ⏱ {{ $item->waktu }} Menit
                                </span>
                            </td>

                            {{-- PERCOBAAN --}}
                            <td class="text-center">
                                <span class="badge bg-secondary px-3 py-2">
                                    {{ $item->max_percobaan }}x
                                </span>
                            </td>

                            {{-- JADWAL --}}
                            <td class="text-center">
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($item->waktu_mulai)->format('d M H:i') }}
                                    <br>
                                    s/d
                                    <br>
                                    {{ \Carbon\Carbon::parse($item->waktu_selesai)->format('d M H:i') }}
                                </small>
                            </td>

                            {{-- SOAL --}}
                            <td class="text-center">
                                @if ($item->tipe == 'word')
                                    <span class="badge bg-primary px-3 py-2">
                                        📄 Word <br>
                                        {{ $item->pertanyaans->count() ?? 0 }}/30
                                    </span>
                                @elseif($item->tipe == 'excel')
                                    <span class="badge bg-success px-3 py-2">
                                        📊 Excel <br>
                                        {{ $item->pertanyaans->count() ?? 0 }}/30
                                    </span>
                                @else
                                    <span class="badge bg-light text-dark">
                                        -
                                    </span>
                                @endif
                            </td>

                            {{-- AKSI --}}
                            <td class="text-center">
                                @if ($item->tipe == 'word')
                                    <a href="{{ route('ujian.monitoring', $item->id) }}"
                                       class="btn btn-primary btn-sm px-3 fw-semibold">
                                        👁 Monitoring
                                    </a>
                                @elseif($item->tipe == 'excel')
                                    <a href="{{ route('ujian.monitoring', $item->id) }}"
                                       class="btn btn-success btn-sm px-3 fw-semibold">
                                        👁 Monitoring
                                    </a>
                                @else
                                    <span class="badge bg-secondary">No Action</span>
                                @endif
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                Belum ada data ujian
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>

            </div>
        </div>

    </div>

</div>
</section>

@endsection
