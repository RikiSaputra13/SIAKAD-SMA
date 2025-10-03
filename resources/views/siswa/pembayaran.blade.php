@extends('siswa.layouts.app')

@section('title', 'Pembayaran Saya')
@section('header', 'Pembayaran Saya')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Riwayat Pembayaran</h5>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center">
                <thead class="bg-light">
                    <tr>
                        <th>No</th>
                        <th>Jenis Pembayaran</th>
                        <th>Total Tagihan</th>
                        <th>Jumlah Bayar</th>
                        <th>Tanggal Bayar</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pembayarans as $index => $pembayaran)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $pembayaran->jenis_pembayaran ?? '-' }}</td>
                            <td>Rp {{ number_format($pembayaran->total_tagihan, 2, ',', '.') }}</td>
                            <td>Rp {{ number_format($pembayaran->jumlah_bayar, 2, ',', '.') }}</td>
                            <td>
                                {{ $pembayaran->tanggal_bayar ? \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->format('d-m-Y') : '-' }}
                            </td>
                            <td>
                                <span class="badge bg-{{ $pembayaran->status === 'Lunas' ? 'success' : 'warning' }}">
                                    {{ $pembayaran->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada data pembayaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

