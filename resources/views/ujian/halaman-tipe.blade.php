@extends('dashboard.index')

@section('content')
    <div class="container py-5 h-full" x-data="{ selectedType: '', agreed: false }">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-1">
                                <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">Ujian</a>
                                </li>
                                <li class="breadcrumb-item active">{{ $ujian->judul }}</li>
                            </ol>
                        </nav>
                        <h2 class="fw-bold text-dark">{{ $ujian->judul }}</h2>
                    </div>
                    <div class="text-end d-none d-md-block">
                        <p class="mb-0 fw-bold">Nama User: {{ ucfirst(auth()->user()->username) }}</p>
                        {{-- <small class="text-muted">{{ $ }}</small> --}}
                    </div>
                </div>

                <form action="{{ route('ujianstart.start', $ujian->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <h5 class="mb-4 fw-semibold text-secondary">Pilih Tipe Soal:</h5>
                    <div class="row g-4 mb-5">
                        @foreach ($tipeSoal as $tipe)
                            <div class="col-md-6">
                                <div class="card h-100 p-3 selectable-card"
                                    :class="selectedType === '{{ $tipe->tipe }}' ? 'active' : ''"
                                    @click="selectedType = '{{ $tipe->tipe }}'">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between mb-3">
                                            <div class="badge bg-primary-subtle text-primary px-3 py-2">
                                                {{ strtoupper($tipe->tipe) }}</div>
                                            <input type="radio" name="tipe" value="{{ $tipe->tipe }}" class="d-none"
                                                x-model="selectedType">
                                        </div>
                                        <h5 class="card-title fw-bold">Paket {{ ucfirst($tipe->tipe) }}</h5>
                                        <p class="card-text text-muted small">Soal tipe {{ $tipe->tipe }} :
                                            {{ $tipe->total }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <p x-text="selectedType"></p>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-sm shadow btn-start w-100 w-md-auto"
                            :disabled="!selectedType">
                            Mulai Ujian Sekarang
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
