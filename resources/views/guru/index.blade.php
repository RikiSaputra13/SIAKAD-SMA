@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4 text-dark">Dashboard Admin</h1>

    <div class="row g-3 mb-4">
        {{-- Card Total Siswa --}}
        <div class="col-lg-2 col-6">
            <div class="card shadow-sm border-0 bg-gradient-info text-white p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1">{{ $totalSiswa }}</h4>
                        <small>Siswa</small>
                    </div>
                    <i class="fas fa-user-graduate fa-2x opacity-75"></i>
                </div>
            </div>
        </div>

        {{-- Card Total Guru --}}
        <div class="col-lg-2 col-6">
            <div class="card shadow-sm border-0 bg-gradient-success text-white p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1">{{ $totalGuru }}</h4>
                        <small>Guru</small>
                    </div>
                    <i class="fas fa-chalkboard-teacher fa-2x opacity-75"></i>
                </div>
            </div>
        </div>

        {{-- Card Total Kelas --}}
        <div class="col-lg-2 col-6">
            <div class="card shadow-sm border-0 bg-gradient-warning text-white p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1">{{ $totalKelas }}</h4>
                        <small>Kelas</small>
                    </div>
                    <i class="fas fa-school fa-2x opacity-75"></i>
                </div>
            </div>
        </div>

        {{-- Card Total Jadwal --}}
        <div class="col-lg-2 col-6">
            <div class="card shadow-sm border-0 bg-gradient-primary text-white p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1">{{ $totalJadwal }}</h4>
                        <small>Jadwal</small>
                    </div>
                    <i class="fas fa-calendar-alt fa-2x opacity-75"></i>
                </div>
            </div>
        </div>

        {{-- Card Total Absensi --}}
        <div class="col-lg-2 col-6">
            <div class="card shadow-sm border-0 bg-gradient-info text-white p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1">{{ $totalAbsensi }}</h4>
                        <small>Absensi</small>
                    </div>
                    <i class="fas fa-clipboard-list fa-2x opacity-75"></i>
                </div>
            </div>
        </div>

        {{-- Card Total Pembayaran --}}
        <div class="col-lg-2 col-6">
            <div class="card shadow-sm border-0 bg-gradient-secondary text-white p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1">{{ $totalPembayaran }}</h4>
                        <small>Pembayaran</small>
                    </div>
                    <i class="fas fa-money-bill-wave fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Manajemen Token Absensi --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow border-0 rounded-3">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-key me-2"></i>Manajemen Token Absensi</h5>
                </div>
                <div class="card-body">
                    {{-- Notifikasi sukses --}}
                    @if(session('success_token'))
                        <div class="alert alert-success alert-dismissible fade show rounded-3 d-flex align-items-center justify-content-between" role="alert">
                            <div>
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('success_token') }}
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                <p class="mb-3">Klik tombol di bawah untuk membuat <strong>token absensi baru</strong>

                <form action="{{ route('admin.generate-token') }}" method="POST" class="d-flex flex-column flex-sm-row align-items-start gap-3">
                    @csrf
                    <button type="submit" class="btn btn-success btn-lg shadow-sm">
                        <i class="fas fa-key me-2"></i>Buat Token Baru
                    </button>

                    @if(session('current_token'))
                        <div class="alert alert-info mb-0 py-2 px-3 rounded-3 fw-bold d-flex align-items-center gap-2">
                            <i class="fas fa-info-circle"></i>
                            Token Saat Ini: <span class="text-dark">{{ session('current_token') }}</span>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>

</div>
@endsection
