<aside class="main-sidebar sidebar-dark-primary elevation-4" style="background: linear-gradient(180deg, #4e73df 0%, #224abe 100%); transition: all 0.3s ease;">
    <!-- Brand Logo -->
    <a href="{{ auth()->user()->role == 'guru' ? route('guru.dashboard') : route('admin.dashboard') }}" 
       class="brand-link text-white d-flex align-items-center justify-content-center py-3" 
       style="transition: all 0.3s ease; border-bottom: 1px solid rgba(255,255,255,0.1);">
        <div class="brand-image-container me-2">
            <img src="{{ asset('adminlte/img/logo-sekolah.jpg') }}" 
                 alt="Logo Sekolah" 
                 class="brand-image img-circle elevation-3" 
                 style="height:45px; width:45px; object-fit:cover;">
        </div>
        <div class="brand-text text-center">
            <div style="font-size: 16px; font-weight:700; line-height:1.2;">SMA</div>
            <div style="font-size: 14px; font-weight:600; line-height:1.2;">Pangeran Jayakarta</div>
        </div>
    </a>

    <!-- Sidebar -->
    <div class="sidebar mt-2">
        <!-- User Panel -->
        <div class="user-panel d-flex align-items-center mb-3 p-3 mx-2" 
             style="border-radius:0.75rem; transition: all 0.3s ease; background-color: rgba(255,255,255,0.08);">
            <div class="image">
                <img src="{{ auth()->user()->foto 
                            ? asset('storage/' . auth()->user()->foto) 
                            : asset('adminlte/img/admin.png') }}" 
                    class="img-circle elevation-2" 
                    alt="User Image" 
                    style="height:50px; width:50px; object-fit:cover; border: 2px solid rgba(255,255,255,0.2);">
            </div>
            <div class="info ms-3 flex-grow-1">
                <a href="{{ auth()->user()->role == 'guru' ? route('guru.profile') : route('admin.profile') }}" 
                   class="d-block text-white fw-bold text-truncate" 
                   style="font-size: 0.95rem;">
                    {{ auth()->user()->name }}
                </a>
                <span class="d-block text-white-50 text-truncate" 
                      style="font-size: 0.8rem; margin-top: -2px;">
                    {{ ucfirst(auth()->user()->role) }}
                </span>
            </div>
        </div>

        <nav class="mt-2 px-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @if(auth()->user()->role == 'admin')
                    <!-- Menu Admin -->
                    <li class="nav-item mb-1">
                        <a href="{{ route('admin.dashboard') }}" 
                           class="nav-link text-white {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p class="mb-0">Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a href="{{ route('admin.siswa.index') }}" 
                           class="nav-link text-white {{ request()->routeIs('admin.siswa.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-graduate"></i>
                            <p class="mb-0">Kelola Siswa</p>
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a href="{{ route('admin.guru.index') }}" 
                           class="nav-link text-white {{ request()->routeIs('admin.guru.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-chalkboard-teacher"></i>
                            <p class="mb-0">Kelola Guru</p>
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a href="{{ route('admin.kelas.index') }}" 
                           class="nav-link text-white {{ request()->routeIs('admin.kelas.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-school"></i>
                            <p class="mb-0">Kelola Kelas</p>
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a href="{{ route('admin.jadwal.index') }}" 
                           class="nav-link text-white {{ request()->routeIs('admin.jadwal.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p class="mb-0">Kelola Jadwal</p>
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a href="{{ route('admin.absensi.index') }}" 
                           class="nav-link text-white {{ request()->routeIs('admin.absensi.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-clipboard-list"></i>
                            <p class="mb-0">Kelola Absensi</p>
                        </a>
                    </li>
                    
                @elseif(auth()->user()->role == 'guru')
                    <!-- Menu Guru -->
                    <li class="nav-item mb-1">
                        <a href="{{ route('guru.dashboard') }}" 
                           class="nav-link text-white {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p class="mb-0">Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a href="{{ route('guru.siswa.index') }}" 
                           class="nav-link text-white {{ request()->routeIs('guru.siswa.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-graduate"></i>
                            <p class="mb-0">Kelola Siswa</p>
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a href="{{ route('guru.siswa.absensi') }}" 
                           class="nav-link text-white {{ request()->routeIs('guru.absensi.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-clipboard-list"></i>
                            <p class="mb-0">Absensi Siswa</p>
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a href="{{ route('guru.jadwal.index') }}" 
                           class="nav-link text-white {{ request()->routeIs('guru.jadwal.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p class="mb-0">Jadwal Mengajar</p>
                        </a>
                    </li>
                    
                    <!-- Menu Penilaian dengan Sub Menu -->
                    <li class="nav-item mb-1 {{ request()->routeIs('guru.penilaian.*') ? 'menu-open' : '' }}">
                        <a href="#" 
                           class="nav-link text-white {{ request()->routeIs('guru.penilaian.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-chart-bar"></i>
                            <p class="mb-0 d-flex justify-content-between align-items-center">
                                Penilaian Siswa
                                <i class="right fas fa-angle-left ms-2"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="background-color: rgba(0,0,0,0.1); border-radius: 0.5rem; margin: 0.5rem 0;">
                            <li class="nav-item">
                                <a href="{{ route('guru.penilaian.list') }}" 
                                   class="nav-link text-white {{ request()->routeIs('guru.penilaian.list') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-list me-2"></i>
                                    <p class="mb-0">List Penilaian</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                    <!-- Menu Pembuatan Soal dengan Sub Menu -->
                    <li class="nav-item mb-1 {{ request()->routeIs('guru.soal.*') ? 'menu-open' : '' }}">
                        <a href="#" 
                           class="nav-link text-white {{ request()->routeIs('guru.soal.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tasks"></i>
                            <p class="mb-0 d-flex justify-content-between align-items-center">
                                Pembuatan Soal
                                <i class="right fas fa-angle-left ms-2"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="background-color: rgba(0,0,0,0.1); border-radius: 0.5rem; margin: 0.5rem 0;">
                            <li class="nav-item">
                                <a href="{{ route('guru.penilaian.uh') }}" 
                                   class="nav-link text-white {{ request()->routeIs('guru.penilaian.uh') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-edit me-2"></i>
                                    <p class="mb-0">Ujian Harian</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('guru.penilaian.uts') }}" 
                                   class="nav-link text-white {{ request()->routeIs('guru.penilaian.uts') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-file-alt me-2"></i>
                                    <p class="mb-0">Ujian Tengah Semester</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('guru.penilaian.uas') }}" 
                                   class="nav-link text-white {{ request()->routeIs('guru.penilaian.uas') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-file-contract me-2"></i>
                                    <p class="mb-0">Ujian Akhir Semester</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                
                <!-- Logout Menu -->
                <li class="nav-item mt-4 mb-2">
                    <a href="{{ route('logout') }}" 
                       class="nav-link text-white logout-btn"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p class="mb-0">Keluar</p>
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
/* Global Sidebar Styles */
.main-sidebar {
    box-shadow: 2px 0 10px rgba(0,0,0,0.1);
}

