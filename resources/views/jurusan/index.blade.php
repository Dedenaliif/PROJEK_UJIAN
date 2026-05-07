@extends('dashboard.index')

@section('content')

<style>
.modal {
    z-index: 99999 !important;
}

.modal-backdrop {
    z-index: 99998 !important;
}
</style>

<div class="container-xxl flex-grow-1 container-p-y">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h4 class="fw-bold mb-0">Manajemen Jurusan</h4>

        <button class="btn btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#modal-tambah-jurusan">

            <i class="bx bx-plus"></i>
        </button>

    </div>

    <div class="card">

        <div class="card-header">
            <h6 class="mb-0">Daftar Jurusan</h6>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table id="table-kelas"
                    class="table table-hover text-center align-middle datatable">

                    <thead class="table-light">
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Nama Jurusan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($jurusan as $j)
                            <tr>

                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    <span class="badge bg-success">
                                        {{ $j->nama_jurusan }}
                                    </span>
                                </td>

                                <td>

                                    <button class="btn btn-warning btn-sm btn-edit_jurusan m-1"
                                        data-id="{{ $j->id }}"
                                        data-nama_jurusan="{{ $j->nama_jurusan }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modal-edit">

                                        <i class="bx bx-edit"></i>
                                    </button>

                                    <button class="btn btn-danger btn-sm m-1"
                                        onclick="confirmDelete({{ $j->id }})">

                                        <i class="bx bx-trash"></i>
                                    </button>

                                    <form id="delete-form-{{ $j->id }}"
                                        action="{{ route('jurusan.destroy', $j->id) }}"
                                        method="POST">

                                        @csrf
                                        @method('DELETE')

                                    </form>

                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-muted">
                                    Data kosong
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

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
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {

        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }

    });

}
</script>

@endsection
