@extends('dashboard.index')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        {{-- HEADER --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-3">

                {{-- KIRI --}}
                <div>
                    <h4 class="fw-bold mb-1">Data Ujian</h4>

                    @if (auth()->user()->role == 'penguji')
                        <small class="text-muted">Kelola dan pantau ujian</small>
                    @else
                        <small class="text-muted">Daftar ujian tersedia</small>
                    @endif
                </div>

                {{-- KHUSUS PENGUJI --}}
                @if (auth()->user()->role == 'penguji')
                    <div class="d-flex flex-column flex-md-row align-items-stretch align-items-md-center gap-2">

                        {{-- FILTER REPORT --}}
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

                            <select name="sesi_id" class="form-select w-auto">
                                <option value="">Semua Sesi</option>

                                @foreach ($sesis as $item)
                                    <option value="{{ $item->id }}"
                                        {{ request('sesi_id') == $item->id ? 'selected' : '' }}>
                                        Sesi {{ $item->no_sesi }}
                                    </option>
                                @endforeach
                            </select>

                            <button formaction="{{ route('ujian.exportSemuaNilai') }}" class="btn btn-success px-3">
                                Download Report
                            </button>

                            <button type="reset" class="btn btn-outline-secondary px-3"">Reset</button>

                        </form>

                        <a href="{{ route('ujian.create') }}" class="btn btn-primary px-4">
                            <i class="bx bx-plus"></i> Buat Ujian
                        </a>

                    </div>
                @endif

            </div>
        </div>
        {{-- NOTIFIKASI DOWNLOAD SERTIFIKAT KHUSUS SISWA --}}
        @if (auth()->user()->role == 'penguji')
            <a href="{{ route('downloadsemua') }}" class="btn btn-primary d-flex align-items-center gap-2 shadow-sm mb-4">
                <i class="bx bx-file-blank fs-4"></i> Download Semua Sertifikat (.ZIP)
            </a>
        @endif
        @if (auth()->user()->role == 'siswa')
            @if (isset($bisaDownloadSertifikat) && $bisaDownloadSertifikat)
                <div
                    class="alert alert-success d-flex justify-content-between align-items-center p-4 mb-4 border-0 shadow-sm rounded-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar flex-shrink-0">
                            <span class="avatar-initial rounded bg-label-success p-2">
                                <i class="bx bx-award fs-3"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="alert-heading fw-bold mb-1 text-success">Selamat! Sertifikat Anda Tersedia</h5>
                            <p class="mb-0 text-muted small">Anda telah menyelesaikan seluruh rangkaian ujian dengan baik.
                                Silakan unduh sertifikat resmi Anda.</p>
                        </div>
                    </div>
                    <a href="{{ url('siswa/sertifikat/' . auth()->user()->siswa->id . '/download') }}" target="_blank"
                        class="btn btn-success px-4 shadow-sm d-flex align-items-center gap-2">
                        <i class="bx bx-download fs-5"></i> Unduh Sertifikat
                    </a>
                </div>
            @else
                <div
                    class="alert alert-light d-flex justify-content-between align-items-center p-4 mb-4 border border-dashed rounded-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar flex-shrink-0">
                            <span class="avatar-initial rounded bg-label-secondary p-2">
                                <i class="bx bx-lock-alt fs-3"></i>
                            </span>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1 text-secondary">Sertifikat Masih Terkunci</h6>
                            <p class="mb-1 text-muted small">Selesaikan kedua ujian (Word & Excel) untuk membuka akses unduh
                                sertifikat. <span class="fw-bold">Minimal nilai yang didapat > 75</span> </p>
                        </div>
                    </div>
                    <button disabled class="btn btn-secondary px-4 text-white disabled">
                        Terkunci <i class="bx bx-lock-alt ms-1 small"></i>
                    </button>
                </div>
            @endif
        @endif

        {{-- TABLE --}}
        <div class="card border-0 shadow-sm rounded-3">

            <div class="card-header bg-white py-3 px-4 border-bottom">
                <h6 class="fw-bold mb-0">Daftar Ujian</h6>
            </div>

            <div class="card-body p-3">

                <div class="table-responsive">

                    <table class="table align-middle datatable">

                        <thead class="table-light text-center">
                            <tr>
                                <th>No</th>
                                <th>Ujian</th>
                                <th>Durasi</th>
                                <th>Percobaan</th>
                                <th>Sesi</th>
                                <th>Soal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach ($ujians as $key => $item)
                                <tr>

                                    <td class="text-center">{{ $key + 1 }}</td>

                                    <td>
                                        <div class="fw-semibold">{{ $item->judul }}</div>
                                        <small class="text-muted">{{ $item->deskripsi }}</small>
                                    </td>

                                    <td class="text-center">
                                        <span class="badge bg-label-info">
                                            {{ $item->waktu }} Menit
                                        </span>
                                    </td>

                                    <td class="text-center">
                                        <span class="badge bg-label-secondary">
                                            @if (auth()->user()->role == 'siswa')
                                                {{ $item->jumlah_percobaan }}/{{ $item->max_percobaan }}
                                            @else
                                                {{ $item->max_percobaan }}x
                                            @endif
                                        </span>
                                    </td>

                                    {{-- SESI --}}
                                    <td class="text-center">

                                        @if (auth()->user()->role == 'penguji')
                                            @include('ujian.modalsesi')
                                        @endif

                                        @if (auth()->user()->role == 'siswa')
                                            @if (isset($sesiSaya[$item->id]))
                                                <span class="badge bg-primary">
                                                    {{ $sesiSaya[$item->id]->no_sesi }}
                                                </span>
                                            @else
                                                <span class="badge bg-warning">
                                                    Belum ada sesi
                                                </span>
                                            @endif
                                        @endif

                                    </td>

                                    {{-- SOAL --}}
                                    <td class="text-center">
                                        @if ($item->tipe == 'word')
                                            <span class="badge bg-label-primary">
                                                Word ({{ $item->pertanyaans->count() }})
                                            </span>
                                        @elseif($item->tipe == 'excel')
                                            <span class="badge bg-label-success">
                                                Excel ({{ $item->pertanyaans->count() }})
                                            </span>
                                        @endif
                                    </td>

                                    {{-- AKSI --}}
                                    <td class="text-center">

                                        {{-- PENGUJI --}}
                                        @if (auth()->user()->role == 'penguji')
                                            <a href="{{ route('ujian.report', $item->id) }}"
                                                class="btn btn-sm btn-info m-3">
                                                Report
                                            </a>

                                            @if ($item->tipe == 'word')
                                                <a href="{{ route('soal.create', ['ujian' => $item->id, 'tipe' => 'word']) }}"
                                                    class="btn btn-sm btn-primary">
                                                    Word
                                                </a>
                                            @else
                                                <a href="{{ route('soal.create', ['ujian' => $item->id, 'tipe' => 'excel']) }}"
                                                    class="btn btn-sm btn-success">
                                                    Excel
                                                </a>
                                            @endif
                                        @endif


                                        {{-- SISWA --}}
                                        @if (auth()->user()->role == 'siswa')
                                            @if ($item->nilai_terakhir >= 75)
                                                <span class="badge bg-success">
                                                    Lulus
                                                </span>
                                            @elseif($item->nilai_terakhir !== null)
                                                <form action="{{ route('ujianstart.start', $item->id) }}" method="POST">
                                                    @csrf
                                                    <button class="btn btn-warning btn-sm">
                                                        Coba Lagi
                                                    </button>
                                                </form>

                                                <a href="{{ route('ujian.history', $item->id) }}"
                                                    class="btn btn-outline-secondary btn-sm">
                                                    History
                                                </a>
                                            @else
                                                <form action="{{ route('ujianstart.start', $item->id) }}" method="POST">
                                                    @csrf
                                                    <button class="btn btn-primary btn-sm"
                                                        {{ !isset($sesiSaya[$item->id]) ? 'disabled' : '' }}
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

                                    </td>

                                </tr>
                            @endforeach

                        </tbody>

                    </table>

                </div>
            </div>
        </div>
    </div>


    {{-- AUTO CHECK STATUS SISWA --}}
    @if (auth()->user()->role == 'siswa')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
