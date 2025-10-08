<aside class="main-sidebar sidebar-dark-primary elevation-4" style="background: linear-gradient(180deg, #4e73df 0%, #224abe 100%); transition: all 0.3s ease;">
    <!-- Brand Logo -->
    <a href="{{ auth()->user()->role == 'guru' ? route('guru.dashboard') : route('admin.dashboard') }}" 
    class="brand-link text-white d-flex align-items-center" 
    style="transition: all 0.3s ease;">
        <img src="{{ asset('adminlte/img/logo-sekolah.jpg') }}" 
            alt="Logo Sekolah" 
            class="brand-image img-circle elevation-3" 
            style="height:35px; width:35px; object-fit:cover;">
        
        <span class="brand-text ms-2 d-flex flex-column text-center">
            <span style="font-size: 15px; font-weight:700;">SMA</span>
            <span style="font-size: 15px; font-weight:700;">Pangeran Jayakarta</span>
        </span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar mt-3">
        <!-- User Panel -->
        <div class="user-panel d-flex align-items-center mb-3 p-2" style="border-radius:0.5rem; transition: all 0.3s ease; background-color: rgba(255,255,255,0.05);">
            <div class="image">
                <img src="{{ auth()->user()->foto 
                            ? asset('storage/' . auth()->user()->foto) 
                            : asset('adminlte/img/admin.png') }}" 
                    class="img-circle elevation-2" 
                    alt="User Image" 
                    style="height:50px; width:50px; object-fit:cover;">
            </div>

            <div class="info ms-2">
                <a href="{{ auth()->user()->role == 'guru' ? route('guru.profile') : route('admin.profile') }}" class="d-block text-white fw-bold">{{ auth()->user()->name }}</a>
                <span class="d-block text-white-50" style="font-size: 0.8rem;">{{ ucfirst(auth()->user()->role) }}</span>
            </div>
        </div>

        <nav>
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                @if(auth()->user()->role == 'admin')
                    <!-- Menu Admin -->
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link text-white {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.siswa.index') }}" class="nav-link text-white {{ request()->routeIs('admin.siswa.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-graduate"></i>
                            <p>Kelola Siswa</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.guru.index') }}" class="nav-link text-white {{ request()->routeIs('admin.guru.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-chalkboard-teacher"></i>
                            <p>Kelola Guru</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.kelas.index') }}" class="nav-link text-white {{ request()->routeIs('admin.kelas.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-school"></i>
                            <p>Kelola Kelas</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.jadwal.index') }}" class="nav-link text-white {{ request()->routeIs('admin.jadwal.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p>Kelola Jadwal</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.absensi.index') }}" class="nav-link text-white {{ request()->routeIs('admin.absensi.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-clipboard-list"></i>
                            <p>Kelola Absensi</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.pembayaran.index') }}" class="nav-link text-white {{ request()->routeIs('admin.pembayaran.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-money-bill-wave"></i>
                            <p>Kelola Pembayaran</p>
                        </a>
                    </li>
                    
                @elseif(auth()->user()->role == 'guru')
                    <!-- Menu Guru -->
                    <li class="nav-item">
                        <a href="{{ route('guru.dashboard') }}" class="nav-link text-white {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                     <li class="nav-item">
                        <a href="{{ route('guru.siswa.index') }}" class="nav-link text-white {{ request()->routeIs('guru.siswa.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-graduate"></i>
                            <p>Kelola Siswa</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('guru.siswa.absensi') }}" class="nav-link text-white {{ request()->routeIs('guru.absensi.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-clipboard-list"></i>
                            <p>Absensi Siswa</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('guru.jadwal.index') }}" class="nav-link text-white {{ request()->routeIs('guru.jadwal.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p>Jadwal Mengajar</p>
                        </a>
                    </li>
                    
                    <!-- Menu Penilaian dengan Sub Menu -->
                    <li class="nav-item {{ request()->routeIs('guru.penilaian.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link text-white {{ request()->routeIs('guru.penilaian.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-chart-bar"></i>
                            <p>
                                Penilaian Siswa
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('guru.penilaian.list') }}" class="nav-link text-white {{ request()->routeIs('guru.penilaian.list') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-list"></i>
                                    <p>List Penilaian</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                    <!-- Menu Pembuatan Soal dengan Sub Menu -->
                    <li class="nav-item {{ request()->routeIs('guru.soal.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link text-white {{ request()->routeIs('guru.soal.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tasks"></i>
                            <p>
                                Pembuatan Soal
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('guru.penilaian.uh') }}" class="nav-link text-white {{ request()->routeIs('guru.penilaian.uh') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-edit"></i>
                                    <p>Ujian Harian</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link text-white {{ request()->routeIs('guru.soal.uts') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-file-alt"></i>
                                    <p>Ujian Tengah Semester</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link text-white {{ request()->routeIs('guru.soal.uas') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-file-contract"></i>
                                    <p>Ujian Akhir Semester</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                
                <!-- Logout Menu (Umum untuk semua role) -->
                <li class="nav-item mt-3">
                    <a href="{{ route('logout') }}" 
                       class="nav-link text-white" 
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Keluar</p>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </nav>
    </div>
</aside>

<style>
/* Hover & Active Sidebar Links */
.nav-sidebar .nav-link {
    transition: all 0.3s ease;
    border-radius: 0.5rem;
    margin-bottom: 0.2rem;
}

.nav-sidebar .nav-link:hover {
    background-color: rgba(255,255,255,0.1);
    transform: translateX(5px);
}

.nav-sidebar .nav-link.active {
    background-color: rgba(255,255,255,0.2);
    font-weight: bold;
    transform: translateX(5px);
}

.nav-sidebar .nav-link .nav-icon {
    transition: all 0.3s ease;
    width: 1.2rem;
    text-align: center;
}

/* Hover User Panel */
.user-panel:hover {
    background-color: rgba(255,255,255,0.15);
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

/* Guru sidebar color accent */
@if(auth()->user()->role == 'guru')
    .main-sidebar {
        background: linear-gradient(180deg, #1cc88a 0%, #198754 100%) !important;
    }
@endif

/* Style untuk sub menu */
.nav-treeview {
    margin-left: 15px;
}

.nav-treeview .nav-link {
    padding-left: 20px;
    font-size: 14px;
}

.nav-treeview .nav-link .nav-icon {
    font-size: 12px;
}

/* Animasi untuk menu yang terbuka */
.menu-open > .nav-link {
    background-color: blue !important;
}

/* Style untuk arrow menu */
.nav-link > .right {
    transition: transform 0.3s ease;
}

.menu-open .nav-link > .right {
    transform: rotate(-90deg);
}

/* Perbaikan untuk ikon yang konsisten */
.nav-icon {
    margin-right: 0.5rem;
}

/* Responsif untuk mobile */
@media (max-width: 767.98px) {
    .main-sidebar {
        transform: translateX(-100%);
    }
}
</style>