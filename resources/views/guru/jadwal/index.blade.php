@extends('layouts.app')

@section('title', 'Jadwal Mengajar Saya')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-2 text-dark fw-bold">Jadwal Mengajar</h1>
                    <p class="text-muted mb-0">Jadwal mengajar {{ auth()->user()->name ?? auth()->user()->nama }}</p>
                </div>
                <div class="text-end">
                    <span class="badge bg-primary fs-6 px-3 py-2">
                        <i class="fas fa-calendar me-2"></i>
                        {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs fw-semibold text-primary text-uppercase mb-1">
                                Total Jadwal</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ $jadwals->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-alt fa-2x text-primary opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs fw-semibold text-success text-uppercase mb-1">
                                Hari Ini</div>
                            <div class="h5 mb-0 fw-bold text-gray-800" id="jadwalHariIni">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-success opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs fw-semibold text-info text-uppercase mb-1">
                                Kelas Diampu</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">
                                {{ $jadwals->groupBy('kelas_id')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-school fa-2x text-info opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs fw-semibold text-warning text-uppercase mb-1">
                                Mata Pelajaran</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">
                                {{ $jadwals->groupBy('mata_pelajaran')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-warning opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-transparent border-0 py-3">
            <h5 class="mb-0 text-dark fw-bold">
                <i class="fas fa-filter me-2"></i>Filter Jadwal
            </h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('guru.jadwal.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="kelas_id" class="form-label fw-semibold">Kelas</label>
                    <select name="kelas_id" id="kelas_id" class="form-select border-secondary-subtle">
                        <option value="">Semua Kelas</option>
                        @foreach($kelas as $kelasItem)
                            <option value="{{ $kelasItem->id }}" {{ request('kelas_id') == $kelasItem->id ? 'selected' : '' }}>
                                {{ $kelasItem->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="hari" class="form-label fw-semibold">Hari</label>
                    <select name="hari" id="hari" class="form-select border-secondary-subtle">
                        <option value="">Semua Hari</option>
                        <option value="Senin" {{ request('hari') == 'Senin' ? 'selected' : '' }}>Senin</option>
                        <option value="Selasa" {{ request('hari') == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                        <option value="Rabu" {{ request('hari') == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                        <option value="Kamis" {{ request('hari') == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                        <option value="Jumat" {{ request('hari') == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                        <option value="Sabtu" {{ request('hari') == 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="fas fa-search me-2"></i>Terapkan Filter
                    </button>
                    <a href="{{ route('guru.jadwal.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-refresh"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Main Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-dark fw-bold">
                    <i class="fas fa-list me-2"></i>Daftar Jadwal Mengajar
                </h5>
                <div class="text-muted small">
                    {{ $jadwals->count() }} jadwal ditemukan
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <!-- Success Message -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm m-4 mb-0" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle me-2"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 ps-4" style="width: 60px;">No</th>
                            <th class="border-0">Mata Pelajaran</th>
                            <th class="border-0">Kelas</th>
                            <th class="border-0">Hari</th>
                            <th class="border-0">Waktu</th>
                            <th class="border-0">Ruangan</th>
                            <th class="border-0 text-center pe-4" style="width: 100px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jadwals as $jadwal)
                            <tr class="border-bottom schedule-row">
                                <td class="ps-4 fw-semibold text-muted">{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="subject-icon me-3">
                                            <i class="fas fa-book text-primary"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-semibold text-dark">{{ $jadwal->mata_pelajaran }}</h6>
                                            <small class="text-muted">Mata Pelajaran</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-primary">
                                        <i class="fas fa-school me-1"></i>
                                        {{ optional($jadwal->kelas)->nama_kelas ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="day-info">
                                        <span class="fw-semibold text-dark">{{ $jadwal->hari }}</span>
                                        @php
                                            // Gunakan helper function atau variable dari controller
                                            $isToday = $jadwal->hari === ($hariIni ?? \App\Helpers\DateHelper::hariIni());
                                        @endphp
                                        @if($isToday)
                                            <small class="text-success d-block">
                                                <i class="fas fa-circle me-1"></i>Hari ini
                                            </small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="time-slot">
                                        <i class="fas fa-clock text-warning me-2"></i>
                                        <span class="fw-semibold">{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        <i class="fas fa-door-open me-1"></i>
                                        {{ $jadwal->ruangan ?? 'Tidak ada' }}
                                    </span>
                                </td>
                                <td class="text-center pe-4">
                                    @php
                                        $currentTime = now()->format('H:i');
                                        $isActive = $currentTime >= $jadwal->jam_mulai && $currentTime <= $jadwal->jam_selesai;
                                        $isUpcoming = $currentTime < $jadwal->jam_mulai;
                                        $isToday = $jadwal->hari === ($hariIni ?? \App\Helpers\DateHelper::hariIni());
                                    @endphp
                                    <span class="badge {{ $isToday && $isActive ? 'bg-success' : ($isToday && $isUpcoming ? 'bg-info' : 'bg-secondary') }}">
                                        {{ $isToday && $isActive ? 'Berlangsung' : ($isToday && $isUpcoming ? 'Akan Datang' : 'Terjadwal') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">Belum ada jadwal mengajar</h5>
                                        <p class="text-muted mb-0">Jadwal mengajar akan muncul di sini</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Styles tetap sama seperti sebelumnya */
.card {
    border-radius: 0.75rem;
}

.table th {
    font-weight: 600;
    font-size: 0.875rem;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-top: none;
}

.table td {
    vertical-align: middle;
    padding: 1rem 0.75rem;
    border-color: #f8f9fa;
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

.border-secondary-subtle {
    border-color: #e9ecef !important;
}

.shadow-sm {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
}

.time-slot {
    padding: 8px 12px;
    background: rgba(255,193,7,0.1);
    border-radius: 8px;
    display: inline-block;
}

.day-info {
    min-width: 80px;
}

.empty-state {
    opacity: 0.7;
}

/* Hover Effects */
.schedule-row {
    transition: all 0.3s ease;
    border-left: 3px solid transparent;
}

.schedule-row:hover {
    background-color: #f8f9fa;
    border-left-color: #007bff;
    transform: translateX(2px);
}

/* Badge Styles */
.badge {
    font-weight: 500;
    padding: 0.5em 0.8em;
}

/* Responsive Design */
@media (max-width: 768px) {
    .card-header .d-flex {
        flex-direction: column;
        gap: 1rem;
        align-items: start !important;
    }
    
    .subject-icon {
        width: 35px;
        height: 35px;
        font-size: 0.8rem;
    }
    
    .time-slot {
        padding: 6px 10px;
        font-size: 0.875rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Calculate today's schedule count
    function calculateTodaysSchedule() {
        const todayIndicators = document.querySelectorAll('.day-info .text-success');
        document.getElementById('jadwalHariIni').textContent = todayIndicators.length;
    }

    // Initialize
    calculateTodaysSchedule();

    // Add interactive effects
    const scheduleRows = document.querySelectorAll('.schedule-row');
    scheduleRows.forEach(row => {
        row.style.cursor = 'pointer';
        row.addEventListener('click', function() {
            // Add your click handler here
            console.log('Schedule clicked:', this);
        });
    });

    // Auto-refresh time-based status every minute
    setInterval(() => {
        const currentTime = new Date().toLocaleTimeString('id-ID', { 
            hour: '2-digit', 
            minute: '2-digit',
            hour12: false 
        });
        
        scheduleRows.forEach(row => {
            const timeCell = row.querySelector('.time-slot');
            const statusBadge = row.querySelector('.badge');
            
            if (timeCell && statusBadge) {
                const timeText = timeCell.textContent;
                const times = timeText.split(' - ');
                if (times.length === 2) {
                    const [startTime, endTime] = times;
                    
                    const isToday = row.querySelector('.text-success') !== null;
                    const isActive = isToday && currentTime >= startTime && currentTime <= endTime;
                    const isUpcoming = isToday && currentTime < startTime;
                    
                    if (isActive) {
                        statusBadge.className = 'badge bg-success';
                        statusBadge.textContent = 'Berlangsung';
                    } else if (isUpcoming) {
                        statusBadge.className = 'badge bg-info';
                        statusBadge.textContent = 'Akan Datang';
                    } else {
                        statusBadge.className = 'badge bg-secondary';
                        statusBadge.textContent = 'Terjadwal';
                    }
                }
            }
        });
    }, 60000); // Update every minute
});
</script>
@endpush