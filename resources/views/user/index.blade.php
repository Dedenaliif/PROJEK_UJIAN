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

<section class="content-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-0">Manajemen User</h4>

            <div class="d-flex gap-2">
                {{-- DOWNLOAD TEMPLATE --}}
                <a href="{{ route('siswa.template') }}" class="btn btn-success btn-sm m-2">
                    <i class="fas fa-download"></i> Download Template CSV
                </a>

                {{-- TAMBAH USER --}}
                <button class="btn btn-primary btn-sm m-2" data-toggle="modal" data-target="#modal-tambah-user">
                    <i class="fas fa-plus"></i> Tambah
                </button>
            </div>
        </div>

        {{-- CARD IMPORT --}}
        <div class="card mb-3">
            <div class="card-body">
                <form action="{{ route('siswa.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row align-items-end">

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Upload CSV Siswa</label>
                            <input type="file" name="file" class="form-control" accept=".csv" required>
                            <small class="text-muted">Format: Nama, Jurusan</small>
                        </div>

                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100">
                                Generate User
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>

    </div>
</section>

<section class="content">
    <div class="container-fluid">

        <div class="card">

            <div class="card-header">
                <h6 class="mb-0">Daftar User</h6>
            </div>

            <div class="card-body">

                <table id="table-kelas" class="table table-bordered table-hover">
                    <thead class="table-light text-center">
                        <tr>
                            <th width="50">No</th>
                            <th>Username</th>
                            <th width="150">Role</th>
                            <th width="180">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($users as $u)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-center">{{ $u->username }}</td>
                                <td class="text-center">
                                   @php
                                        $badgeColor = match($u->role) {
                                            'admin' => 'bg-danger',
                                            'penguji' => 'bg-primary',
                                            'pengawas' => 'bg-warning',
                                            'siswa' => 'bg-success',
                                            default => 'bg-secondary'
                                        };
                                    @endphp

                                    <span class="badge {{ $badgeColor }} text-capitalize">
                                        {{ $u->role }}
                                    </span>
                                </td>
                                <td class="text-center">

                                    <button class="btn btn-warning btn-sm btn-edit_user"
                                        data-id="{{ $u->id }}"
                                        data-username="{{ $u->username }}"
                                        data-role="{{ $u->role }}"
                                        data-toggle="modal"
                                        data-target="#modal-edit">
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
                                <td colspan="4" class="text-center text-muted">
                                    Data user belum ada
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>

            </div>
        </div>
    </div>
</section>

@include('user.edit')
@include('user.create')

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
