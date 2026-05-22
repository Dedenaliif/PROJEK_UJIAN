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
            <h4 class="fw-bold mb-0">Manajemen Sesi</h4>

            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-tambah-sesi">
                <i class="bx bx-plus"></i>
            </button>
        </div>

        <div class="card">

            <div class="card-header">
                <h6 class="mb-0">Daftar Sesi</h6>
            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-hover text-center datatable">

                        <thead class="table-light">
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Sesi</th>
                                <th class="text-center">Jam</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($sesis as $s)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>

                                    <td>
                                        <span class="badge bg-primary">
                                            Sesi {{ $s->no_sesi }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ $s->jam }}
                                    </td>

                                    <td>

                                        <button class="btn btn-warning btn-sm btn-edit-sesi" data-id="{{ $s->id }}"
                                            data-no_sesi="{{ $s->no_sesi }}" data-jam_mulai="{{ $s->jam_mulai }}"
                                            data-jam_selesai="{{ $s->jam_selesai }}" data-bs-toggle="modal"
                                            data-bs-target="#modal-edit">

                                            <i class="bx bx-edit"></i>

                                        </button>

                                        <button class="btn btn-danger btn-sm" onclick="confirmDelete({{ $s->id }})">

                                            <i class="bx bx-trash"></i>

                                        </button>

                                        <form id="delete-form-{{ $s->id }}"
                                            action="{{ route('sesi.destroy', $s->id) }}" method="POST">

                                            @csrf
                                            @method('DELETE')

                                        </form>

                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="4">Data kosong</td>
                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>
        </div>

    </div>

    @include('sesi.modalcreate')
    @include('sesi.modaledit')

    <script>
        document.querySelectorAll('.btn-edit-sesi').forEach(btn => {
            btn.addEventListener('click', function() {

                document.getElementById('edit-no_sesi').value = this.dataset.no_sesi
                document.getElementById('edit-jam_mulai').value = this.dataset.jam_mulai
                document.getElementById('edit-jam_selesai').value = this.dataset.jam_selesai

                document.getElementById('form-edit').action =
                    `/admin/sesi/${this.dataset.id}`
            })
        })

        function confirmDelete(id) {
            Swal.fire({
                title: 'Yakin hapus?',
                icon: 'warning',
                showCancelButton: true
            }).then((r) => {
                if (r.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit()
                }
            })
        }
    </script>
@endsection
