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

<div class="container-fluid py-4">

    <div class="card shadow-sm">

        {{-- HEADER --}}
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0 fw-bold">
                <i class="fas fa-file-alt mr-2"></i> Form Create Ujian
            </h4>
        </div>

        {{-- BODY --}}
        <div class="card-body">

            <form method="POST" action="{{ route('ujian.store') }}">
                @csrf

                <div class="row">

                    {{-- JUDUL --}}
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold">Judul Ujian</label>
                        <input type="text" name="judul"
                            class="form-control @error('judul') is-invalid @enderror"
                            placeholder="Masukkan Judul Ujian"
                            value="{{ old('judul') }}">

                        @error('judul')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- DESKRIPSI --}}
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold">Deskripsi</label>
                        <input type="text" name="deskripsi"
                            class="form-control @error('deskripsi') is-invalid @enderror"
                            placeholder="Masukkan Deskripsi"
                            value="{{ old('deskripsi') }}">

                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- WAKTU --}}
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold">Durasi (Menit)</label>
                        <input type="number" name="waktu"
                            class="form-control @error('waktu') is-invalid @enderror"
                            placeholder="Contoh: 60"
                            value="{{ old('waktu') }}">

                        @error('waktu')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- MAX PERCOBAAN --}}
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold">Max Percobaan</label>
                        <input type="number" name="max_percobaan"
                            class="form-control @error('max_percobaan') is-invalid @enderror"
                            placeholder="Contoh: 3"
                            value="{{ old('max_percobaan') }}">

                        @error('max_percobaan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- WAKTU MULAI --}}
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold">Waktu Mulai</label>
                        <input type="datetime-local" name="waktu_mulai"
                            class="form-control @error('waktu_mulai') is-invalid @enderror"
                            value="{{ old('waktu_mulai') }}">

                        @error('waktu_mulai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- WAKTU SELESAI --}}
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold">Waktu Selesai</label>
                        <input type="datetime-local" name="waktu_selesai"
                            class="form-control @error('waktu_selesai') is-invalid @enderror"
                            value="{{ old('waktu_selesai') }}">

                        @error('waktu_selesai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- TIPE --}}
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold">Tipe Ujian</label>
                        <select name="tipe"
                            class="form-control @error('tipe') is-invalid @enderror">

                            <option value="">-- Pilih Tipe --</option>
                            <option value="word" {{ old('tipe') == 'word' ? 'selected' : '' }}>Word</option>
                            <option value="excel" {{ old('tipe') == 'excel' ? 'selected' : '' }}>Excel</option>

                        </select>

                        @error('tipe')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                {{-- FOOTER --}}
                <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                    <a href="{{ route('ujian.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Ujian
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

@endsection
