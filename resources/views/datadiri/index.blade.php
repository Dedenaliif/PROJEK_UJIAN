@extends('dashboard.index')

@section('content')

    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="row justify-content-center">
            <div class="col-lg-8">

                <div class="card border-0 shadow-sm rounded-4">

                    {{-- HEADER --}}
                    <div class="card-header bg-white border-0 text-center pt-4 pb-2">
                        <h4 class="fw-bold mb-1">Form Data Diri</h4>

                        @if ($siswa)
                            <small class="text-success">Edit Data</small>
                        @else
                            <small class="text-muted">Lengkapi data diri</small>
                        @endif
                    </div>

                    {{-- BODY --}}
                    <div class="card-body px-4 pb-4">

                        {{-- ERROR --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <strong>Terjadi kesalahan:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- SUCCESS --}}
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('datadiri.store') }}" method="POST">
                            @csrf

                            <div class="row g-3">

                                {{-- NAMA --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Nama Lengkap</label>
                                    <input type="text" name="nama_siswa"
                                        class="form-control @error('nama_siswa') is-invalid @enderror"
                                        value="{{ old('nama_siswa', $siswa->nama_siswa ?? '') }}"
                                        placeholder="Masukkan Nama Lengkap">

                                    @error('nama_siswa')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- NIS --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">NIS</label>
                                    <input type="text" name="nis"
                                        class="form-control @error('nis') is-invalid @enderror"
                                        value="{{ old('nis', $siswa->nis ?? '') }}" placeholder="Masukkan NIS">

                                    @error('nis')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- KELAS --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Kelas</label>
                                    <select name="kelas"
                                        class="form-select @error('kelas') is-invalid @enderror
                                    "@if ($siswa && $siswa->kelas_id)  @endif>

                                        <option value="">-- Pilih Kelas --</option>

                                        @foreach ($kelas as $item)
                                            <option value="{{ $item->id }}"
                                                {{ old('kelas', $siswa->kelas_id ?? '') == $item->id ? 'selected' : '' }}>
                                                {{ $item->nama_kelas }}
                                            </option>
                                        @endforeach

                                    </select>

                                    @error('kelas')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- JURUSAN --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Jurusan</label>
                                    <select name="jurusan" class="form-select @error('jurusan') is-invalid @enderror"
                                        @if ($siswa && $siswa->jurusan_id)  @endif>

                                        <option value="">-- Pilih Jurusan --</option>

                                        @foreach ($jurusan as $item)
                                            <option value="{{ $item->id }}"
                                                {{ old('jurusan', $siswa->jurusan_id ?? '') == $item->id ? 'selected' : '' }}>
                                                {{ $item->nama_jurusan }}
                                            </option>
                                        @endforeach

                                    </select>

                                    @error('jurusan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">No Telepon</label>
                                    <input type="text" name="no_hp"
                                        class="form-control @error('no_hp') is-invalid @enderror"
                                        value="{{ old('no_hp', $siswa->no_hp ?? '') }}" placeholder="Masukkan No Telepon">

                                    @error('no_hp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Email</label>
                                    <input type="email" name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email', $siswa->email ?? '') }}" placeholder="Masukkan Email">

                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">NIK</label>
                                    <input type="text" name="nik"
                                        class="form-control @error('nik') is-invalid @enderror"
                                        value="{{ old('nik', $siswa->nik ?? '') }}" placeholder="Masukkan NIK">

                                    @error('nik')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- BUTTON --}}
                            <div class="d-flex justify-content-end mt-4">
                                <button class="btn btn-primary px-4">
                                    {{ $siswa ? 'Update Data' : 'Simpan Data' }}
                                </button>
                            </div>

                        </form>

                    </div>

                </div>

            </div>
        </div>

    </div>

    {{-- STYLE TAMBAHAN --}}
    <style>
        .card {
            transition: 0.25s;
        }

        .card:hover {
            transform: translateY(-2px);
        }

        /* biar gak mepet */
        .container-xxl {
            padding-left: 24px !important;
            padding-right: 24px !important;
        }
    </style>

@endsection
