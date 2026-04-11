@extends('dashboard.index')

@section('content')
    <section class="content-header">
        <div class="container-fluid px-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Data Jurusan</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <!-- Tombol tambah -->
                    <button class="btn btn-primary" data-toggle="modal" data-target="#modal-tambah-jurusan">
                        <i class="fas fa-plus"></i> Tambah Jurusan
                    </button>
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
                    <h3 class="card-title">Daftar Jurusan</h3>
                </div>

                <!-- Card Body -->
                <div class="card-body">
                    <table id="table-jurusan" class="table table-bordered table-striped">
                        <thead class="text-center">
                            <tr>
                                <th>No</th>
                                <th>Nama Jurusan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($jurusan as $j)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $j->nama_jurusan }}</td>
                                    <td class="text-center">
                                        <button class="btn btn-warning btn-sm btn-edit_jurusan" data-id="{{ $j->id }}"
                                            data-nama_jurusan="{{ $j->nama_jurusan }}" data-toggle="modal"
                                            data-target="#modal-edit">Edit</button>
                                        <button class="btn btn-danger btn-sm" data-toggle="modal"
                                            data-target="#modal-delete-{{ $j->id }}">Hapus</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Data kosong</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </section>
    </div>

    @include('jurusan.modaledit')
    @include('jurusan.modaldelete')
    @include('jurusan.modalcreate')
@endsection
