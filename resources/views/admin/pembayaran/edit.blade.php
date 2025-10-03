@extends('layouts.app')

@section('title', 'Edit Pembayaran')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Edit Pembayaran</h5>
        </div>
        <div class="card-body">

            {{-- Tampilkan error validasi --}}
            @if ($errors->any())
                <div class="alert alert-danger rounded-3">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>- {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.pembayaran.update', $pembayaran->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    {{-- Nama Siswa --}}
                    <div class="col-md-6">
                        <label for="siswa_id" class="form-label">Nama Siswa</label>
                        <select name="siswa_id" id="siswa_id" class="form-control" required>
                            <option value="">-- Pilih Siswa --</option>
                            @foreach($siswas as $siswa)
                                <option value="{{ $siswa->id }}" 
                                    {{ old('siswa_id', $pembayaran->siswa_id) == $siswa->id ? 'selected' : '' }}>
                                    {{ $siswa->nama }} (NIS: {{ $siswa->nis }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Jenis Pembayaran --}}
                    <div class="col-md-6">
                        <label for="jenis_pembayaran" class="form-label">Jenis Pembayaran</label>
                        <input type="text" name="jenis_pembayaran" id="jenis_pembayaran" 
                               class="form-control" 
                               value="{{ old('jenis_pembayaran', $pembayaran->jenis_pembayaran) }}" required>
                    </div>

                    {{-- Total Tagihan --}}
                    <div class="col-md-6">
                        <label for="total_tagihan" class="form-label">Total Tagihan</label>
                        <input type="number" name="total_tagihan" id="total_tagihan" 
                               class="form-control" 
                               value="{{ old('total_tagihan', $pembayaran->total_tagihan) }}" required>
                    </div>

                    {{-- Jumlah Bayar --}}
                    <div class="col-md-6">
                        <label for="jumlah_bayar" class="form-label">Jumlah Bayar</label>
                        <input type="number" name="jumlah_bayar" id="jumlah_bayar" 
                               class="form-control" 
                               value="{{ old('jumlah_bayar', $pembayaran->jumlah_bayar) }}" required>
                    </div>

                    {{-- Tanggal Bayar --}}
                    <div class="col-md-6">
                        <label for="tanggal_bayar" class="form-label">Tanggal Bayar</label>
                        <input type="date" name="tanggal_bayar" id="tanggal_bayar" 
                               class="form-control" 
                               value="{{ old('tanggal_bayar', $pembayaran->tanggal_bayar) }}" required>
                    </div>

                    {{-- Metode Pembayaran --}}
                    <div class="col-md-6">
                        <label for="metode_pembayaran" class="form-label">Metode Pembayaran</label>
                        <select name="metode_pembayaran" id="metode_pembayaran" class="form-control" required>
                            <option value="">-- Pilih Metode --</option>
                            <option value="cash" {{ old('metode_pembayaran', $pembayaran->metode_pembayaran) == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="transfer_bank" {{ old('metode_pembayaran', $pembayaran->metode_pembayaran) == 'transfer_bank' ? 'selected' : '' }}>Transfer Bank</option>
                        </select>
                    </div>

                    {{-- Keterangan (Opsional) --}}
                    <div class="col-md-12">
                        <label for="keterangan" class="form-label">Keterangan (Opsional)</label>
                        <textarea name="keterangan" id="keterangan" class="form-control" rows="2">{{ old('keterangan', $pembayaran->keterangan) }}</textarea>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                    <a href="{{ route('admin.pembayaran.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
