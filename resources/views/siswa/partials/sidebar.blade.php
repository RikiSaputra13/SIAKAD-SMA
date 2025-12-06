<aside class="main-sidebar sidebar-dark-primary elevation-4"
    style="background: linear-gradient(180deg, #4e73df 0%, #224abe 100%); transition: all 0.3s ease;">
    <!-- Brand Logo -->
    <a href="{{ route('siswa.dashboard') }}" class="brand-link text-white d-flex align-items-center"
        style="transition: all 0.3s ease;">
        <img src="{{ asset('adminlte/img/logo-sekolah.jpg') }}" alt="Logo Sekolah"
            class="brand-image img-circle elevation-3" style="height:35px; width:35px; object-fit:cover;">
        <span class="brand-text ms-2 d-flex flex-column text-center">
            <span style="font-size: 15px; font-weight:700;">SMA</span>
            <span style="font-size: 15px; font-weight:700;">Pangeran Jayakarta</span>
        </span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar mt-3">
        <!-- User Panel -->
        <div class="user-panel d-flex align-items-center mb-3 p-2"
            style="border-radius:0.5rem; transition: all 0.3s ease; background-color: rgba(255,255,255,0.05);">
            <div class="image">
                @if (!Auth::user()->siswa || !Auth::user()->siswa->foto)
                    <img src="{{ asset('adminlte/img/admin.png') }}" class="img-circle elevation-2"
                        alt="Default Profile Picture" style="height:70px; width:50px; object-fit:cover;">
                @else
                    <img src="{{ asset('storage/' . Auth::user()->siswa->foto) }}" class="img-circle elevation-2"
                        alt="{{ Auth::user()->siswa->foto }}" style="height:70px; width:50px; object-fit:cover;">
                @endif
            </div>
            <div class="info ms-2">
                <a href="{{ route('siswa.dashboard') }}" class="d-block text-white fw-bold">{{ Auth::user()->name }}</a>
            </div>
        </div>
        <!-- Sidebar Menu -->
        <nav>
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                <li class="nav-item">
                    <a href="{{ route('siswa.dashboard') }}"
                        class="nav-link text-white {{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt fa-lg"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('siswa.jadwal.index') }}"
                        class="nav-link text-white {{ request()->routeIs('siswa.jadwal.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calendar-alt fa-lg"></i>
                        <p>Lihat Jadwal</p>
                    </a>
                </li>

                <!-- Menu Ujian (Dropdown) -->
                <li class="nav-item {{ request()->routeIs('siswa.ujian.*') ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link text-white {{ request()->routeIs('siswa.ujian.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-alt fa-lg"></i>
                        <p>
                            Ujian
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('siswa.ujian-harian.index') }}"
                                class="nav-link text-white {{ request()->routeIs('siswa.ujian-harian.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Ujian</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Menu Nilai (Dropdown) -->
                <li class="nav-item {{ request()->routeIs('siswa.nilai.*') ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link text-white {{ request()->routeIs('siswa.nilai.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-line fa-lg"></i>
                        <p>
                            Nilai
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('siswa.nilai.index') }}"
                                class="nav-link text-white {{ request()->routeIs('siswa.nilai.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Nilai </p>
                            </a>
                        </li>
                        {{-- <li class="nav-item">
                            <a href="#"
                                class="nav-link text-white {{ request()->routeIs('siswa.nilai.uts') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Nilai UTS</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#"
                                class="nav-link text-white {{ request()->routeIs('siswa.nilai.uas') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Nilai UAS</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#"
                                class="nav-link text-white {{ request()->routeIs('siswa.nilai.rapor') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Nilai Rapor</p>
                            </a>
                        </li> --}}
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="{{ route('siswa.absensi.index') }}"
                        class="nav-link text-white {{ request()->routeIs('siswa.absensi.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-check fa-lg"></i>
                        <p>Absensi</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('siswa.ubah-password.form') }}"
                        class="nav-link text-white {{ request()->routeIs('siswa.ubah-password.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-key fa-lg"></i>
                        <p>Ubah Password</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link text-white"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="nav-icon fas fa-sign-out-alt fa-lg"></i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>
        </nav>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
</aside>

<style>
    /* Hover & Active Sidebar Links */
    .nav-sidebar .nav-link {
        transition: all 0.3s ease;
        border-radius: 0.5rem;
    }

    .nav-sidebar .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.1);
        transform: translateX(5px);
    }

    .nav-sidebar .nav-link:hover .nav-icon {
        transform: scale(1.2);
    }

    .nav-sidebar .nav-link.active {
        background-color: rgba(255, 255, 255, 0.2);
        font-weight: bold;
        transform: translateX(5px);
    }

    .nav-sidebar .nav-link .nav-icon {
        transition: all 0.3s ease;
    }

    /* Hover User Panel */
    .user-panel:hover {
        background-color: rgba(255, 255, 255, 0.15);
        transform: translateX(5px);
    }

    .user-panel img {
        transition: all 0.3s ease;
    }

    .user-panel:hover img {
        transform: scale(1.1);
    }

    .user-panel a {
        transition: color 0.3s ease;
    }

    .user-panel:hover a {
        color: #fff;
    }
</style>
