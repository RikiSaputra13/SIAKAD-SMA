@extends('siswa.layouts.app')

@section('title', 'Daftar Ujian Harian')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h4 class="mb-0">Daftar Ujian</h4>
        </div>
        <div class="col-md-6">
            <!-- Filter Tipe Ujian -->
            <form method="GET" action="{{ route('siswa.ujian-harian.index') }}" class="d-flex justify-content-end">
                <div class="input-group" style="max-width: 300px;">
                    <label class="input-group-text" for="tipe_ujian_filter">
                        <i class="fas fa-filter me-1"></i> Filter
                    </label>
                    <select class="form-select" id="tipe_ujian_filter" name="tipe_ujian_id" onchange="this.form.submit()">
                        <option value="">Semua Tipe Ujian</option>
                        @foreach($tipeUjianOptions as $id => $nama)
                            <option value="{{ $id }}" {{ $tipeUjianId == $id ? 'selected' : '' }}>
                                {{ strtoupper($nama) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Info Filter Aktif -->
    @if($tipeUjianId)
        <div class="alert alert-info d-flex align-items-center">
            <i class="fas fa-info-circle me-2"></i>
            <span>
                Menampilkan ujian dengan tipe: 
                <strong>{{ $tipeUjianOptions[$tipeUjianId] ?? 'Tidak Diketahui' }}</strong>
                <a href="{{ route('siswa.ujian-harian.index') }}" class="btn btn-sm btn-outline-info ms-2">
                    Tampilkan Semua
                </a>
            </span>
        </div>
    @endif

    <!-- Statistik Ujian -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Total Ujian</h6>
                            <h4>{{ $ujian->count() }}</h4>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clipboard-list fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Sudah Dikerjakan</h6>
                            <h4>{{ $ujian->where('jawaban', '!=', [])->count() }}</h4>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Belum Dikerjakan</h6>
                            <h4>{{ $ujian->where('jawaban', '==', [])->count() }}</h4>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Tipe Ujian</h6>
                            <h4>{{ $tipeUjianId ? 1 : $tipeUjianOptions->count() }}</h4>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-filter fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Ujian -->
    <div class="row">
        @forelse($ujian as $item)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-header bg-transparent border-bottom-0 pt-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <h6 class="card-title mb-0 fw-bold text-truncate" title="{{ $item->judul_ujian }}">
                                {{ $item->judul_ujian }}
                            </h6>
                            <span class="badge {{ $item->tipeUjian->kode == 'uh' ? 'bg-primary' : ($item->tipeUjian->kode == 'pts' ? 'bg-warning' : 'bg-danger') }} ms-2">
                                {{ strtoupper($item->tipeUjian->kode) }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body pt-2">
                        <div class="mb-2">
                            <small class="text-muted">
                                <i class="fas fa-book me-1"></i>
                                {{ $item->mata_pelajaran }}
                            </small>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>
                                {{ $item->waktu_mulai->format('d M Y') }}
                            </small>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>
                                {{ $item->waktu_mulai->format('H:i') }} - {{ $item->waktu_selesai->format('H:i') }}
                            </small>
                        </div>

                        <div class="mb-3">
                            @if($item->jawaban->isNotEmpty())
                                <span class="badge bg-success rounded-pill">
                                    <i class="fas fa-check me-1"></i>Sudah Dikerjakan
                                </span>
                            @elseif(now()->lt($item->waktu_mulai))
                                <span class="badge bg-warning rounded-pill">
                                    <i class="fas fa-clock me-1"></i>Belum Dimulai
                                </span>
                            @elseif(now()->gt($item->waktu_selesai))
                                <span class="badge bg-danger rounded-pill">
                                    <i class="fas fa-times me-1"></i>Waktu Habis
                                </span>
                            @else
                                <span class="badge bg-info rounded-pill">
                                    <i class="fas fa-pencil-alt me-1"></i>Belum Dikerjakan
                                </span>
                            @endif
                        </div>

                        <div class="d-grid gap-2">
                            <a href="{{ route('siswa.ujian-harian.show', $item->id) }}" 
                               class="btn {{ $item->jawaban->isNotEmpty() ? 'btn-outline-success' : 'btn-primary' }} btn-sm">
                                <i class="fas {{ $item->jawaban->isNotEmpty() ? 'fa-eye' : 'fa-pencil-alt' }} me-1"></i>
                                {{ $item->jawaban->isNotEmpty() ? 'Lihat Hasil' : 'Kerjakan Ujian' }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Tidak ada ujian yang tersedia</h5>
                        <p class="text-muted">
                            @if($tipeUjianId)
                                Tidak ditemukan ujian dengan tipe yang dipilih.
                                <a href="{{ route('siswa.ujian-harian.index') }}" class="text-primary">Tampilkan semua ujian</a>
                            @else
                                Saat ini tidak ada ujian yang tersedia untuk kelas Anda.
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</div>

<style>
.card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}
.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
}
.badge {
    font-size: 0.7rem;
}
</style>
@endsection