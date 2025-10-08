@extends('layouts.app')

@section('title', 'Ujian Akhir Semester')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-purple text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-file-contract me-2"></i>Ujian Akhir Semester
                    </h5>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Filter Section -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="fas fa-filter me-2"></i>Filter Ujian
                            </h6>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="{{ route('guru.penilaian.uas.index') }}" class="row g-3">
                                <div class="col-md-4">
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
                                <div class="col-md-4">
                                    <label for="status" class="form-label">Status</label>
                                    <select name="status" id="status" class="form-select">
                                        <option value="">Semua Status</option>
                                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    </select>
                                </div>
                                <div class="col-md-4 d-flex align-items-end">
                                    <button type="submit" class="btn btn-purple me-2">
                                        <i class="fas fa-search me-1"></i>Filter
                                    </button>
                                    <a href="{{ route('guru.penilaian.uas.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-refresh me-1"></i>Reset
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-purple shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-purple text-uppercase mb-1">
                                                Total UAS</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistik['total'] }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-file-contract fa-2x text-gray-300"></i>
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
                                                Draft</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistik['draft'] }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-edit fa-2x text-gray-300"></i>
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
                                                Published</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistik['published'] }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Completed</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistik['completed'] }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-flag-checkered fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2"></i>Daftar Ujian Akhir Semester
                        </h5>
                        <a href="{{ route('guru.penilaian.uas.create') }}" class="btn btn-success">
                            <i class="fas fa-plus me-1"></i>Buat UAS
                        </a>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped">
                            <thead class="bg-light">
                                <tr>
                                    <th width="50">No</th>
                                    <th>Judul Ujian</th>
                                    <th>Kelas</th>
                                    <th>Mata Pelajaran</th>
                                    <th width="120">Berkas Soal</th>
                                    <th width="100">Total Nilai</th>
                                    <th width="180">Waktu Pelaksanaan</th>
                                    <th width="100">Status</th>
                                    <th width="150" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ujian as $item)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>
                                            <strong class="text-purple">{{ $item->judul_ujian }}</strong>
                                            @if($item->deskripsi)
                                                <br><small class="text-muted">{{ Str::limit($item->deskripsi, 50) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $item->kelas->nama_kelas }}</span>
                                        </td>
                                        <td>{{ $item->mata_pelajaran }}</td>
                                        <td class="text-center">
                                            @if($item->berkas_soal)
                                                <a href="{{ route('guru.penilaian.uas.download.soal', $item->id) }}" 
                                                   class="btn btn-sm btn-outline-purple" title="Download Soal">
                                                    <i class="fas fa-download me-1"></i>PDF
                                                </a>
                                            @else
                                                <span class="badge bg-warning">Tidak ada</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-primary">{{ $item->total_nilai }}</span>
                                        </td>
                                        <td>
                                            <small>
                                                <div><strong>Mulai:</strong> {{ $item->waktu_mulai->format('d M Y H:i') }}</div>
                                                <div><strong>Selesai:</strong> {{ $item->waktu_selesai->format('d M Y H:i') }}</div>
                                            </small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-{{ $item->status == 'published' ? 'success' : ($item->status == 'completed' ? 'primary' : 'warning') }}">
                                                {{ ucfirst($item->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('guru.penilaian.uas.show', $item->id) }}" 
                                                   class="btn btn-info" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('guru.penilaian.uas.edit', $item->id) }}" 
                                                   class="btn btn-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if($item->status == 'draft')
                                                    <form action="{{ route('guru.penilaian.uas.publish', $item->id) }}" 
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success" 
                                                                title="Publish UAS">
                                                            <i class="fas fa-paper-plane"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                <form action="{{ route('guru.penilaian.uas.destroy', $item->id) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" 
                                                            onclick="return confirm('Apakah Anda yakin ingin menghapus UAS ini?')"
                                                            title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="fas fa-file-contract fa-4x mb-3"></i>
                                                <br>
                                                <h5>Belum ada Ujian Akhir Semester</h5>
                                                <p class="mb-3">Mulai buat UAS pertama Anda</p>
                                                <a href="{{ route('guru.penilaian.uas.create') }}" class="btn btn-purple">
                                                    <i class="fas fa-plus me-1"></i>Buat UAS Pertama
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Info Box -->
                    @if($ujian->count() > 0)
                    <div class="alert alert-purple mt-3">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Informasi:</strong> Ujian Akhir Semester merupakan evaluasi akhir dengan bobot nilai tertinggi. Pastikan soal mencakup seluruh materi semester.
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
    .bg-purple {
        background: linear-gradient(45deg, #6f42c1, #8e44ad) !important;
    }
    
    .btn-purple {
        background-color: #6f42c1;
        border-color: #6f42c1;
        color: white;
    }
    
    .btn-purple:hover {
        background-color: #5a2d9c;
        border-color: #5a2d9c;
        color: white;
    }
    
    .btn-outline-purple {
        color: #6f42c1;
        border-color: #6f42c1;
    }
    
    .btn-outline-purple:hover {
        background-color: #6f42c1;
        color: white;
    }
    
    .border-left-purple {
        border-left: 0.25rem solid #6f42c1 !important;
    }
    
    .text-purple {
        color: #6f42c1 !important;
    }
    
    .alert-purple {
        background-color: #f0e6ff;
        border-color: #6f42c1;
        color: #6f42c1;
    }
    
    .card {
        border: none;
        border-radius: 0.5rem;
    }
    
    .table th {
        border-top: none;
        font-weight: 600;
        background-color: #f8f9fa;
    }
    
    .badge {
        font-size: 0.75em;
        padding: 0.4em 0.6em;
    }
    
    .btn-group-sm > .btn {
        padding: 0.25rem 0.5rem;
    }
    
    .table tbody tr:hover {
        background-color: #f8f9fa;
    }
</style>
@endpush

@push('scripts')
<script>
    // Auto submit form when filter changes
    document.getElementById('kelas_id').addEventListener('change', function() {
        this.form.submit();
    });
    
    document.getElementById('status').addEventListener('change', function() {
        this.form.submit();
    });
</script>
@endpush