/* Brand Logo Styles */
.brand-link {
    text-decoration: none;
    padding: 1rem 0.5rem;
}

.brand-link:hover {
    background-color: rgba(255,255,255,0.05);
}

.brand-image-container {
    flex-shrink: 0;
}

.brand-text {
    flex-grow: 1;
}

/* User Panel Styles */
.user-panel {
    cursor: pointer;
}

.user-panel:hover {
    background-color: rgba(255,255,255,0.15) !important;
    transform: translateX(5px);
}

.user-panel .image img {
    transition: all 0.3s ease;
}

.user-panel:hover .image img {
    transform: scale(1.05);
    border-color: rgba(255,255,255,0.4) !important;
}

/* Navigation Styles */
.nav-sidebar .nav-link {
    transition: all 0.3s ease;
    border-radius: 0.5rem;
    margin-bottom: 0.15rem;
    padding: 0.75rem 1rem;
    position: relative;
    overflow: hidden;
}

.nav-sidebar .nav-link::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    height: 100%;
    width: 3px;
    background: rgba(255,255,255,0.5);
    transform: scaleY(0);
    transition: transform 0.3s ease;
}

.nav-sidebar .nav-link:hover {
    background-color: rgba(255,255,255,0.1);
    transform: translateX(5px);
}

.nav-sidebar .nav-link:hover::before {
    transform: scaleY(1);
}

