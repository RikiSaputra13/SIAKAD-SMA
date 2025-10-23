@extends('layouts.app')

@section('title', 'List Penilaian')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Daftar Penilaian Siswa
                    </h5>
                </div>
                <div class="card-body p-4">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle me-2"></i>
                                <span>{{ session('success') }}</span>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Filter Section -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-light border-0 py-3">
                            <h6 class="mb-0 text-dark">
                                <i class="fas fa-filter me-2"></i>Filter Data
                            </h6>
                        </div>
                        <div class="card-body p-4">
                            <form method="GET" action="{{ route('guru.penilaian.list') }}" class="row g-3">
                                <div class="col-md-3">
                                    <label for="kelas_id" class="form-label fw-semibold">Kelas</label>
                                    <select name="kelas_id" id="kelas_id" class="form-select border-secondary-subtle">
                                        <option value="">Semua Kelas</option>
                                        @foreach($kelasOptions as $id => $nama)
                                            <option value="{{ $id }}" {{ request('kelas_id') == $id ? 'selected' : '' }}>
                                                {{ $nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="mata_pelajaran" class="form-label fw-semibold">Mata Pelajaran</label>
                                    <select name="mata_pelajaran" id="mata_pelajaran" class="form-select border-secondary-subtle">
                                        <option value="">Semua Mapel</option>
                                        @foreach($mapelOptions as $mapel)
                                            <option value="{{ $mapel }}" {{ request('mata_pelajaran') == $mapel ? 'selected' : '' }}>
                                                {{ $mapel }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="semester" class="form-label fw-semibold">Semester</label>
                                    <select name="semester" id="semester" class="form-select border-secondary-subtle">
                                        <option value="">Semua</option>
                                        <option value="1" {{ request('semester') == '1' ? 'selected' : '' }}>1</option>
                                        <option value="2" {{ request('semester') == '2' ? 'selected' : '' }}>2</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="tahun_ajaran" class="form-label fw-semibold">Tahun Ajaran</label>
                                    <input type="number" name="tahun_ajaran" id="tahun_ajaran" 
                                           class="form-control border-secondary-subtle" 
                                           value="{{ request('tahun_ajaran') ?? date('Y') }}" 
                                           min="2020" max="2030">
                                </div>
                                <div class="col-md-2 d-flex align-items-end gap-2">
                                    <button type="submit" class="btn btn-primary flex-fill">
                                        <i class="fas fa-search me-1"></i>Filter
                                    </button>
                                    <a href="{{ route('guru.penilaian.list') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-refresh"></i>
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body p-3">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <div class="text-xs fw-semibold text-primary text-uppercase mb-1">
                                                Total Siswa Dinilai</div>
                                            <div class="h5 mb-0 fw-bold text-gray-800">{{ $statistik['total_siswa'] }}</div>
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
                                <div class="card-body p-3">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <div class="text-xs fw-semibold text-success text-uppercase mb-1">
                                                Rata-rata Nilai</div>
                                            <div class="h5 mb-0 fw-bold text-gray-800">{{ number_format($statistik['rata_rata'], 2) }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-chart-line fa-2x text-success opacity-50"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body p-3">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <div class="text-xs fw-semibold text-info text-uppercase mb-1">
                                                Nilai Tertinggi</div>
                                            <div class="h5 mb-0 fw-bold text-gray-800">{{ number_format($statistik['tertinggi'], 2) }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-arrow-up fa-2x text-info opacity-50"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body p-3">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <div class="text-xs fw-semibold text-warning text-uppercase mb-1">
                                                Nilai Terendah</div>
                                            <div class="h5 mb-0 fw-bold text-gray-800">{{ number_format($statistik['terendah'], 2) }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-arrow-down fa-2x text-warning opacity-50"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h5 class="mb-1 text-dark">Data Penilaian</h5>
                            <p class="text-muted mb-0 small">Total {{ $penilaian->count() }} data penilaian</p>
                        </div>
                        <a href="{{ route('guru.penilaian.create') }}" class="btn btn-success px-4">
                            <i class="fas fa-plus me-2"></i>Tambah Penilaian
                        </a>
                    </div>

                    <!-- Table -->
                    <div class="card border-0 shadow-sm">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0 ps-4">No</th>
                                        <th class="border-0">NIS</th>
                                        <th class="border-0">Siswa</th>
                                        <th class="border-0">Kelas</th>
                                        <th class="border-0">Mapel</th>
                                        <th class="border-0 text-center">UH</th>
                                        <th class="border-0 text-center">UTS</th>
                                        <th class="border-0 text-center">UAS</th>
                                        <th class="border-0 text-center">Tugas</th>
                                        <th class="border-0 text-center">Nilai Akhir</th>
                                        <th class="border-0 text-center">Predikat</th>
                                        <th class="border-0 text-center">Semester</th>
                                        <th class="border-0 text-center pe-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($penilaian as $item)
                                        <tr class="border-bottom">
                                            <td class="ps-4 fw-semibold text-muted">{{ $loop->iteration }}</td>
                                            <td class="fw-semibold">{{ $item->siswa->nis }}</td>
                                            <td>
                                                <div class="fw-semibold text-dark">{{ $item->siswa->nama }}</div>
                                            </td>
                                            <td class="text-muted">{{ $item->kelas->nama_kelas }}</td>
                                            <td class="fw-semibold text-primary">{{ $item->mata_pelajaran }}</td>
                                            <td class="text-center">
                                                <span class="fw-bold text-dark">{{ $item->nilai_uh }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="fw-bold text-dark">{{ $item->nilai_uts }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="fw-bold text-dark">{{ $item->nilai_uas }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="fw-bold text-dark">{{ $item->nilai_tugas }}</span>
                                            </td>
                                            <td class="text-center">
                                                @php
                                                    $nilaiColor = match(true) {
                                                        $item->nilai_akhir >= 85 => 'text-success fw-bold',
                                                        $item->nilai_akhir >= 70 => 'text-info fw-bold',
                                                        $item->nilai_akhir >= 60 => 'text-warning fw-bold',
                                                        default => 'text-danger fw-bold'
                                                    };
                                                @endphp
                                                <span class="{{ $nilaiColor }}">
                                                    {{ number_format($item->nilai_akhir, 1) }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @php
                                                    $predikatColor = match($item->predikat) {
                                                        'A' => 'text-success fw-bold',
                                                        'B' => 'text-info fw-bold',
                                                        'C' => 'text-warning fw-bold',
                                                        'D' => 'text-danger fw-bold',
                                                        default => 'text-secondary fw-bold'
                                                    };
                                                @endphp
                                                <span class="{{ $predikatColor }}">
                                                    {{ $item->predikat }}
                                                </span>
                                            </td>
                                            <td class="text-center text-muted">
                                                {{ $item->semester }}/{{ $item->tahun_ajaran }}
                                            </td>
                                            <td class="text-center pe-4">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('guru.penilaian.edit', $item->id) }}" 
                                                       class="btn btn-outline-warning border-secondary-subtle" 
                                                       title="Edit" data-bs-toggle="tooltip">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('guru.penilaian.destroy', $item->id) }}" 
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger border-secondary-subtle" 
                                                                onclick="return confirm('Apakah Anda yakin ingin menghapus penilaian ini?')"
                                                                title="Hapus" data-bs-toggle="tooltip">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="13" class="text-center py-5">
                                                <div class="text-muted">
                                                    <i class="fas fa-inbox fa-3x mb-3 opacity-50"></i>
                                                    <br>
                                                    <h5 class="fw-semibold">Belum ada data penilaian</h5>
                                                    <p class="mb-0">Mulai dengan menambahkan penilaian baru</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Export Buttons -->
                    @if($penilaian->count() > 0)
                    <div class="mt-4 d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            Menampilkan {{ $penilaian->count() }} data penilaian
                        </div>
                        <div class="d-flex gap-2">
                            <a href="#" class="btn btn-outline-primary">
                                <i class="fas fa-file-excel me-2"></i>Export Excel
                            </a>
                            <a href="#" class="btn btn-outline-danger">
                                <i class="fas fa-file-pdf me-2"></i>Export PDF
                            </a>
                        </div>
                    </div>
                    @endif
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
    }
    
    .table td {
        vertical-align: middle;
        padding: 1rem 0.75rem;
    }
    
    .btn-group .btn {
        border-radius: 0.375rem;
        margin: 0 2px;
    }
    
    .border-secondary-subtle {
        border-color: #e9ecef !important;
    }
    
    .shadow-sm {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
    }
</style>
@endpush

@push('scripts')
<script>
    // Auto submit form when filter changes
    document.addEventListener('DOMContentLoaded', function() {
        const kelasSelect = document.getElementById('kelas_id');
        const mapelSelect = document.getElementById('mata_pelajaran');
        
        if (kelasSelect) {
            kelasSelect.addEventListener('change', function() {
                this.form.submit();
            });
        }
        
        if (mapelSelect) {
            mapelSelect.addEventListener('change', function() {
                this.form.submit();
            });
        }

        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });

    // Confirm delete
    function confirmDelete() {
        return confirm('Apakah Anda yakin ingin menghapus penilaian ini?');
    }
</script>
@endpush