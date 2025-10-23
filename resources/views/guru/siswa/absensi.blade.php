@extends('layouts.app')

@section('title', 'Daftar Absensi')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-2 text-dark fw-bold">Data Absensi Siswa</h1>
                    <p class="text-muted mb-0">Manajemen dan monitoring kehadiran siswa</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary" id="btnLihatRekap">
                        <i class="fas fa-chart-bar me-2"></i>Rekap Absensi
                    </button>
                    <button class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Tambah Absensi
                    </button>
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
                            <div class="text-xs fw-semibold text-success text-uppercase mb-1">
                                Total Hadir</div>
                            <div class="h5 mb-0 fw-bold text-gray-800" id="statsHadir">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-success opacity-50"></i>
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
                                Total Sakit</div>
                            <div class="h5 mb-0 fw-bold text-gray-800" id="statsSakit">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-procedures fa-2x text-warning opacity-50"></i>
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
                                Total Izin</div>
                            <div class="h5 mb-0 fw-bold text-gray-800" id="statsIzin">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-envelope fa-2x text-info opacity-50"></i>
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
                            <div class="text-xs fw-semibold text-danger text-uppercase mb-1">
                                Total Alpha</div>
                            <div class="h5 mb-0 fw-bold text-gray-800" id="statsAlpha">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-times fa-2x text-danger opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-dark fw-bold">
                    <i class="fas fa-list me-2"></i>Daftar Absensi
                </h5>
                <div class="d-flex gap-2">
                    <!-- Search Box -->
                    <div class="input-group input-group-sm" style="width: 250px;">
                        <input type="text" class="form-control border-secondary-subtle" placeholder="Cari siswa...">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    
                    <!-- Filter Button -->
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-filter me-1"></i>Filter
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Semua Status</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#">Hadir</a></li>
                            <li><a class="dropdown-item" href="#">Sakit</a></li>
                            <li><a class="dropdown-item" href="#">Izin</a></li>
                            <li><a class="dropdown-item" href="#">Alpha</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm m-4 mb-0" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle me-2"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm m-4 mb-0" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <span>{{ session('error') }}</span>
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
                            <th class="border-0">Siswa</th>
                            <th class="border-0">Kelas</th>
                            <th class="border-0">Tanggal</th>
                            <th class="border-0">Status</th>
                            <th class="border-0">Keterangan</th>
                            <th class="border-0 text-center pe-4" style="width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($absensis as $absensi)
                            <tr class="border-bottom">
                                <td class="ps-4 fw-semibold text-muted">{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-3">
                                            <i class="fas fa-user text-white"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-semibold text-dark">{{ $absensi->siswa->nama }}</h6>
                                            <small class="text-muted">{{ $absensi->siswa->nis }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-primary">
                                        {{ $absensi->siswa->kelas->nama_kelas ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="date-info">
                                        <div class="text-dark fw-semibold">
                                            {{ \Carbon\Carbon::parse($absensi->tanggal)->format('d/m/Y') }}
                                        </div>
                                        <small class="text-muted">
                                            {{ \Carbon\Carbon::parse($absensi->tanggal)->translatedFormat('l') }}
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $status = $absensi->status;
                                        $badgeConfig = [
                                            'Hadir' => ['class' => 'bg-success', 'icon' => 'fa-check'],
                                            'Sakit' => ['class' => 'bg-warning', 'icon' => 'fa-procedures'],
                                            'Izin' => ['class' => 'bg-info', 'icon' => 'fa-envelope'],
                                            'Alpha' => ['class' => 'bg-danger', 'icon' => 'fa-times']
                                        ];
                                        $config = $badgeConfig[$status] ?? ['class' => 'bg-secondary', 'icon' => 'fa-question'];
                                    @endphp
                                    <span class="badge {{ $config['class'] }}">
                                        <i class="fas {{ $config['icon'] }} me-1"></i>
                                        {{ $status }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-muted small">
                                        {{ $absensi->keterangan_izin ?? '-' }}
                                    </span>
                                </td>
                                <td class="text-center pe-4">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.absensi.edit', $absensi->id) }}" 
                                           class="btn btn-outline-primary border-secondary-subtle" 
                                           title="Edit" data-bs-toggle="tooltip">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.absensi.destroy', $absensi->id) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-outline-danger border-secondary-subtle" 
                                                    onclick="return confirm('Yakin hapus absensi ini?')"
                                                    title="Hapus" data-bs-toggle="tooltip">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">Belum ada data absensi</h5>
                                        <p class="text-muted mb-0">Mulai dengan menambahkan absensi baru</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination & Footer -->
            @if($absensis->count() > 0)
            <div class="card-footer bg-transparent border-0 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        Menampilkan {{ $absensis->count() }} data absensi
                    </div>
                    <!-- Add pagination links if needed -->
                    {{ $absensis->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Rekap -->
<div class="modal fade" id="rekapModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-chart-pie me-2"></i>Rekap Absensi Siswa
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <!-- Filter Section -->
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Dari Tanggal</label>
                        <input type="date" id="start_date" class="form-control border-secondary-subtle">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Sampai Tanggal</label>
                        <input type="date" id="end_date" class="form-control border-secondary-subtle">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Kelas</label>
                        <select id="kelas_id" class="form-select border-secondary-subtle">
                            <option value="">Semua Kelas</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button class="btn btn-primary w-100" id="btnFilter">
                            <i class="fas fa-filter me-2"></i>Terapkan Filter
                        </button>
                    </div>
                </div>

                <!-- Export Buttons -->
                <div class="d-flex gap-2 mb-4">
                    <button class="btn btn-success" id="btnCetakPdf">
                        <i class="fas fa-file-pdf me-2"></i>Cetak PDF
                    </button>
                    <button class="btn btn-success" id="btnCetakExcel">
                        <i class="fas fa-file-excel me-2"></i>Cetak Excel
                    </button>
                </div>

                <!-- Rekap Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="rekapTable">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-center">No</th>
                                <th>NIS</th>
                                <th>Nama Siswa</th>
                                <th class="text-center">Hadir</th>
                                <th class="text-center">Sakit</th>
                                <th class="text-center">Izin</th>
                                <th class="text-center">Alpha</th>
                                <th class="text-center">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    <i class="fas fa-search me-2"></i>Gunakan filter untuk menampilkan data
                                </td>
                            </tr>
                        </tbody>
                        <tfoot class="bg-light fw-semibold">
                            <tr>
                                <th colspan="3" class="text-end">Total Keseluruhan</th>
                                <th class="text-center" id="totalHadir">0</th>
                                <th class="text-center" id="totalSakit">0</th>
                                <th class="text-center" id="totalIzin">0</th>
                                <th class="text-center" id="totalAlpha">0</th>
                                <th class="text-center" id="grandTotal">0</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
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

.avatar-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
}

.border-secondary-subtle {
    border-color: #e9ecef !important;
}

.shadow-sm {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
}

.date-info {
    min-width: 100px;
}

.empty-state {
    opacity: 0.7;
}

.btn-group .btn {
    border-radius: 0.375rem;
    margin: 0 1px;
}

/* Badge Styles */
.badge {
    font-weight: 500;
    padding: 0.5em 0.8em;
    font-size: 0.8rem;
}

/* Hover Effects */
.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.02);
    transform: translateX(2px);
    transition: all 0.2s ease;
}

/* Modal Styles */
.modal-content {
    border-radius: 1rem;
}

.modal-header {
    border-radius: 1rem 1rem 0 0;
    padding: 1.5rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .card-header .d-flex {
        flex-direction: column;
        gap: 1rem;
        align-items: start !important;
    }
    
    .input-group {
        width: 100% !important;
    }
    
    .btn-group .btn {
        padding: 0.25rem 0.5rem;
    }
}

/* Loading Animation */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.table tbody tr {
    animation: fadeIn 0.3s ease-in;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Calculate and update statistics
    function updateStatistics() {
        const stats = {
            Hadir: document.querySelectorAll('.badge.bg-success').length,
            Sakit: document.querySelectorAll('.badge.bg-warning').length,
            Izin: document.querySelectorAll('.badge.bg-info').length,
            Alpha: document.querySelectorAll('.badge.bg-danger').length
        };

        document.getElementById('statsHadir').textContent = stats.Hadir;
        document.getElementById('statsSakit').textContent = stats.Sakit;
        document.getElementById('statsIzin').textContent = stats.Izin;
        document.getElementById('statsAlpha').textContent = stats.Alpha;
    }

    // Update statistics on page load
    updateStatistics();

    // Modal functionality
    const rekapModal = new bootstrap.Modal(document.getElementById('rekapModal'));
    const btnLihatRekap = document.getElementById('btnLihatRekap');
    const btnFilter = document.getElementById('btnFilter');
    const btnCetakPdf = document.getElementById('btnCetakPdf');
    const btnCetakExcel = document.getElementById('btnCetakExcel');
    const rekapTableBody = document.querySelector('#rekapTable tbody');

    function fetchRekap(start = '', end = '', kelas_id = '') {
        rekapTableBody.innerHTML = `
            <tr>
                <td colspan="8" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Memuat data...</p>
                </td>
            </tr>
        `;

        fetch(`{{ route('admin.absensi.rekap') }}?start_date=${start}&end_date=${end}&kelas_id=${kelas_id}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            credentials: 'same-origin'
        })
        .then(res => res.json())
        .then(res => {
            rekapTableBody.innerHTML = '';
            if(res.success){
                let i = 1;
                let stats = {Hadir:0, Sakit:0, Izin:0, Alpha:0, total:0};

                if(res.rekap.length === 0){
                    rekapTableBody.innerHTML = `
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="fas fa-inbox me-2"></i>Tidak ada data untuk filter yang dipilih
                            </td>
                        </tr>
                    `;
                }

                res.rekap.forEach(item => {
                    const total = item.Hadir + item.Sakit + item.Izin + item.Alpha;
                    stats.Hadir += item.Hadir;
                    stats.Sakit += item.Sakit;
                    stats.Izin += item.Izin;
                    stats.Alpha += item.Alpha;
                    stats.total += total;

                    rekapTableBody.innerHTML += `
                        <tr>
                            <td class="text-center">${i++}</td>
                            <td>${item.nis}</td>
                            <td>${item.nama_siswa}</td>
                            <td class="text-center">${item.Hadir}</td>
                            <td class="text-center">${item.Sakit}</td>
                            <td class="text-center">${item.Izin}</td>
                            <td class="text-center">${item.Alpha}</td>
                            <td class="text-center fw-bold">${total}</td>
                        </tr>
                    `;
                });

                document.getElementById('totalHadir').textContent = stats.Hadir;
                document.getElementById('totalSakit').textContent = stats.Sakit;
                document.getElementById('totalIzin').textContent = stats.Izin;
                document.getElementById('totalAlpha').textContent = stats.Alpha;
                document.getElementById('grandTotal').textContent = stats.total;
            } else {
                rekapTableBody.innerHTML = `
                    <tr>
                        <td colspan="8" class="text-center py-4 text-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>Gagal memuat data
                        </td>
                    </tr>
                `;
            }
        })
        .catch(err => {
            console.error(err);
            rekapTableBody.innerHTML = `
                <tr>
                    <td colspan="8" class="text-center py-4 text-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>Terjadi kesalahan server
                    </td>
                </tr>
            `;
        });
    }

    btnLihatRekap.addEventListener('click', () => {
        rekapModal.show();
        // Set default dates
        const today = new Date().toISOString().split('T')[0];
        const firstDay = new Date(new Date().getFullYear(), new Date().getMonth(), 1).toISOString().split('T')[0];
        document.getElementById('start_date').value = firstDay;
        document.getElementById('end_date').value = today;
        fetchRekap(firstDay, today);
    });

    btnFilter.addEventListener('click', () => {
        const start = document.getElementById('start_date').value;
        const end = document.getElementById('end_date').value;
        const kelas_id = document.getElementById('kelas_id').value;
        fetchRekap(start, end, kelas_id);
    });

    btnCetakPdf.addEventListener('click', () => {
        const start = document.getElementById('start_date').value;
        const end = document.getElementById('end_date').value;
        const kelas_id = document.getElementById('kelas_id').value;
        window.open(`{{ route('admin.absensi.rekap.cetak') }}?start_date=${start}&end_date=${end}&kelas_id=${kelas_id}`, '_blank');
    });

    btnCetakExcel.addEventListener('click', () => {
        const start = document.getElementById('start_date').value;
        const end = document.getElementById('end_date').value;
        const kelas_id = document.getElementById('kelas_id').value;
        window.location.href = `{{ route('admin.absensi.export-excel') }}?start_date=${start}&end_date=${end}&kelas_id=${kelas_id}`;
    });
});
</script>
@endpush