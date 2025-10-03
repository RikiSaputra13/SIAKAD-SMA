@extends('siswa.layouts.app')

@section('title', 'Dashboard Saya')
@section('header', 'Dashboard Saya')

@section('content')
    <h1 class="mb-4">Selamat Datang, {{ Auth::user()->name }}</h1>

    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>0</h3>
                    <p>Absensi</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-check"></i>
                </div>
                <a href="{{ route('siswa.absensi.index') }}" class="small-box-footer">
                    Lihat & Lakukan Absensi <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>0</h3>
                    <p>Jadwal Pelajaran</p>
                </div>
                <div class="icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <a href="{{ route('siswa.jadwal.index') }}" class="small-box-footer">
                    Lihat Jadwal <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>0</h3>
                    <p>Pembayaran</p>
                </div>
                <div class="icon">
                    <i class="fas fa-credit-card"></i>
                </div>
                <a href="{{ route('siswa.pembayaran.index') }}" class="small-box-footer">
                    Lihat Pembayaran <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>
@endsection