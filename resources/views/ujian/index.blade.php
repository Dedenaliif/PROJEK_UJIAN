@extends('dashboard.index')

@section('content')
    <section class="content-header">
        <div class="container-fluid px-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Data Ujian</h1>
                </div>

                <div class="col-sm-6 text-right">
                    <a href="{{ route('ujian.create') }}" class="btn rounded-lg btn-primary btn-sm px-3 fw-bold">Buat
                        Ujian</a>
                </div>
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
                                    {{ $item->max_percobaan }}x
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
                                <div>
                                    <span class="badge bg-primary">
                                         {{ $item->total ?? 0 }}/30
                                    </span>
                                </div>
                            </td>

                            {{-- AKSI --}}
                            <td class="text-center">

                                <div class="d-flex justify-content-center gap-2">

                                   <a href="{{ route('soal.create', $item->id) }}"
                                    class="btn btn-sm btn-success m-2">
                                    Buat Soal
                                    </a>

                                    <a href="{{ route('ujianstart.start', $item->id) }}"
                                        class="btn btn-sm btn-primary m-2">
                                        Mulai
                                    </a>

                                </div>

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>

            </div>
        </div>
        <!-- Modal Pilih Tipe Soal -->
          <div class="modal fade" id="modalPilihSoal">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content text-center p-4">

                    <h5 class="mb-3">Pilih Jenis Soal</h5>

                    <div class="d-flex justify-content-center gap-3 mt-3">
                        <button id="btnWord" class="btn btn-primary px-4 m-2">
                            Word
                        </button>

                        <button id="btnExcel" class="btn btn-success px-4 m-2">
                            Excel
                        </button>
                    </div>

                </div>
            </div>
        </div>

    <script>
        let ujianId = null;

        document.querySelectorAll('.btn-pilih-soal').forEach(btn => {
            btn.addEventListener('click', function() {
                ujianId = this.dataset.id;
            });
        });

      document.getElementById('btnWord').onclick = function() {
        if (!ujianId) return alert('Ujian tidak ditemukan');
        window.location.href = `/soal/${ujianId}?tipe=word`;
        };

        document.getElementById('btnExcel').onclick = function() {
            if (!ujianId) return alert('Ujian tidak ditemukan');
            window.location.href = `/soal/${ujianId}?tipe=excel`;
        };
    </script>

    </section>
@endsection
