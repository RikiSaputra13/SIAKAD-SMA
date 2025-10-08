@extends('layouts.app')

@section('title', 'Ujian Harian')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-file-pdf me-2"></i>Ujian Harian
                    </h5>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
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
                            <form method="GET" action="{{ route('guru.penilaian.uh.index') }}" class="row g-3">
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
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="fas fa-search me-1"></i>Filter
                                    </button>
                                    <a href="{{ route('guru.penilaian.uh.index') }}" class="btn btn-secondary">
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
                                                Total Ujian</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistik['total'] }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
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
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
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
                        <h5 class="mb-0">Daftar Ujian Harian</h5>
                        <a href="{{ route('guru.penilaian.uh.create') }}" class="btn btn-success">
                            <i class="fas fa-plus me-1"></i>Buat Ujian Harian
                        </a>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped">
                            <thead class="bg-light">
                                <tr>
                                    <th>No</th>
                                    <th>Judul Ujian</th>
                                    <th>Kelas</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Berkas Soal</th>
                                    <th>Total Nilai</th>
                                    <th>Waktu</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ujian as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <strong>{{ $item->judul_ujian }}</strong>
                                            @if($item->deskripsi)
                                                <br><small class="text-muted">{{ Str::limit($item->deskripsi, 50) }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $item->kelas->nama_kelas }}</td>
                                        <td>{{ $item->mata_pelajaran }}</td>
                                        <td class="text-center">
                                            @if($item->berkas_soal)
                                                <a href="{{ route('guru.penilaian.uh.download.soal', $item->id) }}" 
                                                   class="btn btn-sm btn-outline-primary" title="Download Soal">
                                                    <i class="fas fa-download me-1"></i>PDF
                                                </a>
                                            @else
                                                <span class="badge bg-warning">Tidak ada</span>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $item->total_nilai }}</td>
                                        <td>
                                            <small>
                                                <strong>Mulai:</strong> {{ $item->waktu_mulai->format('d M Y H:i') }}<br>
                                                <strong>Selesai:</strong> {{ $item->waktu_selesai->format('d M Y H:i') }}
                                            </small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-{{ $item->status == 'published' ? 'success' : ($item->status == 'completed' ? 'info' : 'warning') }}">
                                                {{ ucfirst($item->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('guru.penilaian.uh.show', $item->id) }}" 
                                                   class="btn btn-info" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('guru.penilaian.uh.edit', $item->id) }}" 
                                                   class="btn btn-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if($item->status == 'draft')
                                                    <form action="{{ route('guru.penilaian.uh.publish', $item->id) }}" 
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success" 
                                                                title="Publish">
                                                            <i class="fas fa-paper-plane"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                <form action="{{ route('guru.penilaian.uh.destroy', $item->id) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" 
                                                            onclick="return confirm('Apakah Anda yakin ingin menghapus ujian harian ini?')"
                                                            title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-file-pdf fa-3x mb-3"></i>
                                                <br>
                                                Belum ada ujian harian.
                                                <br>
                                                <a href="{{ route('guru.penilaian.uh.create') }}" class="btn btn-primary mt-2">
                                                    Buat Ujian Harian Pertama
                                                </a>
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
    
    .btn-group-sm > .btn {
        padding: 0.25rem 0.5rem;
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

    // Confirm actions
    function confirmAction(message) {
        return confirm(message || 'Apakah Anda yakin?');
    }
</script>
@endpush