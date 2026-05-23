@extends('dashboard.index')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">Data Markup Nilai</h4>
            {{-- <a href="{{ route('siswa.exportCsv') }}" class="btn btn-success d-flex align-items-center gap-2">
                <i class="bx bx-export"></i> Generate CSV
            </a> --}}
        </div>

        <div class="card">

            <div class="card-header">
                <h6 class="mb-0">Daftar Markup Nilai</h6>
            </div>
            <div class="d-flex flex-column flex-md-row align-items-stretch align-items-md-center gap-2 px-4">



                {{-- FORM FILTER --}}
                <form action="{{ route('ujian.exportDataMarkup') }}" method="GET"
                    class="d-flex flex-column flex-md-row gap-2">

                    <select name="kelas_id" class="form-select">

                        <option value="">
                            Semua Kelas
                        </option>

                        @foreach ($kelas as $item)
                            <option value="{{ $item->id }}">
                                {{ $item->nama_kelas }}
                            </option>
                        @endforeach

                    </select>

                    <select name="jurusan_id" class="form-select">

                        <option value="">
                            Semua Jurusan
                        </option>

                        @foreach ($jurusan as $item)
                            <option value="{{ $item->id }}">
                                {{ $item->nama_jurusan }}
                            </option>
                        @endforeach

                    </select>

                    <button class="btn btn-success">
                        Download Filter
                    </button>

                </form>

            </div>
            <div class="card-body">

                <div class="table-responsive">
                    <table id="table-kelas" class="table table-hover text-center align-middle datatable">

                        <thead class="table-light">
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Nama Siswa</th>
                                <th class="text-center">NIS</th>
                                <th class="text-center">Kelas</th>
                                <th class="text-center">Jurusan</th>
                                <th class="text-center">Nilai Word</th>
                                <th class="text-center">Nilai Excel</th>
                                <th class="text-center">Markup Word & Excel</th>

                            </tr>
                        </thead>
                        <tbody>

                            @forelse ($siswas as $s)
                                @csrf
                                <tr>

                                    <td>{{ $loop->iteration }}</td>

                                    <td>{{ $s->nama_siswa }}</td>

                                    <td>{{ $s->nis }}</td>

                                    <td>
                                        <span class="badge bg-primary">
                                            {{ $s->kelas->nama_kelas ?? '-' }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="badge bg-success">
                                            {{ $s->jurusan->nama_jurusan ?? '-' }}
                                        </span>
                                    </td>

                                    {{-- NILAI WORD --}}
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $s->nilai_word ?? '-' }}
                                        </span>
                                    </td>

                                    {{-- NILAI EXCEL --}}
                                    <td>
                                        <span class="badge bg-warning">
                                            {{ $s->nilai_excel ?? '-' }}
                                        </span>
                                    </td>

                                    <td colspan="3">

                                        <form action="{{ route('ujian.markupnilai.simpan') }}" method="POST"
                                            class="d-flex gap-2">

                                            @csrf

                                            <input type="hidden" name="word_id" value="{{ $s->word_terbesar?->id }}">

                                            <input type="hidden" name="excel_id" value="{{ $s->excel_terbesar?->id }}">

                                            <input type="number" name="markup_word" class="form-control"
                                                placeholder="Markup Word" value="{{ $s->nilaiMarkupWord }}">

                                            <input type="number" name="markup_excel" class="form-control"
                                                placeholder="Markup Excel" value="{{ $s->nilaiMarkupExcel }}">

                                            <button type="submit" class="btn btn-primary btn-sm">
                                                Simpan
                                            </button>

                                        </form>

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
@endsection
