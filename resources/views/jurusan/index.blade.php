@extends('dashboard.index')

@section('content')
    <section class="content-header">
        <div class="container px-2">
            <div class="d-flex justify-content-between align-items-center  mb-2">
                <div class="">
                    <h3>Manajemen Jurusan</h3>
                </div>
                <div class="mb-2">
                    <!-- Tombol tambah -->
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-tambah-jurusan">
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
                    <table id="table-kelas" class="table table-bordered table-striped">
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
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $j->nama_jurusan }}</td>
                                    <td class="text-center">
                                        <button class="btn btn-warning btn-sm btn-edit_jurusan"
                                            data-id="{{ $j->id }}" data-nama_jurusan="{{ $j->nama_jurusan }}"
                                            data-bs-toggle="modal" data-bs-target="#modal-edit">Edit</button>
                                        <button class="btn btn-sm btn-danger" onclick="confirmDelete({{ $j->id }})">
                                            Hapus
                                        </button>

                                        <form id="delete-form-{{ $j->id }}"
                                            action="{{ route('jurusan.destroy', $j->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                        </form>
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


    @include('jurusan.modaledit')
    @include('jurusan.modaldelete')
    @include('jurusan.modalcreate')

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const editButtons = document.querySelectorAll('.btn-edit_jurusan');

            editButtons.forEach(btn => {
                btn.addEventListener('click', function() {

                    let id = this.dataset.id;
                    let nama = this.dataset.nama_jurusan;

                    document.getElementById('edit-nama_jurusan').value = nama;

                    document.getElementById('form-edit').action = `/admin/jurusan/${id}`;
                });
            });

        });
    </script>
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Yakin hapus?',
                text: "Data tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
@endsection
