@extends('siswa.layouts.app')

@section('title', 'Dashboard Saya')
@section('header', 'Dashboard Saya')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Welcome Section -->
        <div class="col-12 mb-4">
            <div class="card border-0 shadow-sm bg-gradient-primary text-white">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-2">Selamat Datang, {{ Auth::user()->name }}! 👋</h2>
                            <p class="mb-0 opacity-75">
                                <i class="fas fa-calendar-day me-1"></i>
                                {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <div class="bg-white bg-opacity-20 rounded p-3 d-inline-block">
                                <div class="h4 mb-0 fw-bold">{{ $siswa->kelas->nama_kelas ?? '-' }}</div>
                                <small class="opacity-75">Kelas Anda</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 card-hover">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs fw-semibold text-primary text-uppercase mb-1">Kehadiran Bulan Ini</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">
                                {{ $statistik['hadir_bulan'] }} Hari
                            </div>
                            <div class="mt-2">
                                <span class="badge bg-success bg-opacity-10 text-success">
                                    <i class="fas fa-chart-line me-1"></i>
                                    {{ $statistik['persentase_hadir'] }}%
                                </span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-user-check fa-2x text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0">
                    <a href="{{ route('siswa.absensi.index') }}" class="text-primary text-decoration-none small">
                        Lihat Detail <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 card-hover">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs fw-semibold text-success text-uppercase mb-1">Rata-rata Nilai</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">
                                {{ $statistik['rata_nilai'] }}
                            </div>
                            <div class="mt-2">
                                <span class="badge bg-info bg-opacity-10 text-info">
                                    {{ $statistik['total_mapel'] }} Mapel
                                </span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-chart-line fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0">
                    <a href="{{ route('siswa.nilai.index') }}" class="text-success text-decoration-none small">
                        Lihat Nilai <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 card-hover">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs fw-semibold text-info text-uppercase mb-1">Jadwal Hari Ini</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">
                                {{ $statistik['jadwal_hari_ini'] }} Mapel
                            </div>
                            <div class="mt-2">
                                <span class="badge bg-primary bg-opacity-10 text-primary">
                                    {{ $jadwalSekarangNama }}
                                </span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="bg-info bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-calendar-alt fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0">
                    <a href="{{ route('siswa.jadwal.index') }}" class="text-info text-decoration-none small">
                        Lihat Jadwal <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Jadwal Hari Ini -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-light border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-clock me-2 text-primary"></i>Jadwal Hari Ini
                    </h5>
                    <span class="badge bg-primary">{{ $jadwalHariIni->count() }} Mapel</span>
                </div>
                <div class="card-body p-0">
                    @if($jadwalHariIni->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($jadwalHariIni as $index => $jadwal)
                                <div class="list-group-item border-0 py-3 {{ $index % 2 === 0 ? 'bg-light' : '' }}">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 rounded p-2 me-3">
                                            <i class="fas fa-book text-primary"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 text-dark">{{ $jadwal->mata_pelajaran }}</h6>
                                            <small class="text-muted">
                                                <i class="fas fa-user me-1"></i>{{ $jadwal->guru->nama ?? 'Guru' }}
                                                • 
                                                <i class="fas fa-clock me-1"></i>{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}
                                            </small>
                                        </div>
                                        <span class="badge bg-light text-dark border">{{ $jadwal->ruangan ?? 'Kelas' }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-0">Tidak ada jadwal hari ini</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Absensi Terbaru -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-light border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-user-check me-2 text-success"></i>Riwayat Absensi Terbaru
                    </h5>
                    <span class="badge bg-success">{{ $absensi->count() }} Data</span>
                </div>
                <div class="card-body p-0">
                    @if($absensi->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($absensi as $index => $absen)
                                <div class="list-group-item border-0 py-3 {{ $index % 2 === 0 ? 'bg-light' : '' }}">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <h6 class="mb-1 text-dark">
                                                {{ \Carbon\Carbon::parse($absen->tanggal)->isoFormat('D MMMM YYYY') }}
                                            </h6>
                                            <small class="text-muted">
                                                {{ $absen->mapel ?? 'Umum' }} • 
                                                {{ $absen->jam_mulai ?? '' }}
                                            </small>
                                        </div>
                                        @php
                                            $statusColor = match($absen->status) {
                                                'Hadir' => 'success',
                                                'Izin' => 'info',
                                                'Sakit' => 'warning',
                                                'Alpha' => 'danger',
                                                default => 'secondary'
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $statusColor }}">
                                            {{ $absen->status }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-0">Belum ada data absensi</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Kehadiran -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0 py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2 text-info"></i>Statistik Kehadiran {{ date('Y') }}
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="attendanceChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0 py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-rocket me-2 text-warning"></i>Akses Cepat
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('siswa.nilai.index') }}" class="btn btn-outline-primary btn-lg w-100 py-3">
                                <i class="fas fa-chart-line fa-2x mb-2"></i>
                                <br>
                                <span>Lihat Nilai</span>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('siswa.jadwal.index') }}" class="btn btn-outline-success btn-lg w-100 py-3">
                                <i class="fas fa-calendar-alt fa-2x mb-2"></i>
                                <br>
                                <span>Jadwal Pelajaran</span>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('siswa.absensi.index') }}" class="btn btn-outline-info btn-lg w-100 py-3">
                                <i class="fas fa-user-check fa-2x mb-2"></i>
                                <br>
                                <span>Absensi</span>
                            </a>
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
    .card-hover {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
    }
    
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    }
    
    .bg-opacity-10 {
        background-color: rgba(var(--bs-primary-rgb), 0.1) !important;
    }
    
    .btn-outline-primary:hover {
        transform: translateY(-2px);
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Attendance Chart
        const ctx = document.getElementById('attendanceChart').getContext('2d');
        const attendanceChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($bulanLabels),
                datasets: [
                    {
                        label: 'Hadir',
                        data: @json($chartData['Hadir']),
                        backgroundColor: 'rgba(40, 167, 69, 0.8)',
                        borderColor: 'rgba(40, 167, 69, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Izin',
                        data: @json($chartData['Izin']),
                        backgroundColor: 'rgba(255, 193, 7, 0.8)',
                        borderColor: 'rgba(255, 193, 7, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Sakit',
                        data: @json($chartData['Sakit']),
                        backgroundColor: 'rgba(23, 162, 184, 0.8)',
                        borderColor: 'rgba(23, 162, 184, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Alpha',
                        data: @json($chartData['Alpha']),
                        backgroundColor: 'rgba(220, 53, 69, 0.8)',
                        borderColor: 'rgba(220, 53, 69, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Add hover effects
        const cards = document.querySelectorAll('.card-hover');
        cards.forEach((card) => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
            });
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    });
</script>
@endpush