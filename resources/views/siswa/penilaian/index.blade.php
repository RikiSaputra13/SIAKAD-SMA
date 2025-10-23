@extends('siswa.layouts.app')

@section('title', 'Hasil Nilai Saya')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-line me-2"></i>Hasil Nilai Saya
                    </h5>
                </div>
                <div class="card-body p-4">
                    <!-- Student Info -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary rounded-circle p-3 me-3">
                                    <i class="fas fa-user-graduate text-white fa-2x"></i>
                                </div>
                                <div>
                                    <h4 class="mb-1 text-dark">{{ auth()->user()->siswa->nama }}</h4>
                                    <p class="text-muted mb-0">
                                        <i class="fas fa-id-card me-1"></i>NIS: {{ auth()->user()->siswa->nis }} 
                                        | 
                                        <i class="fas fa-users me-1 ms-2"></i>Kelas: {{ auth()->user()->siswa->kelas->nama_kelas }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <div class="bg-light rounded p-3">
                                <div class="text-muted small">Semester Aktif</div>
                                <div class="h5 mb-0 fw-bold text-primary">{{ $semesterAktif }}/{{ $tahunAjaranAktif }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="row mb-4">
                        <div class="col-xl-2 col-md-4 mb-3">
                            <div class="card border-0 bg-success text-white h-100">
                                <div class="card-body p-3 text-center">
                                    <i class="fas fa-trophy fa-2x mb-2 opacity-75"></i>
                                    <div class="h4 mb-0 fw-bold">{{ $statistik['nilai_tertinggi'] }}</div>
                                    <small>Nilai Tertinggi</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-md-4 mb-3">
                            <div class="card border-0 bg-info text-white h-100">
                                <div class="card-body p-3 text-center">
                                    <i class="fas fa-chart-bar fa-2x mb-2 opacity-75"></i>
                                    <div class="h4 mb-0 fw-bold">{{ $statistik['rata_rata'] }}</div>
                                    <small>Rata-rata</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-md-4 mb-3">
                            <div class="card border-0 bg-warning text-white h-100">
                                <div class="card-body p-3 text-center">
                                    <i class="fas fa-book fa-2x mb-2 opacity-75"></i>
                                    <div class="h4 mb-0 fw-bold">{{ $statistik['total_mapel'] }}</div>
                                    <small>Total Mapel</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-md-4 mb-3">
                            <div class="card border-0 bg-danger text-white h-100">
                                <div class="card-body p-3 text-center">
                                    <i class="fas fa-exclamation-triangle fa-2x mb-2 opacity-75"></i>
                                    <div class="h4 mb-0 fw-bold">{{ $statistik['nilai_terendah'] }}</div>
                                    <small>Nilai Terendah</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-md-4 mb-3">
                            <div class="card border-0 bg-secondary text-white h-100">
                                <div class="card-body p-3 text-center">
                                    <i class="fas fa-check-circle fa-2x mb-2 opacity-75"></i>
                                    <div class="h4 mb-0 fw-bold">{{ $statistik['mapel_tuntas'] }}</div>
                                    <small>Mapel Tuntas</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-md-4 mb-3">
                            <div class="card border-0 bg-dark text-white h-100">
                                <div class="card-body p-3 text-center">
                                    <i class="fas fa-clock fa-2x mb-2 opacity-75"></i>
                                    <div class="h4 mb-0 fw-bold">{{ $statistik['mapel_belum_tuntas'] }}</div>
                                    <small>Perlu Perbaikan</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Simple Filter -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-3">
                            <form method="GET" action="{{ route('siswa.nilai.index') }}" class="row g-2">
                                <div class="col-md-3">
                                    <select name="semester" class="form-select border-secondary-subtle">
                                        <option value="">Semua Semester</option>
                                        <option value="1" {{ request('semester') == '1' ? 'selected' : '' }}>Semester 1</option>
                                        <option value="2" {{ request('semester') == '2' ? 'selected' : '' }}>Semester 2</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select name="tahun_ajaran" class="form-select border-secondary-subtle">
                                        <option value="">Semua Tahun</option>
                                        @foreach($tahunAjaranOptions as $tahun)
                                            <option value="{{ $tahun }}" {{ request('tahun_ajaran') == $tahun ? 'selected' : '' }}>
                                                {{ $tahun }}/{{ $tahun + 1 }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select name="status" class="form-select border-secondary-subtle">
                                        <option value="">Semua Status</option>
                                        <option value="tuntas" {{ request('status') == 'tuntas' ? 'selected' : '' }}>Sudah Tuntas</option>
                                        <option value="belum_tuntas" {{ request('status') == 'belum_tuntas' ? 'selected' : '' }}>Perlu Perbaikan</option>
                                    </select>
                                </div>
                                <div class="col-md-3 d-flex gap-2">
                                    <button type="submit" class="btn btn-primary flex-fill">
                                        <i class="fas fa-filter me-1"></i>Filter
                                    </button>
                                    <a href="{{ route('siswa.nilai.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-refresh"></i>
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Grades Table -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-light border-0 py-3">
                            <h6 class="mb-0 text-dark">
                                <i class="fas fa-list me-2"></i>Daftar Nilai Mata Pelajaran
                            </h6>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0 ps-4">Mata Pelajaran</th>
                                        <th class="border-0 text-center">UH</th>
                                        <th class="border-0 text-center">UTS</th>
                                        <th class="border-0 text-center">UAS</th>
                                        <th class="border-0 text-center">Tugas</th>
                                        <th class="border-0 text-center">Nilai Akhir</th>
                                        <th class="border-0 text-center">Predikat</th>
                                        <th class="border-0 text-center">Status</th>
                                        <th class="border-0 text-center pe-4">Semester</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($nilai as $item)
                                        <tr class="border-bottom">
                                            <td class="ps-4">
                                                <div class="fw-semibold text-dark">{{ $item->mata_pelajaran }}</div>
                                                <small class="text-muted">Guru: {{ $item->guru->nama ?? '-' }}</small>
                                            </td>
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
                                                    $status = $item->nilai_akhir >= 75 ? 'tuntas' : 'belum_tuntas';
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
                                            <td class="text-center">
                                                @if($item->nilai_akhir >= 75)
                                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">
                                                        <i class="fas fa-check-circle me-1"></i>Tuntas
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25">
                                                        <i class="fas fa-exclamation-triangle me-1"></i>Perlu Perbaikan
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-center text-muted pe-4">
                                                {{ $item->semester }}/{{ $item->tahun_ajaran }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center py-5">
                                                <div class="text-muted">
                                                    <i class="fas fa-inbox fa-3x mb-3 opacity-50"></i>
                                                    <br>
                                                    <h5 class="fw-semibold">Belum ada data nilai</h5>
                                                    <p class="mb-0">Data nilai akan ditampilkan setelah guru menginput nilai</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Performance Summary -->
                    @if($nilai->count() > 0)
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header bg-light border-0 py-3">
                                    <h6 class="mb-0 text-dark">
                                        <i class="fas fa-chart-pie me-2"></i>Ringkasan Performa
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-4">
                                            <div class="h3 fw-bold text-success">{{ $statistik['persentase_tuntas'] }}%</div>
                                            <small class="text-muted">Tuntas</small>
                                        </div>
                                        <div class="col-4">
                                            <div class="h3 fw-bold text-warning">{{ $statistik['mapel_sedang'] }}</div>
                                            <small class="text-muted">Cukup</small>
                                        </div>
                                        <div class="col-4">
                                            <div class="h3 fw-bold text-danger">{{ $statistik['mapel_belum_tuntas'] }}</div>
                                            <small class="text-muted">Perlu Perbaikan</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header bg-light border-0 py-3">
                                    <h6 class="mb-0 text-dark">
                                        <i class="fas fa-star me-2"></i>Pencapaian
                                    </h6>
                                </div>
                                <div class="card-body">
                                    @if($statistik['nilai_tertinggi'] >= 90)
                                        <div class="d-flex align-items-center text-success mb-2">
                                            <i class="fas fa-trophy me-2"></i>
                                            <span>Excellent! Nilai tertinggi Anda sangat baik</span>
                                        </div>
                                    @endif
                                    
                                    @if($statistik['persentase_tuntas'] >= 80)
                                        <div class="d-flex align-items-center text-info mb-2">
                                            <i class="fas fa-check-circle me-2"></i>
                                            <span>Great! Mayoritas mapel sudah tuntas</span>
                                        </div>
                                    @endif

                                    @if($statistik['mapel_belum_tuntas'] > 0)
                                        <div class="d-flex align-items-center text-warning">
                                            <i class="fas fa-lightbulb me-2"></i>
                                            <span>Fokus perbaikan pada {{ $statistik['mapel_belum_tuntas'] }} mapel</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
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
    }
    
    .table td {
        vertical-align: middle;
        padding: 1rem 0.75rem;
    }
    
    .bg-opacity-10 {
        background-color: rgba(var(--bs-success-rgb), 0.1) !important;
    }
    
    .border-opacity-25 {
        border-color: rgba(var(--bs-success-rgb), 0.25) !important;
    }
    
    .badge {
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto submit form when filter changes
        const filterSelects = document.querySelectorAll('select[name="semester"], select[name="tahun_ajaran"], select[name="status"]');
        
        filterSelects.forEach(select => {
            select.addEventListener('change', function() {
                this.form.submit();
            });
        });

        // Add simple animation to stats cards
        const statCards = document.querySelectorAll('.card.border-0.bg-success, .card.border-0.bg-info, .card.border-0.bg-warning, .card.border-0.bg-danger, .card.border-0.bg-secondary, .card.border-0.bg-dark');
        
        statCards.forEach(card => {
            card.style.transition = 'transform 0.2s ease-in-out';
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