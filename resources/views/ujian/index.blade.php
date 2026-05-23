@extends('dashboard.index')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        {{-- HEADER --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-3">

                <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-3">

                    {{-- KIRI --}}
                    <div>
                        <h4 class="fw-bold mb-1">Data Ujian</h4>
                        <small class="text-muted">Kelola dan pantau ujian</small>
                    </div>

                    {{-- KANAN --}}
                    <div class="d-flex flex-column flex-md-row align-items-stretch align-items-md-center gap-2">

                        {{-- FORM FILTER --}}
                        <form action="{{ route('ujian.exportSemuaNilai') }}" method="GET"
                            class="d-flex flex-column flex-md-row gap-2">

                            <select name="kelas_id" class="form-select w-auto">
                                <option value="">Semua Kelas</option>

                                @foreach ($kelas as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->nama_kelas }}
                                    </option>
                                @endforeach
                            </select>

                            <select name="jurusan_id" class="form-select w-auto">
                                <option value="">Semua Jurusan</option>

                                @foreach ($jurusan as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->nama_jurusan }}
                                    </option>
                                @endforeach
                            </select>

                            <button class="btn btn-success px-3">
                                Download Report
                            </button>

                        </form>

                        {{-- BUTTON --}}
                        <a href="{{ route('ujian.create') }}" class="btn btn-primary px-4">
                            <i class="bx bx-plus"></i> Buat Ujian
                        </a>

                    </div>

                </div>
            </div>
        </div>


        {{-- TABLE CARD --}}
        <div class="card border-0 shadow-sm rounded-3">

            <div class="card-header bg-white py-3 px-4 border-bottom">
                <h6 class="fw-bold mb-0">Daftar Ujian</h6>
            </div>

            <div class="card-body p-3">

                <div class="table-responsive">

                    <table class="table align-middle datatable">

                        <thead class="table-light text-center">
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Ujian</th>
                                <th class="text-center">Durasi</th>
                                <th class="text-center">Percobaan</th>
                                <th class="text-center">Sesi</th>
                                <th class="text-center">Soal</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach ($ujians as $key => $item)
                                <tr class="table-row-hover">

                                    <td class="text-center fw-semibold">
                                        {{ $key + 1 }}
                                    </td>

                                    <td>
                                        <div class="fw-semibold mb-1">
                                            {{ $item->judul }}
                                        </div>
                                        <small class="text-muted">
                                            {{ $item->deskripsi }}
                                        </small>
                                    </td>

                                    <td class="text-center">
                                        <span class="badge bg-label-info">
                                            ⏱ {{ $item->waktu }} Menit
                                        </span>
                                    </td>

                                    <td class="text-center">
                                        <span class="badge bg-label-secondary">
                                            @if (auth()->user()->role == 'siswa')
                                                {{ $item->jumlah_percobaan }} / {{ $item->max_percobaan }}
                                            @else
                                                {{ $item->max_percobaan }} x
                                            @endif
                                        </span>
                                    </td>

                                    <td class="text-center">
                                        @if (auth()->user()->role == 'penguji')
                                            @include('ujian.modalsesi')
                                        @endif

                                        @if (auth()->user()->role == 'siswa')
                                            @if (isset($sesiSaya[$item->id]))
                                                <div class="alert alert-primary mb-0 py-2 px-3">

                                                    <strong>
                                                        {{ $sesiSaya[$item->id]->no_sesi }}
                                                    </strong>

                                                    <br>

                                                    <small>
                                                        {{ $sesiSaya[$item->id]->jam }}
                                                    </small>

                                                </div>
                                            @else
                                                <div class="alert alert-warning text-center py-2 mb-0">
                                                    Belum mendapat sesi
                                                </div>
                                            @endif
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        @if ($item->tipe == 'word')
                                            <span class="badge bg-label-primary">
                                                📄 Word ({{ $item->pertanyaans->count() ?? 0 }})
                                            </span>
                                        @elseif($item->tipe == 'excel')
                                            <span class="badge bg-label-success">
                                                📊 Excel ({{ $item->pertanyaans->count() ?? 0 }})
                                            </span>
                                        @else
                                            <span class="badge bg-label-dark">-</span>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        <div class="d-flex flex-wrap justify-content-center gap-2">

                                            {{-- PENGUJI --}}
                                            @if (auth()->user()->role == 'penguji')
                                                <a href="{{ route('ujian.report', $item->id) }}"
                                                    class="btn btn-sm btn-outline-info">
                                                    Report
                                                </a>

                                                @if ($item->tipe == 'word')
                                                    <a href="{{ route('soal.create', ['ujian' => $item->id, 'tipe' => 'word']) }}"
                                                        class="btn btn-sm btn-primary">
                                                        Word
                                                    </a>
                                                @elseif($item->tipe == 'excel')
                                                    <a href="{{ route('soal.create', ['ujian' => $item->id, 'tipe' => 'excel']) }}"
                                                        class="btn btn-sm btn-success">
                                                        Excel
                                                    </a>
                                                @endif
                                            @endif

                                            {{-- SISWA --}}
                                            @if (auth()->user()->role == 'siswa')
                                                @if ($item->nilai_terakhir >= 75)
                                                    <span class="badge bg-success px-3 py-2">
                                                        ✔ Lulus
                                                    </span>
                                                @elseif ($item->nilai_terakhir !== null)
                                                    <form action="{{ route('ujianstart.start', $item->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        <button class="btn btn-warning btn-sm">
                                                            Coba Lagi
                                                        </button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('ujianstart.start', $item->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        <button class="btn btn-primary btn-sm"
                                                            {{ $item->jumlah_percobaan >= $item->max_percobaan ? 'disabled' : '' }}>
                                                            Mulai
                                                        </button>
                                                    </form>

                                                    <a href="{{ route('ujian.history', $item->id) }}"
                                                        class="btn btn-outline-secondary btn-sm">
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

    {{-- AUTO REDIRECT --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    @if (auth()->user()->role == 'siswa')
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
