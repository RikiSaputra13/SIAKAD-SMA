@extends('layouts.app')

@section('title', 'Dashboard Guru')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4 text-dark">Dashboard Guru</h1>

    <div class="row g-3 mb-4">
        {{-- Card Total Kelas yang Diampu --}}
        <div class="col-lg-3 col-6">
            <div class="card shadow-sm border-0 bg-gradient-primary text-white p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1">{{ $totalKelasDiampu }}</h4>
                        <small>Kelas Diampu</small>
                    </div>
                    <i class="fas fa-chalkboard fa-2x opacity-75"></i>
                </div>
            </div>
        </div>

        {{-- Card Total Jadwal Mengajar --}}
        <div class="col-lg-3 col-6">
            <div class="card shadow-sm border-0 bg-gradient-success text-white p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1">{{ $totalJadwalMengajar }}</h4>
                        <small>Jadwal Mengajar</small>
                    </div>
                    <i class="fas fa-calendar-alt fa-2x opacity-75"></i>
                </div>
            </div>
        </div>

        {{-- Card Total Siswa --}}
        <div class="col-lg-3 col-6">
            <div class="card shadow-sm border-0 bg-gradient-info text-white p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1">{{ $totalSiswa }}</h4>
                        <small>Total Siswa</small>
                    </div>
                    <i class="fas fa-user-graduate fa-2x opacity-75"></i>
                </div>
            </div>
        </div>

        {{-- Card Absensi Hari Ini --}}
        <div class="col-lg-3 col-6">
            <div class="card shadow-sm border-0 bg-gradient-warning text-white p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1">{{ $totalAbsensiHariIni }}</h4>
                        <small>Absensi Hari Ini</small>
                    </div>
                    <i class="fas fa-clipboard-list fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Jadwal Mengajar Hari Ini --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow border-0 rounded-3">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-calendar-day me-2"></i>Jadwal Mengajar Hari Ini</h5>
                </div>
                <div class="card-body">
                    @if(count($jadwalHariIni) > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Mata Pelajaran</th>
                                        <th>Kelas</th>
                                        <th>Jam</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($jadwalHariIni as $i => $jadwal)
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td>{{ $jadwal->mapel }}</td>
                                            <td>{{ $jadwal->kelas }}</td>
                                            <td>{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info mb-0">
                            Tidak ada jadwal mengajar hari ini.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection