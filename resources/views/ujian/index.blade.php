@extends('dashboard.index')

@section('content')

<section class="content-header mb-3">
    <div class="container-fluid px-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap">

            <div>
                <h3 class="fw-bold mb-1">Data Ujian</h3>
                <small class="text-muted">Kelola dan pantau ujian</small>
            </div>

            @if (auth()->user()->role == 'penguji')
                <a href="{{ route('ujian.create') }}"
                    class="btn btn-primary shadow-sm px-4">
                    + Buat Ujian
                </a>
            @endif

        </div>
    </div>
</section>

<section class="content">
<div class="container-fluid">

    <div class="card border-0 shadow-sm">

        {{-- HEADER --}}
        <div class="card-header bg-white border-0">
            <h6 class="fw-bold mb-0">Daftar Ujian</h6>
        </div>

        {{-- BODY --}}
        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light text-center">
                        <tr>
                            <th>No</th>
                            <th class="text-start">Ujian</th>
                            <th>Durasi</th>
                            <th>Percobaan</th>
                            <th>Jadwal</th>
                            <th>Soal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($ujians as $key => $item)
                        <tr>

                            {{-- NO --}}
                            <td class="text-center fw-semibold">
                                {{ $key + 1 }}
                            </td>

                            {{-- NAMA --}}
                            <td class="col-2">
                                <div class="fw-bold text-dark">
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
                                    @if (auth()->user()->role == 'siswa')
                                        {{ $item->jumlah_percobaan }} / {{ $item->max_percobaan }}
                                    @else
                                        {{ $item->max_percobaan }} x
                                    @endif
                                </span>
                            </td>

                            {{-- JADWAL --}}
                            <td class="text-center">
                                <small class="text-muted">
                                    {{ $item->waktu_mulai }} <br>
                                    <span class="text-dark fw-semibold">s/d</span> <br>
                                    {{ $item->waktu_selesai }}
                                </small>
                            </td>

                            {{-- SOAL --}}
                            <td class="text-center">
                                @if ($item->tipe == 'word')
                                    <span class="badge bg-primary px-3 py-2">
                                        📄 Word ({{ $item->pertanyaans->count() ?? 0 }})
                                    </span>
                                @elseif($item->tipe == 'excel')
                                    <span class="badge bg-success px-3 py-2">
                                        📊 Excel ({{ $item->pertanyaans->count() ?? 0 }})
                                    </span>
                                @else
                                    <span class="badge bg-dark">-</span>
                                @endif
                            </td>

                            {{-- AKSI --}}
                            <td class="text-center">

                                <div class="d-flex flex-wrap justify-content-center gap-2">

                                    {{-- ===== PENGUJI ===== --}}
                                    @if (auth()->user()->role == 'penguji')

                                        <a href="{{ route('ujian.report', $item->id) }}"
                                            class="btn btn-outline-info btn-sm px-3">
                                            Report
                                        </a>

                                        @if ($item->tipe == 'word')
                                            <a href="{{ route('soal.create', ['ujian' => $item->id, 'tipe' => 'word']) }}"
                                                class="btn btn-primary btn-sm px-3">
                                                Word
                                            </a>
                                        @elseif($item->tipe == 'excel')
                                            <a href="{{ route('soal.create', ['ujian' => $item->id, 'tipe' => 'excel']) }}"
                                                class="btn btn-success btn-sm px-3">
                                                Excel
                                            </a>
                                        @endif

                                    @endif

                                    {{-- ===== SISWA ===== --}}
                                    @if (auth()->user()->role == 'siswa')

                                        @if ($item->nilai_terakhir >= 75)
                                            <span class="badge bg-success px-3 py-2">
                                                ✔ Lulus
                                            </span>

                                        @elseif ($item->nilai_terakhir !== null)
                                            <form action="{{ route('ujianstart.start', $item->id) }}" method="POST">
                                                @csrf
                                                <button class="btn btn-warning btn-sm px-3">
                                                    Coba Lagi
                                                </button>
                                            </form>

                                        @else
                                            <form action="{{ route('ujianstart.start', $item->id) }}" method="POST">
                                                @csrf
                                                <button
                                                    class="btn btn-primary btn-sm px-3"
                                                    {{ $item->jumlah_percobaan >= $item->max_percobaan ? 'disabled' : '' }}>
                                                    Mulai
                                                </button>
                                            </form>

                                            <a href="{{ route('ujian.history', $item->id) }}"
                                                class="btn btn-outline-secondary btn-sm px-3">
                                                History
                                            </a>
                                        @endif

                                    @endif

                                </div>

                            </td>

                        </tr>
                        @endforeach

                    </tbody>

                </table>
            </div>

        </div>

    </div>

</div>
</section>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@if(auth()->user()->role == 'siswa')
<script>
setInterval(function() {

    $.get("{{ route('ujian.cekStatus') }}", function(res) {

        if (res.redirect) {
            window.location.href = res.redirect;
        }

    });

}, 3000);
</script>
@endif

@endsection
