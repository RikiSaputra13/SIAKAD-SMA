@extends('layouts.app')

@section('title', 'List Penilaian')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>List Penilaian
                    </h5>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Filter Section -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="fas fa-filter me-2"></i>Filter Data
                            </h6>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="{{ route('guru.penilaian.list') }}" class="row g-3">
                                <div class="col-md-3">
                                    <label for="kelas_id" class="form-label">Kelas</label>
                                    <select name="kelas_id" id="kelas_id" class="form-select">
                                        <option value="">Semua Kelas</option>
                                        @foreach($kelasOptions as $id => $nama)
                                            <option value="{{ $id }}" {{ request('kelas_id') == $id ? 'selected' : '' }}>
                                                {{ $nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="mata_pelajaran" class="form-label">Mata Pelajaran</label>
                                    <select name="mata_pelajaran" id="mata_pelajaran" class="form-select">
                                        <option value="">Semua Mapel</option>
                                        @foreach($mapelOptions as $mapel)
                                            <option value="{{ $mapel }}" {{ request('mata_pelajaran') == $mapel ? 'selected' : '' }}>
                                                {{ $mapel }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="semester" class="form-label">Semester</label>
                                    <select name="semester" id="semester" class="form-select">
                                        <option value="">Semua</option>
                                        <option value="1" {{ request('semester') == '1' ? 'selected' : '' }}>1</option>
                                        <option value="2" {{ request('semester') == '2' ? 'selected' : '' }}>2</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="tahun_ajaran" class="form-label">Tahun Ajaran</label>
                                    <input type="number" name="tahun_ajaran" id="tahun_ajaran" 
                                           class="form-control" value="{{ request('tahun_ajaran') ?? date('Y') }}" 
                                           min="2020" max="2030">
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="fas fa-search me-1"></i>Filter
                                    </button>
                                    <a href="{{ route('guru.penilaian.list') }}" class="btn btn-secondary">
                                        <i class="fas fa-refresh me-1"></i>Reset
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Total Siswa</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistik['total_siswa'] }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-users fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Rata-rata Nilai</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($statistik['rata_rata'], 2) }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                Nilai Tertinggi</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($statistik['tertinggi'], 2) }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-arrow-up fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Nilai Terendah</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($statistik['terendah'], 2) }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-arrow-down fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Data Penilaian</h5>
                        <a href="{{ route('guru.penilaian.create') }}" class="btn btn-success">
                            <i class="fas fa-plus me-1"></i>Tambah Penilaian
                        </a>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped">
                            <thead class="bg-light">
                                <tr>
                                    <th>No</th>
                                    <th>Siswa</th>
                                    <th>Kelas</th>
                                    <th>Mata Pelajaran</th>
                                    <th>UH</th>
                                    <th>UTS</th>
                                    <th>UAS</th>
                                    <th>Tugas</th>
                                    <th>Nilai Akhir</th>
                                    <th>Predikat</th>
                                    <th>Semester</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($penilaian as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->siswa->nama }}</td>
                                        <td>{{ $item->kelas->nama_kelas }}</td>
                                        <td>{{ $item->mata_pelajaran }}</td>
                                        <td class="text-center">{{ $item->nilai_uh }}</td>
                                        <td class="text-center">{{ $item->nilai_uts }}</td>
                                        <td class="text-center">{{ $item->nilai_uas }}</td>
                                        <td class="text-center">{{ $item->nilai_tugas }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-{{ $this->getBadgeColor($item->nilai_akhir) }} fs-6">
                                                {{ number_format($item->nilai_akhir, 2) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-{{ $this->getPredikatColor($item->predikat) }}">
                                                {{ $item->predikat }}
                                            </span>
                                        </td>
                                        <td class="text-center">{{ $item->semester }}/{{ $item->tahun_ajaran }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('guru.penilaian.show', $item->id) }}" 
                                                   class="btn btn-info" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('guru.penilaian.edit', $item->id) }}" 
                                                   class="btn btn-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('guru.penilaian.destroy', $item->id) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" 
                                                            onclick="return confirm('Apakah Anda yakin ingin menghapus penilaian ini?')"
                                                            title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="12" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                                <br>
                                                Belum ada data penilaian.
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Export Buttons -->
                    @if($penilaian->count() > 0)
                    <div class="mt-3">
                        <a href="#" class="btn btn-outline-primary">
                            <i class="fas fa-file-excel me-1"></i>Export Excel
                        </a>
                        <a href="#" class="btn btn-outline-danger">
                            <i class="fas fa-file-pdf me-1"></i>Export PDF
                        </a>
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
        border: none;
        border-radius: 0.5rem;
    }
    
    .table th {
        border-top: none;
        font-weight: 600;
    }
    
    .badge {
        font-size: 0.8em;
        padding: 0.4em 0.6em;
    }
</style>
@endpush

@push('scripts')
<script>
    // Auto submit form when filter changes
    document.getElementById('kelas_id').addEventListener('change', function() {
        this.form.submit();
    });
    
    document.getElementById('mata_pelajaran').addEventListener('change', function() {
        this.form.submit();
    });

    // Confirm delete
    function confirmDelete() {
        return confirm('Apakah Anda yakin ingin menghapus penilaian ini?');
    }
</script>
@endpush

<?php
// Helper functions for blade
if (!function_exists('getBadgeColor')) {
    function getBadgeColor($nilai) {
        if ($nilai >= 85) return 'success';
        if ($nilai >= 70) return 'info';
        if ($nilai >= 60) return 'warning';
        return 'danger';
    }
}

if (!function_exists('getPredikatColor')) {
    function getPredikatColor($predikat) {
        switch ($predikat) {
            case 'A': return 'success';
            case 'B': return 'info';
            case 'C': return 'warning';
            case 'D': return 'danger';
            default: return 'secondary';
        }
    }
}
?>