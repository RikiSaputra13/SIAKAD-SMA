@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1 text-dark fw-bold">Dashboard Admin</h1>
                    <p class="text-muted mb-0">Selamat datang, {{ Auth::user()->name }}! 👋</p>
                </div>
                <div class="text-end">
                    <span class="badge bg-primary fs-6 px-3 py-2">
                        <i class="fas fa-clock me-2"></i>
                        <span id="current-time">{{ now()->format('H:i') }}</span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards with Animation -->
    <div class="row g-3 mb-4">
        {{-- Card Total Siswa --}}
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card stats-card border-0 shadow-hover" data-aos="fade-up" data-aos-delay="100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-title text-primary mb-1">{{ $totalSiswa }}</h3>
                            <p class="card-text text-muted mb-0">Total Siswa</p>
                            <small class="text-success">
                                <i class="fas fa-user-graduate me-1"></i>
                                <span>Active</span>
                            </small>
                        </div>
                        <div class="icon-container bg-primary">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card Total Guru --}}
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card stats-card border-0 shadow-hover" data-aos="fade-up" data-aos-delay="200">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-title text-success mb-1">{{ $totalGuru }}</h3>
                            <p class="card-text text-muted mb-0">Total Guru</p>
                            <small class="text-success">
                                <i class="fas fa-chalkboard-teacher me-1"></i>
                                <span>Teaching</span>
                            </small>
                        </div>
                        <div class="icon-container bg-success">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card Total Kelas --}}
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card stats-card border-0 shadow-hover" data-aos="fade-up" data-aos-delay="300">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-title text-info mb-1">{{ $totalKelas }}</h3>
                            <p class="card-text text-muted mb-0">Total Kelas</p>
                            <small class="text-success">
                                <i class="fas fa-school me-1"></i>
                                <span>Active</span>
                            </small>
                        </div>
                        <div class="icon-container bg-info">
                            <i class="fas fa-school"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card Total Jadwal --}}
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card stats-card border-0 shadow-hover" data-aos="fade-up" data-aos-delay="400">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-title text-warning mb-1">{{ $totalJadwal }}</h3>
                            <p class="card-text text-muted mb-0">Total Jadwal</p>
                            <small class="text-success">
                                <i class="fas fa-calendar-alt me-1"></i>
                                <span>This Week</span>
                            </small>
                        </div>
                        <div class="icon-container bg-warning">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card Total Absensi --}}
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card stats-card border-0 shadow-hover" data-aos="fade-up" data-aos-delay="500">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-title text-danger mb-1">{{ $totalAbsensi }}</h3>
                            <p class="card-text text-muted mb-0">Total Absensi</p>
                            <small class="text-success">
                                <i class="fas fa-clipboard-list me-1"></i>
                                <span>Records</span>
                            </small>
                        </div>
                        <div class="icon-container bg-danger">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card Total Mata Pelajaran --}}
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card stats-card border-0 shadow-hover" data-aos="fade-up" data-aos-delay="600">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-title text-secondary mb-1">{{ $totalMapel ?? '0' }}</h3>
                            <p class="card-text text-muted mb-0">Mata Pelajaran</p>
                            <small class="text-success">
                                <i class="fas fa-book me-1"></i>
                                <span>Subjects</span>
                            </small>
                        </div>
                        <div class="icon-container bg-secondary">
                            <i class="fas fa-book"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    

    {{-- System Overview --}}
    <div class="row g-4 mt-2">
        <div class="col-12">
            <div class="card border-0 shadow-sm" data-aos="fade-up" data-aos-delay="300">
                <div class="card-header bg-transparent border-0 py-3">
                    <h5 class="mb-0 text-dark fw-bold">
                        <i class="fas fa-chart-bar me-2 text-success"></i>
                        System Overview
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 col-6 mb-3">
                            <div class="p-3 border rounded-3 hover-lift">
                                <div class="text-primary mb-2">
                                    <i class="fas fa-database fa-2x"></i>
                                </div>
                                <h4 class="fw-bold text-dark">{{ $totalSiswa + $totalGuru }}</h4>
                                <p class="text-muted mb-0">Total Users</p>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <div class="p-3 border rounded-3 hover-lift">
                                <div class="text-success mb-2">
                                    <i class="fas fa-book fa-2x"></i>
                                </div>
                                <h4 class="fw-bold text-dark">{{ $totalMapel ?? '0' }}</h4>
                                <p class="text-muted mb-0">Mata Pelajaran</p>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <div class="p-3 border rounded-3 hover-lift">
                                <div class="text-info mb-2">
                                    <i class="fas fa-tasks fa-2x"></i>
                                </div>
                                <h4 class="fw-bold text-dark">{{ $totalJadwal }}</h4>
                                <p class="text-muted mb-0">Active Schedules</p>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <div class="p-3 border rounded-3 hover-lift">
                                <div class="text-warning mb-2">
                                    <i class="fas fa-chart-line fa-2x"></i>
                                </div>
                                <h4 class="fw-bold text-dark">{{ $totalAbsensi }}</h4>
                                <p class="text-muted mb-0">Attendance Records</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Custom Styles for Dashboard */
.stats-card {
    transition: all 0.3s ease;
    border-radius: 1rem;
    overflow: hidden;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
}

.shadow-hover {
    box-shadow: 0 2px 15px rgba(0,0,0,0.08) !important;
}

.icon-container {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

.card-title {
    font-size: 2rem;
    font-weight: 700;
}

/* Quick Actions Styles */
.action-btn {
    border: 2px solid;
    border-radius: 12px;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    min-height: 100px;
}

.action-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.action-btn span {
    font-weight: 600;
    font-size: 0.9rem;
}

/* Info Items */
.info-item {
    padding: 12px 0;
    border-bottom: 1px solid #f0f0f0;
}

.info-item:last-child {
    border-bottom: none;
}

.info-icon {
    width: 45px;
    height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

/* Token Visual */
.token-visual {
    transition: all 0.3s ease;
}

.token-visual:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

/* Hover Lift Effect */
.hover-lift {
    transition: all 0.3s ease;
}

.hover-lift:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

/* Responsive Design */
@media (max-width: 768px) {
    .card-title {
        font-size: 1.5rem;
    }
    
    .icon-container {
        width: 50px;
        height: 50px;
        font-size: 1.2rem;
    }
    
    .action-btn {
        min-height: 80px;
    }
    
    .action-btn i {
        font-size: 1.5rem !important;
    }
}

/* Animation Classes */
.fade-in {
    animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Live Clock */
#live-clock {
    font-family: 'Courier New', monospace;
    color: #28a745;
}
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS (Animate On Scroll)
    AOS.init({
        duration: 800,
        once: true,
        offset: 100
    });

    // Live Clock
    function updateClock() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
        document.getElementById('live-clock').textContent = timeString;
        document.getElementById('current-time').textContent = now.toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    // Update clock immediately and every second
    updateClock();
    setInterval(updateClock, 1000);

    // Add hover effects to stats cards
    const statsCards = document.querySelectorAll('.stats-card');
    statsCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // Quick actions animation
    const actionBtns = document.querySelectorAll('.action-btn');
    actionBtns.forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-3px) scale(1.02)';
        });
        
        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });

    // Auto-refresh dashboard data every 5 minutes
    setInterval(() => {
        // You can add AJAX call here to refresh data
        console.log('Auto-refresh dashboard data');
    }, 300000); // 5 minutes
});

// Add loading animation
window.addEventListener('load', function() {
    document.body.classList.add('loaded');
});
</script>
@endpush