@extends('layouts.app')

@section('title', 'Data Siswa')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-2 text-dark fw-bold">Data Siswa</h1>
                    <p class="text-muted mb-0">Manajemen data siswa SMA Pangeran Jayakarta</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary">
                        <i class="fas fa-file-export me-2"></i>Export
                    </button>
                    <button class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Tambah Siswa
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
                            <div class="text-xs fw-semibold text-primary text-uppercase mb-1">
                                Total Siswa</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ $siswas->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-primary opacity-50"></i>
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
                                Siswa Aktif</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ $siswas->count() }}</div>
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
                            <div class="text-xs fw-semibold text-info text-uppercase mb-1">
                                Kelas Terisi</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">
                                {{ $siswas->groupBy('kelas_id')->count() }}
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
                                Rata-rata Per Kelas</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">
                                {{ number_format($siswas->count() / max($siswas->groupBy('kelas_id')->count(), 1), 1) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-bar fa-2x text-warning opacity-50"></i>
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
                    <i class="fas fa-list me-2"></i>Daftar Siswa
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
                            <li><a class="dropdown-item" href="#">Semua Kelas</a></li>
                            <li><a class="dropdown-item" href="#">Kelas X</a></li>
                            <li><a class="dropdown-item" href="#">Kelas XI</a></li>
                            <li><a class="dropdown-item" href="#">Kelas XII</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <!-- Success Message -->
            @if(session('success'))
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
                            <th class="border-0">Siswa</th>
                            <th class="border-0">NIS</th>
                            <th class="border-0">Kelas</th>
                            <th class="border-0">Kontak</th>
                            <th class="border-0">Jenis Kelamin</th>
                            <th class="border-0">TTL</th>
                            <th class="border-0 text-center pe-4" style="width: 100px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($siswas as $siswa)
                            <tr class="border-bottom">
                                <td class="ps-4 fw-semibold text-muted">{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-3">
                                            <i class="fas fa-user text-white"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-semibold text-dark">{{ $siswa->nama }}</h6>
                                            <small class="text-muted">{{ $siswa->user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $siswa->nis }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-primary">
                                        {{ optional($siswa->kelas)->nama_kelas ?? 'Tidak Ada Kelas' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="contact-info">
                                        <div class="text-dark small">{{ $siswa->alamat }}</div>
                                        <div class="text-muted smaller">{{ $siswa->tlp_orang_tua }}</div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge {{ $siswa->jenis_kelamin == 'L' ? 'bg-info' : 'bg-pink' }}">
                                        {{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="birth-info">
                                        <div class="text-dark small">{{ $siswa->tempat_lahir }}</div>
                                        <div class="text-muted smaller">
                                            {{ \Carbon\Carbon::parse($siswa->tanggal_lahir)->format('d/m/Y') }}
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center pe-4">
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary border-secondary-subtle" 
                                                title="Edit" data-bs-toggle="tooltip">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-outline-info border-secondary-subtle" 
                                                title="Detail" data-bs-toggle="tooltip">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-outline-danger border-secondary-subtle" 
                                                title="Hapus" data-bs-toggle="tooltip">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="fas fa-user-graduate fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">Belum ada data siswa</h5>
                                        <p class="text-muted mb-0">Mulai dengan menambahkan siswa baru</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination & Footer -->
            @if($siswas->count() > 0)
            <div class="card-footer bg-transparent border-0 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        Menampilkan {{ $siswas->count() }} dari {{ $siswas->total() }} siswa
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="text-muted small me-2">Baris per halaman:</span>
                        <select class="form-select form-select-sm border-secondary-subtle" style="width: 80px;">
                            <option>10</option>
                            <option>25</option>
                            <option>50</option>
                        </select>
                        
                        <!-- Simple Pagination -->
                        <nav>
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item disabled">
                                    <a class="page-link" href="#">Previous</a>
                                </li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#">Next</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Custom Styles */
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

.bg-pink {
    background-color: #e83e8c !important;
    color: white;
}

.contact-info, .birth-info {
    max-width: 200px;
}

.smaller {
    font-size: 0.75rem;
}

.empty-state {
    opacity: 0.7;
}

.btn-group .btn {
    border-radius: 0.375rem;
    margin: 0 1px;
}

/* Hover Effects */
.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.02);
    transform: translateX(2px);
    transition: all 0.2s ease;
}

/* Badge Styles */
.badge {
    font-weight: 500;
    padding: 0.35em 0.65em;
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
    
    .contact-info, .birth-info {
        max-width: 150px;
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

    // Search functionality
    const searchInput = document.querySelector('input[type="text"]');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }

    // Add smooth animations
    const cards = document.querySelectorAll('.card');
    cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
    });
});
</script>
@endpush