@extends('dashboard.index')

@section('content')
    <div class="container py-5">

        <div class="card shadow border-0">

            <div class="card-body text-center p-5">

                <h2 class="fw-bold text-warning mb-3">
                    Latihan Selesai
                </h2>

                <h1 class="display-1 fw-bold text-primary">
                    {{ $nilai }}
                </h1>

                <p class="text-muted mb-4">
                    Anda telah menyelesaikan latihan ujian.
                    Klik tombol di bawah untuk masuk ke ujian sebenarnya.
                </p>

                <a href="{{ route('latihan.check') }}" class="btn btn-primary">
                    Ujian Sebenarnya
                </a>

            </div>

        </div>

    </div>
@endsection
