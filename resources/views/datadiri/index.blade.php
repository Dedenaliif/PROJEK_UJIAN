@extends('dashboard.index')

@section('content')

<div class="container-fluid">

    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card card-primary card-outline mt-4">

                <div class="card-header text-center">
                    <h4 class="mb-0 font-weight-bold">
                        Form Data Diri
                    </h4>

                    @if($siswa)
                        <small class="text-success">Edit Data</small>
                    @else
                        <small class="text-muted">Lengkapi data diri</small>
                    @endif
                </div>

                <div class="card-body">

                    {{-- 🔥 VALIDASI ERROR --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <b>Terjadi kesalahan:</b>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- 🔥 SUCCESS --}}
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('datadiri.store') }}" method="POST">
                        @csrf

                        <div class="row">

                            {{-- NAMA --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama Lengkap</label>
                                    <input type="text"
                                        name="nama_siswa"
                                        class="form-control @error('nama_siswa') is-invalid @enderror"
                                        value="{{ old('nama_siswa', $siswa->nama_siswa ?? '') }}"
                                        placeholder="Masukkan Nama Lengkap">

                                    @error('nama_siswa')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- NIS --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>NIS</label>
                                    <input type="text"
                                        name="nis"
                                        class="form-control @error('nis') is-invalid @enderror"
                                        value="{{ old('nis', $siswa->nis ?? '') }}"
                                        placeholder="Masukkan NIS">

                                    @error('nis')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- KELAS --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Kelas</label>
                                    <select name="kelas"
                                        class="form-control @error('kelas') is-invalid @enderror">

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
                            </div>

                            {{-- JURUSAN --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Jurusan</label>
                                    <select name="jurusan"
                                        class="form-control @error('jurusan') is-invalid @enderror">

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
                            </div>

                        </div>

                        <div class="text-right mt-3">
                            <button class="btn btn-primary">
                                {{ $siswa ? 'Update Data' : 'Simpan Data' }}
                            </button>
                        </div>

                    </form>

                </div>

            </div>

        </div>
    </div>

</div>

@endsection
