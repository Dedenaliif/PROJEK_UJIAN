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
                    <table id="table-kelas" class="table table-bordered table-striped">
                        <thead class="text-center">
                            <tr>
                                <th>No</th>
                                <th class="ps-4">Nama Ujian</th>
                                <th>Deskripsi</th>
                                <th>Waktu Ujian</th>
                                <th>Max Percobaan</th>
                                <th>Waktu Mulai</th>
                                <th>Waktu Selesai</th>
                                <th>Soal</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ujians as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td class="ps-4">
                                        <div class="fw-bold">{{ $item->judul }}</div>
                                    </td>
                                    <td>{{ $item->deskripsi }}</td>
                                    <td>{{ $item->waktu }} Menit</td>
                                    <td>{{ $item->max_percobaan }}</td>
                                    <td>{{ $item->waktu_mulai }}</td>
                                    <td>{{ $item->waktu_selesai }}</td>
                                    <td><a href="" class="p-2 bg-secondary">{{ $item->pertanyaans->count() }}</a>
                                    </td>
                                    <td class="text-center ">
                                        <button type="button" class="btn btn-success btn-tambah-soal btn-sm"
                                            data-id="{{ $item->id }}" data-judul="{{ $item->judul }}"
                                            data-toggle="modal" data-target="#modal-tambah-pertanyaan">
                                            Buat Soal
                                        </button>
                                    </td>
                                    <td class="text-center ">
                                        <a href="{{ route('ujianstart.start', $item->id) }}" class="btn btn-success">
                                            Mulai
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </section>

    @include('ujian.createsoal')
    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif
@endsection
