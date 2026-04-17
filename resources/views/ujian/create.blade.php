@extends('dashboard.index')

@section('content')
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
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-12">

                <div class=" card form-card border-0 p-4 p-md-5">
                    <h3 class="mb-4 text-primary  fw-bold">Form Create Ujian</h3>


                    <form method="post" action="{{ route('ujian.store') }}" class="needs-validation" novalidate>
                        @csrf
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class=" form-label fw-semibold my-2">Judul Ujian</label>
                                <input type="text" class="form-control" name="judul"
                                    placeholder="Masukkan Judul Ujian">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold my-2">Deskripsi</label>
                                <input type="text" class="form-control" name="deskripsi"
                                    placeholder="Masukkan Deskripsi">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold my-2">Waktu (menit)</label>
                                <input type="number" class="form-control" name="waktu" placeholder="Masukkan Waktu">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold my-2">Max Percobaan</label>
                                <input type="number" class="form-control" name="max_percobaan"
                                    placeholder="Masukkan Max Percobaan">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold my-2">Waktu Mulai</label>
                                <input type="datetime-local" class="form-control" name="waktu_mulai">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold my-2">Waktu Selesai</label>
                                <input type="datetime-local" class="form-control" name="waktu_selesai">
                            </div>
                        </div>


                        <div class="mt-4 flex d-flex justify-content-between  pt-3 border-top">
                            <a href="{{ route('ujian.index') }}" class="btn btn-secondary  rounded-lg px-4 fw-bold">
                                Kembali
                            </a>
                            <button type="submit" class="btn btn-primary btn-submit">
                                Submit
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
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
