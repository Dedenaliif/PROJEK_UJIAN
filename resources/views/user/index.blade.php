@extends('dashboard.index')

@section('content')

{{-- ALERT --}}
@if (session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Berhasil',
    text: '{{ session('success') }}',
    timer: 2000,
    showConfirmButton: false
});
</script>
@endif

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Manajemen User</h4>

    <div class="d-flex gap-2">

        <a href="{{ route('siswa.template') }}" class="btn btn-success">
            <i class="bx bx-download"></i> Template CSV
        </a>

        <button class="btn btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#modal-tambah-user">
            <i class="bx bx-plus"></i> Tambah
        </button>

    </div>
</div>

{{-- IMPORT --}}
<div class="card mb-4">
    <div class="card-body">

        <form action="{{ route('siswa.import') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap">

                <div class="">
                    <label class="form-label fw-semibold">Upload CSV</label>
                    <input type="file" name="file" class="form-control" accept=".csv" required>
                    <small class="text-muted">Format: Nama, Jurusan</small>
                </div>

                <div class="">
                    <button class="btn btn-primary w-100">
                        Generate User
                    </button>
                </div>

            </div>

        </form>

    </div>
</div>

{{-- TABLE --}}
<div class="card">
    <div class="card-header">
        <h6 class="mb-0">Daftar User</h6>
    </div>

    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-hover text-center">

                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($users as $u)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $u->username }}</td>
                            <td>
                                @php
                                    $badge = match($u->role) {
                                        'admin' => 'bg-danger',
                                        'penguji' => 'bg-primary',
                                        'pengawas' => 'bg-warning',
                                        'siswa' => 'bg-success',
                                        default => 'bg-secondary'
                                    };
                                @endphp

                                <span class="badge {{ $badge }}">
                                    {{ $u->role }}
                                </span>
                            </td>

                            <td>

                                <button class="btn btn-warning btn-sm btn-edit_user"
                                    data-id="{{ $u->id }}"
                                    data-username="{{ $u->username }}"
                                    data-role="{{ $u->role }}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modal-edit">
                                    Edit
                                </button>

                                <button class="btn btn-danger btn-sm"
                                    onclick="confirmDelete({{ $u->id }})">
                                    Hapus
                                </button>

                                <form id="delete-form-{{ $u->id }}"
                                    action="{{ route('user.destroy', $u->id) }}"
                                    method="POST">
                                    @csrf
                                    @method('DELETE')
                                </form>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-muted">
                                Data user belum ada
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>
</div>

@include('user.edit')
@include('user.create')

<script>
document.querySelectorAll('.btn-edit_user').forEach(btn => {
    btn.addEventListener('click', function () {

        let id = this.dataset.id;
        let username = this.dataset.username;
        let role = this.dataset.role;

        document.getElementById('edit-username').value = username;
        document.getElementById('edit-role').value = role;

        document.getElementById('form-edit').action = '/admin/user/' + id;
    });
});
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
