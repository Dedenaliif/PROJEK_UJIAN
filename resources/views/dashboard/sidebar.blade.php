<!-- BRAND -->
<a href="#" class="brand-link text-center py-3">
    <span class="brand-text fw-bold text-white fs-5">
        🎓 CBT System
    </span>
</a>

<div class="sidebar">

    <!-- USER PANEL -->
    <div class="user-panel d-flex align-items-center px-3 py-3 mb-3">
        @php
            $user = auth()->user();
            $nama = $user->role == 'siswa'
                ? ($user->siswa->nama_siswa ?? $user->username)
                : $user->username;
        @endphp

        <div class="image">
            <img src="https://ui-avatars.com/api/?name={{ $nama }}"
                class="img-circle elevation-2">
        </div>

        <div class="info ms-2">
            <div class="fw-semibold text-white">
                {{ $nama }}
            </div>
            <small class="text-light text-capitalize">
                {{ $user->role }}
            </small>
        </div>
    </div>

    <!-- MENU -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column sidebar-menu">

            {{-- ADMIN --}}
            @if (auth()->user()->role == 'admin')

                <li class="nav-header">MASTER DATA</li>

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

            {{-- PENGUJI --}}
            @if (auth()->user()->role == 'penguji')

                <li class="nav-header">UJIAN</li>

                <li class="nav-item">
                    <a href="{{ route('ujian.index') }}"
                        class="nav-link {{ request()->routeIs('ujian.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>Manajemen Ujian</p>
                    </a>
                </li>

            @endif

            {{-- SISWA --}}
            @if (auth()->user()->role == 'siswa')

                <li class="nav-header">MENU</li>

               @if($siswa)
                    <li class="nav-item">
                        <a href="{{ url('siswa/ujian') }}"
                            class="nav-link {{ request()->is('siswa/ujian') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-pencil-alt"></i>
                            <p>Mulai Ujian</p>
                        </a>
                    </li>
                    @else
                    <li class="nav-item">
                        <a href="#" class="nav-link disabled text-muted">
                            <i class="nav-icon fas fa-lock"></i>
                            <p>Mulai Ujian (Isi Data Diri dulu)</p>
                        </a>
                    </li>
                @endif

                <li class="nav-item">
                    <a href="{{ route('datadiri.index') }}"
                        class="nav-link {{ request()->routeIs('datadiri.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-id-card"></i>
                        <p>Data Diri</p>
                    </a>
                </li>

            @endif

            {{-- PENGAWAS --}}
            @if (auth()->user()->role == 'pengawas')

                <li class="nav-header">MONITORING</li>

                <li class="nav-item">
                    <a href="{{ url('pengawas/monitoring') }}"
                        class="nav-link {{ request()->is('pengawas/monitoring') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-desktop"></i>
                        <p>Monitoring Ujian</p>
                    </a>
                </li>

            @endif

        </ul>
    </nav>
</div><!-- BRAND -->


<style>

/* RESET DEFAULT ADMINLTE */
.nav-sidebar {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding-left: 0 !important;
}

/* NAV ITEM */
.nav-sidebar .nav-item {
    width: 100%;
    display: flex;
    justify-content: center;
}

/* NAV LINK JADI BUTTON */
.nav-sidebar .nav-link {
    width: 80%;
    display: flex;
    align-items: center;
    justify-content: center;

    gap: 12px;
    padding: 12px 18px;

    border-radius: 14px;

    color: #cbd5f5;
    font-weight: 500;

    transition: all 0.25s ease;
}

/* FIX POSISI BIAR TIDAK KEKANAN */
.nav-sidebar .nav-link {
    margin: 6px auto; /* 🔥 ini bikin center beneran */
}

/* ICON */
.nav-sidebar .nav-icon {
    font-size: 16px;
    width: 20px;
    text-align: center;
}

/* TEXT */
.nav-sidebar .nav-link p {
    margin: 0;
    text-align: center;
}

/* HOVER EFFECT */
.nav-sidebar .nav-link:hover {
    background: rgba(59,130,246,0.15);
    transform: translateY(-2px) scale(1.02);
}

/* ACTIVE MENU */
.nav-sidebar .nav-link.active {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    color: white;
    box-shadow: 0 6px 18px rgba(59,130,246,0.35);
    transform: scale(1.03);
}

.nav-sidebar .nav-link.active::before {
    content: '';
    position: absolute;
    left: 0;
    height: 60%;
    width: 4px;
    background: white;
    border-radius: 0 5px 5px 0;
}

/* HEADER TEXT */
.nav-header {
    text-align: center;
    font-size: 12px;
    color: #94a3b8;
    margin-top: 15px;
    margin-bottom: 5px;
    letter-spacing: 1px;
}

/* USER PANEL */
.user-panel {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;

    border-bottom: 1px solid rgba(255,255,255,0.08);
    padding-bottom: 15px;
}

/* AVATAR */
.user-panel .image img {
    width: 45px;
    height: 45px;
    margin-bottom: 8px;
}

/* USER NAME */
.user-panel .info a {
    font-size: 14px;
}

/* ROLE */
.user-panel small {
    font-size: 11px;
}

/* BRAND */
.brand-link {
    justify-content: center;
    text-align: center;
}

.brand-text {
    font-size: 16px;
    letter-spacing: 1px;
}

/* ANIMASI HALUS */
.nav-link, .nav-icon {
    transition: all 0.2s ease-in-out;
}

</style>
