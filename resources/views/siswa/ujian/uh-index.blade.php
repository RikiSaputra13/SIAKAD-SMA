@extends('siswa.layouts.app')

@section('title', 'Daftar Ujian Harian')

@section('content')
<div class="container-fluid">
    <h4 class="mb-4">Daftar Ujian</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @forelse($ujian as $item)
        <div class="card mb-3 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">{{ $item->judul_ujian }} <span class="badge bg-secondary">{{ strtoupper($item->tipeUjian->nama) }}</span></h5>
                <p class="card-text mb-1"><strong>Mapel:</strong> {{ $item->mata_pelajaran }}</p>
                <p class="card-text mb-1"><strong>Waktu:</strong> 
                    {{ $item->waktu_mulai->format('d M Y H:i') }} - 
                    {{ $item->waktu_selesai->format('d M Y H:i') }}
                </p>

                <p class="card-text">
                    <strong>Status:</strong>
                    @if($item->jawaban->isNotEmpty())
                        <span class="badge bg-success">Sudah Dikerjakan</span>
                    @elseif(now()->lt($item->waktu_mulai))
                        <span class="badge bg-warning">Belum Dimulai</span>
                    @elseif(now()->gt($item->waktu_selesai))
                        <span class="badge bg-danger">Waktu Habis</span>
                    @else
                        <span class="badge bg-info">Belum Dikerjakan</span>
                    @endif
                </p>

                <div class="mt-3">
                    <a href="{{ route('siswa.ujian-harian.show', $item->id) }}" class="btn btn-primary">
                        <i class="fas fa-eye me-1"></i>Lihat Ujian
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-info">Tidak ada ujian yang tersedia saat ini.</div>
    @endforelse
</div>
@endsection
