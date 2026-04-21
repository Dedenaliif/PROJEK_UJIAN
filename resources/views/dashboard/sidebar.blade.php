<!-- Brand Logo -->
<a href="#" class="brand-link d-flex align-items-center">
    <img src="{{ asset('adminlte/dist/img/AdminLTELogo.png') }}"
        class="brand-image img-circle elevation-3">
    <span class="brand-text fw-bold ms-2">CBT System</span>
</a>

<div class="sidebar">

    <!-- USER PANEL -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center">
        <div class="image">
            <img src="https://ui-avatars.com/api/?name={{ auth()->user()->username }}"
                class="img-circle elevation-2">
        </div>
        <div class="info">
            <a href="#" class="d-block fw-semibold">
                {{ auth()->user()->username }}
            </a>
            <small class="text-muted text-capitalize">
                {{ auth()->user()->role }}
            </small>
        </div>
    </div>

    <!-- MENU -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview">

            {{-- ================= ADMIN ================= --}}
            @if (auth()->user()->role == 'admin')

                <li class="nav-header text-uppercase">Master Data</li>

                <li class="nav-item">
                    <a href="{{ route('user.index') }}"
                        class="nav-link {{ request()->routeIs('user.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Data User</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('siswa.index') }}"
                        class="nav-link {{ request()->routeIs('siswa.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-graduate"></i>
                        <p>Data Siswa</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('kelas.index') }}"
                        class="nav-link {{ request()->routeIs('kelas.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-school"></i>
                        <p>Data Kelas</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('jurusan.index') }}"
                        class="nav-link {{ request()->routeIs('jurusan.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-layer-group"></i>
                        <p>Data Jurusan</p>
                    </a>
                </li>

            @endif

            {{-- ================= PENGUJI ================= --}}
            @if (auth()->user()->role == 'penguji')

            <li class="nav-header text-uppercase mt-3">Ujian</li>

                <li class="nav-item">
                    <a href="{{ route('ujian.index') }}"
                        class="nav-link {{ request()->routeIs('ujian.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>Manajemen Ujian</p>
                    </a>
                </li>

            @endif
            {{-- ================= SISWA ================= --}}
            @if (auth()->user()->role == 'siswa')

                <li class="nav-header text-uppercase">Menu Utama</li>

                <li class="nav-item">
                    <a href="{{ url('siswa/ujian') }}"
                        class="nav-link {{ request()->url('siswa/ujian') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-pencil-alt"></i>
                        <p>Mulai Ujian</p>
                    </a>
                </li>

            @endif
            @if (auth()->user()->role == 'pengawas')

                <li class="nav-header text-uppercase">Menu Utama</li>

                <li class="nav-item">
                    <a href="{{ url('pengawas/monitoring') }}"
                        class="nav-link {{ request()->url('pengawas/monitoring') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-pencil-alt"></i>
                        <p>Monitoring Ujian</p>
                    </a>
                </li>

            @endif

</div>
