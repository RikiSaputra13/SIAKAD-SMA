@extends('siswa.layouts.app')

@section('title', 'Riwayat Absensi')
@section('header', 'Riwayat Absensi')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Riwayat Absensi</h5>
        </div>
        <div class="card-body">
            {{-- Tabel Riwayat Absensi --}}
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center">
                    <thead class="bg-light">
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($absensis as $index => $absensi)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($absensi->tanggal)->format('d-m-Y') }}</td>
                                <td>
                                    @php
                                        $badge = match($absensi->status) {
                                            'Hadir' => 'success',
                                            'Sakit' => 'warning',
                                            'Izin' => 'info',
                                            'Alpha' => 'danger',
                                            default => 'secondary'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $badge }}">{{ $absensi->status }}</span>
                                </td>
                                <td>{{ $absensi->keterangan ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Belum ada data absensi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
