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

<div class="container-xxl container-p-y">

    {{-- HEADER --}}
    <div class="mb-4">
        <h4 class="fw-bold mb-1">Buat Ujian</h4>
        <small class="text-muted">Isi informasi ujian dengan lengkap</small>
    </div>

    <div class="card shadow-sm border-0 rounded-4">

        {{-- HEADER CARD --}}
        <div class="card-header bg-label-primary rounded-top-4">
            <h5 class="mb-0 fw-semibold">
                Informasi Ujian
            </h5>
        </div>

        {{-- BODY --}}
        <div class="card-body p-4">

            <form method="POST" action="{{ route('ujian.store') }}">
                @csrf

                <div class="row g-4">

                    {{-- JUDUL --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Judul Ujian</label>
                        <input type="text" name="judul"
                            class="form-control form-control-lg @error('judul') is-invalid @enderror"
                            placeholder="Contoh: Ujian Microsoft Word"
                            value="{{ old('judul') }}">

                        @error('judul')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- DESKRIPSI --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <input type="text" name="deskripsi"
                            class="form-control form-control-lg @error('deskripsi') is-invalid @enderror"
                            placeholder="Deskripsi singkat ujian"
                            value="{{ old('deskripsi') }}">

                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- DURASI --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Durasi (Menit)</label>
                        <input type="number" name="waktu"
                            class="form-control @error('waktu') is-invalid @enderror"
                            placeholder="Contoh: 60"
                            value="{{ old('waktu') }}">

                        @error('waktu')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- MAX PERCOBAAN --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Max Percobaan</label>
                        <input type="number" name="max_percobaan"
                            class="form-control @error('max_percobaan') is-invalid @enderror"
                            placeholder="Contoh: 3"
                            value="{{ old('max_percobaan') }}">

                        @error('max_percobaan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- WAKTU MULAI --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Waktu Mulai</label>
                        <input type="datetime-local" name="waktu_mulai"
                            class="form-control @error('waktu_mulai') is-invalid @enderror"
                            value="{{ old('waktu_mulai') }}">

                        @error('waktu_mulai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- WAKTU SELESAI --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Waktu Selesai</label>
                        <input type="datetime-local" name="waktu_selesai"
                            class="form-control @error('waktu_selesai') is-invalid @enderror"
                            value="{{ old('waktu_selesai') }}">

                        @error('waktu_selesai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- TIPE --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tipe Ujian</label>
                        <select name="tipe"
                            class="form-select @error('tipe') is-invalid @enderror">

                            <option value="">-- Pilih Tipe --</option>
                            <option value="word" {{ old('tipe') == 'word' ? 'selected' : '' }}>
                                📄 Word
                            </option>
                            <option value="excel" {{ old('tipe') == 'excel' ? 'selected' : '' }}>
                                📊 Excel
                            </option>

                        </select>

                        @error('tipe')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                {{-- FOOTER --}}
                <div class="d-flex justify-content-between mt-5 pt-4 border-top">

                    <a href="{{ route('ujian.index') }}"
                        class="btn btn-outline-secondary px-4">
                        ← Kembali
                    </a>

                    <button type="submit"
                        class="btn btn-primary px-4 shadow-sm">
                        Simpan Ujian
                    </button>

                </div>

            </form>

        </div>
    </div>

</div>

<style>
.container-xxl {
    max-width: 1100px;
    margin: auto;
}

.card {
    border-radius: 14px;
}

.form-control,
.form-select {
    border-radius: 10px;
    padding: 10px 12px;
}

.card-header {
    border-bottom: none;
}

</style>

@endsection