.nav-sidebar .nav-link.active {
    background-color: rgba(255,255,255,0.2) !important;
    font-weight: 600;
    transform: translateX(5px);
}

.nav-sidebar .nav-link.active::before {
    transform: scaleY(1);
    background: #fff;
}

/* Treeview Styles */
.nav-treeview {
    padding-left: 0.5rem;
    margin: 0.5rem 0 !important;
}

.nav-treeview .nav-link {
    padding: 0.6rem 1rem 0.6rem 2rem;
    font-size: 0.9rem;
    margin-bottom: 0.1rem;
}

.nav-treeview .nav-link .nav-icon {
    font-size: 0.8rem;
    width: 1rem;
}

/* Menu Open State */
.menu-open > .nav-link {
    background-color: rgba(255,255,255,0.15) !important;
}

.menu-open > .nav-link > .right {
    transform: rotate(-90deg);
}

/* Arrow Animation */
.nav-link > .right {
    transition: transform 0.3s ease;
    font-size: 0.8rem;
}

/* Icons */
.nav-icon {
    margin-right: 0.75rem;
    width: 1.2rem;
    text-align: center;
    font-size: 1rem;
}

/* Logout Button */
.logout-btn {
    background-color: rgba(220,53,69,0.2) !important;
    border: 1px solid rgba(220,53,69,0.3);
}

.logout-btn:hover {
    background-color: rgba(220,53,69,0.3) !important;
    border-color: rgba(220,53,69,0.5);
}

/* Text Truncation */
.text-truncate {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Role-based Background Colors */
.main-sidebar[class*="admin"] {
    background: linear-gradient(180deg, #4e73df 0%, #224abe 100%) !important;
}

.main-sidebar[class*="guru"] {
    background: linear-gradient(180deg, #1cc88a 0%, #198754 100%) !important;
}

/* Responsive Design */
@media (max-width: 767.98px) {
    .main-sidebar {
        transform: translateX(-100%);
    }
    
    .brand-text div {
        font-size: 14px !important;
    }
    
    .user-panel {
        padding: 0.75rem !important;
    }
    
    .nav-sidebar .nav-link {
        padding: 0.6rem 0.8rem;
    }
}

/* Smooth Transitions */
* {
    transition: color 0.3s ease, background-color 0.3s ease, transform 0.3s ease;
}

/* Scrollbar Styling for Sidebar */
.sidebar::-webkit-scrollbar {
    width: 6px;
}

.sidebar::-webkit-scrollbar-track {
    background: rgba(255,255,255,0.1);
}

.sidebar::-webkit-scrollbar-thumb {
    background: rgba(255,255,255,0.3);
    border-radius: 3px;
}

.sidebar::-webkit-scrollbar-thumb:hover {
    background: rgba(255,255,255,0.5);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add role-based class to sidebar
    const sidebar = document.querySelector('.main-sidebar');
    const userRole = '{{ auth()->user()->role }}';
    sidebar.classList.add(userRole + '-sidebar');

    // Auto-close other treeviews when one opens
    const treeviewLinks = document.querySelectorAll('.nav-link[data-widget="treeview"]');
    treeviewLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            if (this.parentElement.classList.contains('menu-open')) {
                return;
            }
            
            // Close other open treeviews
            treeviewLinks.forEach(otherLink => {
                if (otherLink !== this && otherLink.parentElement.classList.contains('menu-open')) {
                    otherLink.parentElement.classList.remove('menu-open');
                }
            });
        });
    });

    // Add active state to current page
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.nav-link[href]');
    
    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href && currentPath.startsWith(href) && href !== '/') {
            link.classList.add('active');
            
            // Also activate parent treeview if exists
            const treeviewParent = link.closest('.nav-treeview')?.parentElement;
            if (treeviewParent) {
                treeviewParent.classList.add('menu-open');
                treeviewParent.querySelector('> .nav-link').classList.add('active');
            }
        }
    });
});
</script>