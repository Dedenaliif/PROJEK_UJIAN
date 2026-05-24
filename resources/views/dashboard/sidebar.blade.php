<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

    {{-- BRAND --}}
    <div class="app-brand demo text-center">
        <a href="#" class="app-brand-link">
            <span class="app-brand-text fw-bold fs-5">
                🎓 CBT System
            </span>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    {{-- USER --}}
    @php
        $user = auth()->user();
        $nama = $user->role == 'siswa' ? $user->siswa->nama_siswa ?? $user->username : $user->username;
    @endphp

    <div class="text-center py-3 border-bottom">
        <img src="https://ui-avatars.com/api/?name={{ $nama }}" class="rounded-circle mb-2" width="50">
        <div class="fw-semibold">{{ $nama }}</div>
        <small class="text-muted text-capitalize">{{ $user->role }}</small>
    </div>

    {{-- MENU --}}
    <ul class="menu-inner py-3">

        {{-- ADMIN --}}
        @if ($user->role == 'admin')
            <li class="menu-header small text-uppercase">
                <span>Master Data</span>
            </li>

            <li class="menu-item {{ request()->routeIs('user.*') ? 'active' : '' }}">
                <a href="{{ route('user.index') }}" class="menu-link">
                    <i class="menu-icon bx bx-user"></i>
                    <div>Data User</div>
                </a>
            </li>

            <li class="menu-item {{ request()->routeIs('siswa.*') ? 'active' : '' }}">
                <a href="{{ route('siswa.index') }}" class="menu-link">
                    <i class="menu-icon bx bx-user-pin"></i>
                    <div>Data Siswa</div>
                </a>
            </li>

            <li class="menu-item {{ request()->routeIs('kelas.*') ? 'active' : '' }}">
                <a href="{{ route('kelas.index') }}" class="menu-link">
                    <i class="menu-icon bx bx-buildings"></i>
                    <div>Data Kelas</div>
                </a>
            </li>

            <li class="menu-item {{ request()->routeIs('jurusan.*') ? 'active' : '' }}">
                <a href="{{ route('jurusan.index') }}" class="menu-link">
                    <i class="menu-icon bx bx-layer"></i>
                    <div>Data Jurusan</div>
                </a>
            </li>

            <li class="menu-item {{ request()->routeIs('sesi.*') ? 'active' : '' }}">
                <a href="{{ route('sesi.index') }}" class="menu-link">
                    <i class="menu-icon bx bx-time"></i>
                    <div>Data Sesi</div>
                </a>
            </li>
        @endif


        {{-- PENGUJI --}}
        @if ($user->role == 'penguji')
            <li class="menu-header small text-uppercase">
                <span>Ujian</span>
            </li>

            <li class="menu-item {{ request()->routeIs('ujian.*') ? 'active' : '' }}">
                <a href="{{ route('ujian.index') }}" class="menu-link">
                    <i class="menu-icon bx bx-file"></i>
                    <div>Manajemen Ujian</div>
                </a>
            </li>
            <li class="menu-item {{ request()->routeIs('markup.*') ? 'active' : '' }}">
                <a href="{{ route('markup.nilai') }}" class="menu-link">
                    <i class="menu-icon bx bx-file"></i>
                    <div>Manajemen Markup Nilai</div>
                </a>
            </li>
        @endif


        {{-- SISWA --}}
        @if ($user->role == 'siswa')

            @php
                $siswa = $user->siswa;

                $dataLengkap =
                    $siswa && $siswa->kelas_id && $siswa->jurusan_id && $siswa->nis && $siswa->no_hp && $siswa->email;
            @endphp

            <li class="menu-header small text-uppercase">
                <span>Menu</span>
            </li>

            {{-- MULAI UJIAN --}}
            @if ($dataLengkap)
                <li class="menu-item {{ request()->is('siswa/ujian*') ? 'active' : '' }}">
                    <a href="{{ route('latihan.check') }}" class="menu-link">
                        <i class="menu-icon bx bx-pencil"></i>
                        <div>Mulai Ujian</div>
                    </a>
                </li>
            @else
                <li class="menu-item disabled">
                    <a href="{{ route('datadiri.index') }}" class="menu-link">
                        <i class="menu-icon bx bx-lock"></i>
                        <div>Isi Data Diri Dulu</div>
                    </a>
                </li>
            @endif

            {{-- DATA DIRI --}}
            <li class="menu-item {{ request()->routeIs('datadiri.*') ? 'active' : '' }}">
                <a href="{{ route('datadiri.index') }}" class="menu-link">
                    <i class="menu-icon bx bx-id-card"></i>
                    <div>Data Diri</div>
                </a>
            </li>

        @endif


        {{-- PENGAWAS --}}
        @if ($user->role == 'pengawas')
            <li class="menu-header small text-uppercase">
                <span>Monitoring</span>
            </li>

            <li class="menu-item {{ request()->is('pengawas/monitoring') ? 'active' : '' }}">
                <a href="{{ url('pengawas/monitoring') }}" class="menu-link">
                    <i class="menu-icon bx bx-desktop"></i>
                    <div>Monitoring Ujian</div>
                </a>
            </li>
        @endif

    </ul>
</aside>
