@extends('layouts.app')

@section('title', 'Dashboard Guru')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1 text-dark fw-bold">Dashboard Guru</h1>
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
        {{-- Card Total Kelas yang Diampu --}}
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card border-0 shadow-hover" data-aos="fade-up" data-aos-delay="100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-title text-primary mb-1">{{ $totalKelasDiampu }}</h3>
                            <p class="card-text text-muted mb-0">Kelas Diampu</p>
                            <small class="text-success">
                                <i class="fas fa-trend-up me-1"></i>
                                <span>Active</span>
                            </small>
                        </div>
                        <div class="icon-container bg-primary">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card Total Jadwal Mengajar --}}
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card border-0 shadow-hover" data-aos="fade-up" data-aos-delay="200">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-title text-success mb-1">{{ $totalJadwalMengajar }}</h3>
                            <p class="card-text text-muted mb-0">Jadwal Mengajar</p>
                            <small class="text-success">
                                <i class="fas fa-calendar-check me-1"></i>
                                <span>This Week</span>
                            </small>
                        </div>
                        <div class="icon-container bg-success">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card Total Siswa --}}
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card border-0 shadow-hover" data-aos="fade-up" data-aos-delay="300">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-title text-info mb-1">{{ $totalSiswa }}</h3>
                            <p class="card-text text-muted mb-0">Total Siswa</p>
                            <small class="text-success">
                                <i class="fas fa-users me-1"></i>
                                <span>All Classes</span>
                            </small>
                        </div>
                        <div class="icon-container bg-info">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card Absensi Hari Ini --}}
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card border-0 shadow-hover" data-aos="fade-up" data-aos-delay="400">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-title text-warning mb-1">{{ $totalAbsensiHariIni }}</h3>
                            <p class="card-text text-muted mb-0">Absensi Hari Ini</p>
                            <small class="text-success">
                                <i class="fas fa-clipboard-check me-1"></i>
                                <span>Updated</span>
                            </small>
                        </div>
                        <div class="icon-container bg-warning">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Jadwal Mengajar Hari Ini --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" data-aos="fade-up">
                <div class="card-header bg-transparent border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-dark fw-bold">
                            <i class="fas fa-calendar-day me-2 text-primary"></i>
                            Jadwal Mengajar Hari Ini
                        </h5>
                        <span class="badge bg-primary">{{ count($jadwalHariIni) }} Jadwal</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if(count($jadwalHariIni) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0 ps-4">No</th>
                                        <th class="border-0">Mata Pelajaran</th>
                                        <th class="border-0">Kelas</th>
                                        <th class="border-0">Waktu</th>
                                        <th class="border-0 text-center pe-4">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($jadwalHariIni as $i => $jadwal)
                                        <tr class="schedule-row">
                                            <td class="ps-4 fw-semibold text-muted">{{ $i + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="subject-icon me-3">
                                                        <i class="fas fa-book text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-semibold">{{ $jadwal->mapel }}</h6>
                                                        <small class="text-muted">Mata Pelajaran</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark border">
                                                    <i class="fas fa-school me-1"></i>
                                                    {{ $jadwal->kelas }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="time-slot">
                                                    <i class="fas fa-clock text-warning me-2"></i>
                                                    <span class="fw-semibold">{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</span>
                                                </div>
                                            </td>
                                            <td class="text-center pe-4">
                                                @php
                                                    $currentTime = now()->format('H:i');
                                                    $isActive = $currentTime >= $jadwal->jam_mulai && $currentTime <= $jadwal->jam_selesai;
                                                    $isUpcoming = $currentTime < $jadwal->jam_mulai;
                                                @endphp
                                                <span class="badge {{ $isActive ? 'bg-success' : ($isUpcoming ? 'bg-info' : 'bg-secondary') }}">
                                                    {{ $isActive ? 'Berlangsung' : ($isUpcoming ? 'Akan Datang' : 'Selesai') }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="empty-state">
                                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Tidak ada jadwal mengajar hari ini</h5>
                                <p class="text-muted mb-0">Silakan cek jadwal untuk hari lainnya</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Quick Actions & Info Panel --}}
        <div class="col-lg-4">
            {{-- Quick Actions --}}
            <div class="card border-0 shadow-sm mb-4" data-aos="fade-up" data-aos-delay="100">
                <div class="card-header bg-transparent border-0 py-3">
                    <h5 class="mb-0 text-dark fw-bold">
                        <i class="fas fa-bolt me-2 text-warning"></i>
                        Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-6">
                            <a href="{{ route('guru.penilaian.list') }}" class="btn btn-outline-primary w-100 h-100 py-3 action-btn">
                                <i class="fas fa-chart-bar fa-2x mb-2"></i>
                                <span>Input Nilai</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('guru.siswa.absensi') }}" class="btn btn-outline-success w-100 h-100 py-3 action-btn">
                                <i class="fas fa-clipboard-check fa-2x mb-2"></i>
                                <span>Absensi</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('guru.jadwal.index') }}" class="btn btn-outline-info w-100 h-100 py-3 action-btn">
                                <i class="fas fa-calendar-alt fa-2x mb-2"></i>
                                <span>Jadwal</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('guru.siswa.index') }}" class="btn btn-outline-warning w-100 h-100 py-3 action-btn">
                                <i class="fas fa-user-graduate fa-2x mb-2"></i>
                                <span>Siswa</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Today's Info --}}
            <div class="card border-0 shadow-sm" data-aos="fade-up" data-aos-delay="200">
                <div class="card-header bg-transparent border-0 py-3">
                    <h5 class="mb-0 text-dark fw-bold">
                        <i class="fas fa-info-circle me-2 text-info"></i>
                        Hari Ini
                    </h5>
                </div>
                <div class="card-body">
                    <div class="info-item d-flex align-items-center mb-3">
                        <div class="info-icon bg-primary rounded-circle me-3">
                            <i class="fas fa-calendar text-white"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-semibold">{{ now()->translatedFormat('l, d F Y') }}</h6>
                            <small class="text-muted">Tanggal Hari Ini</small>
                        </div>
                    </div>
                    <div class="info-item d-flex align-items-center mb-3">
                        <div class="info-icon bg-success rounded-circle me-3">
                            <i class="fas fa-clock text-white"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-semibold" id="live-clock">Loading...</h6>
                            <small class="text-muted">Waktu Sekarang</small>
                        </div>
                    </div>
                    <div class="info-item d-flex align-items-center">
                        <div class="info-icon bg-warning rounded-circle me-3">
                            <i class="fas fa-bell text-white"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-semibold">{{ count($jadwalHariIni) }} Jadwal</h6>
                            <small class="text-muted">Total Mengajar Hari Ini</small>
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

/* Schedule Table Styles */
.schedule-row {
    transition: all 0.3s ease;
    border-left: 3px solid transparent;
}

.schedule-row:hover {
    background-color: #f8f9fa;
    border-left-color: #007bff;
    transform: translateX(2px);
}

.subject-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: rgba(0,123,255,0.1);
    display: flex;
    align-items: center;
    justify-content: center;
}

.time-slot {
    padding: 8px 12px;
    background: rgba(255,193,7,0.1);
    border-radius: 8px;
    display: inline-block;
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

/* Empty State */
.empty-state {
    opacity: 0.7;
}

/* Badge Styles */
.badge {
    font-weight: 500;
    padding: 0.5em 1em;
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

    // Schedule row click effect
    const scheduleRows = document.querySelectorAll('.schedule-row');
    scheduleRows.forEach(row => {
        row.style.cursor = 'pointer';
        row.addEventListener('click', function() {
            // Add your click handler here
            console.log('Schedule clicked');
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