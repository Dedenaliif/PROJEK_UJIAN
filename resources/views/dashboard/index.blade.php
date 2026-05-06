<!DOCTYPE html>
<html lang="en"
  class="light-style layout-navbar-fixed layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="{{ asset('sneat/assets/') }}/"
  data-template="vertical-menu-template">

<head>
    <meta charset="utf-8" />
    <title>CBT</title>

    @include('dashboard.header')
</head>

<body>

<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">

        {{-- SIDEBAR --}}
        @include('dashboard.sidebar')

        <div class="layout-page">

            {{-- NAVBAR --}}
            @include('dashboard.navbar')

            <div class="content-wrapper">
                <div class="container-xxl flex-grow-1 container-p-y">
                    @yield('content')
                    @if(Auth::check() && Auth::user()->role === 'admin' && request()->routeIs('dashboard'))
                    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
                    <div class="row g-4 mb-4">

                        {{-- SISWA --}}
                        <div class="col-md-4">
                            <div class="card stat-card shadow-sm border-0">
                                <div class="card-body d-flex align-items-center gap-3">
                                    <div class="icon-box bg-primary">
                                        <i class="bi bi-mortarboard"></i>
                                    </div>

                                    <div>
                                        <small class="text-muted">Total Siswa</small>
                                        <h3 class="fw-bold mb-0">{{ $totalSiswa }}</h3>
                                    </div>

                                </div>
                            </div>
                        </div>

                        {{-- PENGUJI --}}
                        <div class="col-md-4">
                            <div class="card stat-card shadow-sm border-0">
                                <div class="card-body d-flex align-items-center gap-3">
                                    <div class="icon-box bg-success">
                                        <i class="bi bi-person-workspace"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted">Penguji</small>
                                        <h3 class="fw-bold mb-0">{{ $totalPenguji }}</h3>
                                    </div>

                                </div>
                            </div>
                        </div>

                        {{-- PENGAWAS --}}
                        <div class="col-md-4">
                            <div class="card stat-card shadow-sm border-0">
                                <div class="card-body d-flex align-items-center gap-3">

                                    <div class="icon-box bg-danger">
                                        <i class="bi bi-shield-check"></i>
                                    </div>

                                    <div>
                                        <small class="text-muted">Pengawas</small>
                                        <h3 class="fw-bold mb-0">{{ $totalPengawas }}</h3>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                    <style>
                    .stat-card {
                        border-radius: 16px;
                        transition: 0.25s;
                    }

                    .stat-card:hover {
                        transform: translateY(-5px);
                    }

                    .icon-box {
                        width: 55px;
                        height: 55px;
                        border-radius: 12px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 24px;
                        color: white;
                    }
                    </style>
                    @endif
                </div>

                @include('dashboard.footer')
            </div>

        </div>
    </div>
</div>

@include('dashboard.script')

</body>
</html>
