@extends('layouts.app')

@section('title', 'Riwayat Absensi')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i>Riwayat Absensi</h5>
                    <a href="{{ route('siswa.dashboard') }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <!-- Info Siswa -->
                    @if($siswa)
                        <div class="alert alert-info mb-4">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-user-circle fa-lg me-3"></i>
                                <div>
                                    <strong class="d-block">{{ $siswa->nama }}</strong>
                                    <small class="text-muted">Kelas: {{ $siswa->kelas->nama ?? 'Tidak ada data kelas' }}</small>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Error Message -->
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Data Absensi -->
                    @if($absensis->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="bg-light">
                                    <tr>
                                        <th width="50">No</th>
                                        <th>Tanggal</th>
                                        <th>Waktu</th>
                                        <th>Guru</th>
                                        <th>Status</th>
                                        <th>Sesi</th>
                                        <th>Token</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($absensis as $index => $absensi)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <strong>{{ $absensi->tanggal->translatedFormat('d F Y') }}</strong>
                                            </td>
                                            <td>{{ $absensi->waktu }}</td>
                                            <td>
                                                <div>
                                                    <strong>{{ $absensi->guru->nama ?? 'Tidak ada data' }}</strong>
                                                </div>
                                                <small class="text-muted">
                                                    {{ $absensi->mapel->nama ?? 'Tidak ada mapel' }}
                                                </small>
                                            </td>
                                            <td>
                                                <span class="badge 
                                                    @if($absensi->status == 'hadir') bg-success
                                                    @elseif($absensi->status == 'terlambat') bg-warning
                                                    @elseif($absensi->status == 'sakit') bg-info
                                                    @elseif($absensi->status == 'izin') bg-primary
                                                    @elseif($absensi->status == 'alfa') bg-danger
                                                    @else bg-secondary
                                                    @endif">
                                                    <i class="fas 
                                                        @if($absensi->status == 'hadir') fa-check-circle
                                                        @elseif($absensi->status == 'terlambat') fa-clock
                                                        @elseif($absensi->status == 'sakit') fa-thermometer
                                                        @elseif($absensi->status == 'izin') fa-envelope
                                                        @elseif($absensi->status == 'alfa') fa-times-circle
                                                        @else fa-question-circle
                                                        @endif me-1">
                                                    </i>
                                                    {{ ucfirst($absensi->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($absensi->sesi)
                                                    <span class="badge bg-dark">{{ $absensi->sesi }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($absensi->token_used)
                                                    <code class="bg-light px-2 py-1 rounded">{{ $absensi->token_used }}</code>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small>{{ $absensi->keterangan ?? 'Tidak ada keterangan' }}</small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Summary -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="card bg-light">
                                    <div class="card-body py-2">
                                        <div class="row text-center">
                                            <div class="col">
                                                <small class="text-muted">Total Absensi: <strong>{{ $absensis->count() }}</strong></small>
                                            </div>
                                            <div class="col">
                                                <small class="text-success">Hadir: <strong>{{ $absensis->where('status', 'hadir')->count() }}</strong></small>
                                            </div>
                                            <div class="col">
                                                <small class="text-warning">Terlambat: <strong>{{ $absensis->where('status', 'terlambat')->count() }}</strong></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-5">
                            <i class="fas fa-clipboard-list fa-4x text-muted mb-4"></i>
                            <h5 class="text-muted">Belum ada riwayat absensi</h5>
                            <p class="text-muted mb-4">Absensi Anda akan muncul di sini setelah melakukan absensi.</p>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('siswa.absensi.index') }}" class="btn btn-primary">
                                    <i class="fas fa-clipboard-check me-2"></i>Absensi Sekarang
                                </a>
                                <a href="{{ route('siswa.dashboard') }}" class="btn btn-warning">
                                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
                
                <!-- Footer dengan tombol kembali -->
                @if($absensis->count() > 0)
                <div class="card-footer bg-transparent">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            Menampilkan {{ $absensis->count() }} riwayat absensi
                        </small>
                        <a href="{{ route('siswa.dashboard') }}" class="btn btn-warning">
                            <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .table th {
        border-top: none;
        font-weight: 600;
        color: #495057;
    }
    .badge {
        font-size: 0.75em;
    }
    .card {
        border-radius: 10px;
    }
    .table-responsive {
        border-radius: 8px;
    }
</style>
@endpush