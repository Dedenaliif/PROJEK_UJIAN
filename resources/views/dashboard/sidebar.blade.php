  <!-- Brand Logo -->
  <a href="../../index3.html" class="brand-link">
      <img src="{{ asset('adminlte/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
          class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">AdminLTE 3</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">


      <!-- Sidebar Menu -->
      <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
              <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
              <li class="nav-item">
                  <a href="#" class="nav-link">
                      <i class="nav-icon fas fa-tachometer-alt"></i>
                      <p>
                          Master Data
                      </p>
                  </a>

                      <li class="nav-item">
                          <a href="{{ route('user.index') }}" class="nav-link {{ request()->routeIs('user.*') ? 'active' : '' }}">
                              <i class="far fa-circle nav-icon"></i>
                              <p>Data User</p>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a href="{{ route('siswa.index') }}" class="nav-link {{ request()->routeIs('siswa.*') ? 'active' : '' }}">
                              <i class="far fa-circle nav-icon"></i>
                              <p>Data Siswa</p>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a href="{{ route('kelas.index') }}" class="nav-link {{ request()->routeIs('kelas.*') ? 'active' : '' }}">
                              <i class="far fa-circle nav-icon"></i>
                              <p>Data Kelas</p>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a href="{{ route('jurusan.index') }}" class="nav-link {{ request()->routeIs('jurusan.*') ? 'active' : '' }}">
                              <i class="far fa-circle nav-icon"></i>
                              <p>Data Jurusan</p>
                          </a>
                      </li>

              </li>
              <li class="nav-item">
                  <a href="../../index2.html" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Data Ujian</p>
                  </a>
              </li>
          </ul>
      </nav>
      <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
