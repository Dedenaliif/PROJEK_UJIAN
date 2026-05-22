@extends('dashboard.index')

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Data Siswa</h4>
    </div>

    <div class="card">

        <div class="card-header">
            <h6 class="mb-0">Daftar Siswa</h6>
        </div>

        <div class="card-body">

            <div class="table-responsive">
                <table id="table-kelas" class="table table-hover text-center align-middle datatable">

                    <thead class="table-light">
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Nama Siswa</th>
                            <th class="text-center">NISN</th>
                            <th class="text-center">Kelas</th>
                            <th class="text-center">Jurusan</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($siswas as $s)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $s->nama_siswa }}</td>
                                <td>{{ $s->nis ?? '' }}</td>

                                <td>
                                    <span class="badge bg-primary">
                                        {{ $s->kelas->nama_kelas ?? '' }}
                                    </span>
                                </td>

                                <td>
                                    <span class="badge bg-success">
                                        {{ $s->jurusan->nama_jurusan ?? '' }}
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

@endsection
