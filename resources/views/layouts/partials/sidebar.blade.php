<aside class="main-sidebar sidebar-dark-primary elevation-4" style="background: linear-gradient(180deg, #4e73df 0%, #224abe 100%); transition: all 0.3s ease;">
    <!-- Brand Logo -->
    <a href="{{ route('admin.dashboard') }}" 
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
                style="height:70px; width50px; object-fit:cover;">
        </div>

            <div class="info ms-2">
                <a href="{{ route('admin.profile') }}" class="d-block text-white fw-bold">{{ auth()->user()->name }}</a>
            </div>
        </div>

        <nav>
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link text-white {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt fa-lg"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.siswa.index') }}" class="nav-link text-white {{ request()->routeIs('admin.siswa.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-graduate fa-lg"></i>
                        <p>Kelola Siswa</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.guru.index') }}" class="nav-link text-white {{ request()->routeIs('admin.guru.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chalkboard-teacher fa-lg"></i>
                        <p>Kelola Guru</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.kelas.index') }}" class="nav-link text-white {{ request()->routeIs('admin.kelas.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-school fa-lg"></i>
                        <p>Kelola Kelas</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.jadwal.index') }}" class="nav-link text-white {{ request()->routeIs('admin.jadwal.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calendar-alt fa-lg"></i>
                        <p>Kelola Jadwal</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.absensi.index') }}" class="nav-link text-white {{ request()->routeIs('admin.absensi.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-clipboard-list fa-lg"></i>
                        <p>Kelola Absensi</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.pembayaran.index') }}" class="nav-link text-white {{ request()->routeIs('admin.pembayaran.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-money-bill-wave fa-lg"></i>
                        <p>Kelola Pembayaran</p>
                    </a>
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
}

.nav-sidebar .nav-link:hover {
    background-color: rgba(255,255,255,0.1);
    transform: translateX(5px);
}

.nav-sidebar .nav-link:hover .nav-icon {
    transform: scale(1.2);
}

.nav-sidebar .nav-link.active {
    background-color: rgba(255,255,255,0.2);
    font-weight: bold;
    transform: translateX(5px);
}

.nav-sidebar .nav-link .nav-icon {
    transition: all 0.3s ease;
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

</style>

