<nav class="layout-navbar navbar navbar-expand-xl navbar-detached bg-navbar-theme">

    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
    <a class="nav-item nav-link px-0 me-xl-4 layout-menu-toggle" href="javascript:void(0)">
        <i class="bx bx-menu bx-sm"></i>
    </a>
    </div>

  <div class="navbar-nav-right d-flex align-items-center ms-auto">

    @php
      $user = auth()->user();
      $nama = $user->role == 'siswa'
        ? ($user->siswa->nama_siswa ?? $user->username)
        : $user->username;
    @endphp

    <ul class="navbar-nav flex-row align-items-center ms-auto">
      <li class="nav-item dropdown-user dropdown">
        <a class="nav-link dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
          <div class="avatar avatar-online">
            <span class="avatar-initial rounded-circle bg-primary">
              {{ strtoupper(substr($nama,0,1)) }}
            </span>
          </div>
        </a>

        <ul class="dropdown-menu dropdown-menu-end">
          <li class="px-3 py-2">
            <strong>{{ $nama }}</strong><br>
            <small>{{ $user->role }}</small>
          </li>

          <li><div class="dropdown-divider"></div></li>

          <li>
            <a class="dropdown-item"
              href="{{ route('logout') }}"
              onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              <i class="bx bx-power-off me-2"></i> Logout
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
              @csrf
            </form>
          </li>
        </ul>
      </li>
    </ul>

  </div>
</nav>
