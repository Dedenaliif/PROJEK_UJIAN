@extends('dashboard.index')

@section('content')
    <section class="content-header">
        <div class="container-fluid px-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Data Ujian</h1>
                </div>

                @if (auth()->user()->role == 'penguji')
                    <div class="col-sm-6 text-right">
                        <a href="{{ route('ujian.create') }}" class="btn rounded-lg btn-primary btn-sm px-3 fw-bold">Buat
                            Ujian</a>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">

                <!-- Card Header -->
                <div class="card-header">
                    <h3 class="card-title">Daftar Ujian</h3>
                </div>

                <!-- Card Body -->
                <div class="card-body">
                    <table id="table-kelas" class="table table-bordered table-hover align-middle">
                        <thead class="table-dark text-center">
                            <tr>
                                <th>No</th>
                                <th class="text-start">Nama Ujian</th>
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
                                    <td class="text-center">{{ $key + 1 }}</td>

                                    {{-- NAMA --}}
                                    <td>
                                        <div class="fw-bold">{{ $item->judul }}</div>
                                        <small class="text-muted">{{ $item->deskripsi }}</small>
                                    </td>

                                    {{-- DURASI --}}
                                    <td class="text-center">
                                        <span class="badge bg-info">
                                            {{ $item->waktu }} Menit
                                        </span>
                                    </td>

                                    {{-- PERCOBAAN --}}
                                    <td class="text-center">
                                        <span class="badge bg-secondary">
                                            @if (auth()->user()->role == 'siswa')
                                                {{ $item->jumlah_percobaan }} / {{ $item->max_percobaan }}
                                            @else
                                                {{ $item->max_percobaan }} x
                                            @endif
                                        </span>
                                    </td>

                                    {{-- JADWAL --}}
                                    <td class="text-center">
                                        <small>
                                            {{ $item->waktu_mulai }} <br> s/d <br> {{ $item->waktu_selesai }}
                                        </small>
                                    </td>

                                    {{-- SOAL --}}
                                    <td class="text-center">
                                        @if ($item->tipe == 'word')
                                            <div>
                                                <span class="badge bg-primary">
                                                    Word: {{ $item->pertanyaans->count() ?? 0 }}/30
                                                </span>
                                            </div>
                                        @elseif($item->tipe == 'excel')
                                            <div class="mt-1">
                                                <span class="badge bg-success">
                                                    Excel: {{ $item->pertanyaans->count() ?? 0 }}/30
                                                </span>
                                            </div>
                                        @endif
                                    </td>

                                    {{-- AKSI --}}
                                    <td class="text-center">

                                        <div class="d-flex justify-content-center gap-2">

                                            @if (auth()->user()->role == 'penguji')
                                                <a href="{{ route('ujian.report', $item->id) }}"
                                                    class="btn btn-info btn-sm px-3 fw-bold">
                                                    Report
                                                </a>
                                                @if ($item->tipe == 'word')
                                                    <a href="{{ route('soal.create', ['ujian' => $item->id, 'tipe' => 'word']) }}"
                                                        id="btnWord" class="btn btn-primary px-4 m-2">
                                                        Word
                                                    </a>
                                                @elseif($item->tipe == 'excel')
                                                    <a href="{{ route('soal.create', ['ujian' => $item->id, 'tipe' => 'excel']) }}"
                                                        id="btnExcel" class="btn btn-success px-4 m-2">
                                                        Excel
                                                    </a>
                                                @else
                                                    @if (auth()->user()->role == 'penguji')
                                                        <div class="col-sm-6 text-right">
                                                            <a href="{{ route('ujian.create') }}"
                                                                class="btn btn-primary btn-sm px-3 fw-bold">
                                                                Buat Ujian
                                                            </a>
                                                        </div>
                                                    @endif
                                                    {{-- <span class="badge badge-secondary">No Action</span> --}}
                                                @endif
                                            @endif

                                            @if (auth()->user()->role == 'siswa')
                                                @if ($item->nilai_terakhir >= 75)
                                                    <button class="btn btn-success px-3">Selesai</button>
                                                @elseif ($item->nilai_terakhir !== null)
                                                    <form action="{{ route('ujianstart.start', $item->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-warning px-3">
                                                            Coba Lagi
                                                        </button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('ujianstart.start', $item->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @if ($item->jumlah_percobaan >= $item->max_percobaan)
                                                            <button type="submit" disabled class="btn-sm btn-primary px-3">
                                                                Mulai
                                                            </button>
                                                        @else
                                                            <button type="submit" class="btn-sm btn-primary px-3">
                                                                Mulai
                                                            </button>
                                                        @endif
                                                    </form>
                                                    <a class="btn-sm btn-secondary"
                                                        href="{{ route('ujian.history', $item->id) }}">History</a>
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

    </section>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        setInterval(function() {

            $.get("{{ route('ujian.cekStatus') }}", function(res) {

                if (res.redirect) {
                    window.location.href = res.redirect;
                }

            });

        }, 1000);
    </script>
@endsection
