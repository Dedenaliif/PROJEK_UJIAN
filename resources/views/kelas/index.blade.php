@extends('dashboard.index')

@section('content')
    <section class="content-header">
        <div class="container-fluid px-2">
            <div class="d-flex justify-content-between align-items-center">
                <div class="">
                    <h3>Manajemen Kelas</h3>
                </div>
                <div class="mb-2">
                    <!-- Tombol tambah -->
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-tambah-kelas">
                        <i class="fas fa-plus"></i> Tambah Kelas
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
                    <h3 class="card-title">Daftar Kelas</h3>
                </div>

                <!-- Card Body -->
                <div class="card-body">
                    <table id="table-kelas" class="table table-bordered table-striped">
                        <thead class="text-center">
                            <tr>
                                <th>No</th>
                                <th>Nama Kelas</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($kelas as $k)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $k->nama_kelas }}</td>
                                    <td class="text-center">
                                        <button class="btn btn-warning btn-sm btn-edit_kelas" data-id="{{ $k->id }}"
                                            data-nama_kelas="{{ $k->nama_kelas }}" data-bs-toggle="modal"
                                            data-bs-target="#modal-edit">Edit</button>
                                        <button class="btn btn-sm btn-danger" onclick="confirmDelete({{ $k->id }})">
                                            Hapus
                                        </button>

                                        <form id="delete-form-{{ $k->id }}"
                                            action="{{ route('kelas.destroy', $k->id) }}" method="POST">
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
    </div>

    @include('kelas.modaledit')
    @include('kelas.modaldelete')
    @include('kelas.modalcreate')

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const editButtons = document.querySelectorAll('.btn-edit_kelas');

            editButtons.forEach(btn => {
                btn.addEventListener('click', function() {

                    let id = this.dataset.id;
                    let nama = this.dataset.nama_kelas;

                    document.getElementById('edit-nama_kelas').value = nama;

                    document.getElementById('form-edit').action = `/admin/kelas/${id}`;
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
