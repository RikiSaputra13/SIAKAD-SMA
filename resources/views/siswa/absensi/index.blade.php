@extends('siswa.layouts.app')

@section('title', 'Absensi Saya')
@section('header', 'Absensi Saya')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Absensi Saya</h5>
        </div>
        <div class="card-body">
            <p>Lihat & lakukan absensi</p>

            {{-- Tombol untuk melihat riwayat --}}
            <a href="{{ route('siswa.absensi.history') }}" class="btn btn-info mb-4">
                <i class="fas fa-history"></i>Riwayat
            </a>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            {{-- Form Absensi --}}
            <div class="card shadow-sm mt-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Form Absensi</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('siswa.absensi.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="token" class="form-label">Masukkan Token Absensi</label>
                            <input type="text" class="form-control @error('token') is-invalid @enderror" id="token" name="token" required>
                            @error('token')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tanggal" class="form-label">Tanggal</label>
                            <input type="text" class="form-control" id="tanggal" value="{{ now()->format('d/m/Y') }}" disabled>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status Kehadiran</label>
                            <input type="text" class="form-control" id="status" value="Hadir" disabled>
                        </div>

                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan (Opsional)</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Absen</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
