@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('header', 'Dashboard Admin')

@section('content')
<div class="row">
    <!-- Guru -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ \App\Models\Guru::count() }}</h3>
                <p>Guru</p>
            </div>
            <div class="icon">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
            <a href="{{ route('admin.guru.index') }}" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- Siswa -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ \App\Models\Siswa::count() }}</h3>
                <p>Siswa</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-graduate"></i>
            </div>
            <a href="{{ route('admin.siswa.index') }}" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- Kelas -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ \App\Models\Kelas::count() }}</h3>
                <p>Kelas</p>
            </div>
            <div class="icon">
                <i class="fas fa-school"></i>
            </div>
            <a href="{{ route('admin.kelas.index') }}" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- Jadwal -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>{{ \App\Models\Jadwal::count() }}</h3>
                <p>Jadwal</p>
            </div>
            <div class="icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <a href="{{ route('admin.jadwal.index') }}" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- Absensi -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ \App\Models\Absensi::count() }}</h3>
                <p>Absensi</p>
            </div>
            <div class="icon">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <a href="{{ route('admin.absensi.index') }}" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- Pembayaran -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-secondary">
            <div class="inner">
                <h3>{{ \App\Models\Pembayaran::count() }}</h3>
                <p>Pembayaran</p>
            </div>
            <div class="icon">
                <i class="fas fa-credit-card"></i>
            </div>
            <a href="{{ route('admin.pembayaran.index') }}" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>
@endsection
