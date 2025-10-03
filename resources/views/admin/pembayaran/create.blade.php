@extends('layouts.app')

@section('title', 'Tambah Pembayaran')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Tambah Pembayaran</h5>
        </div>
        <div class="card-body">

            {{-- Tampilkan error validasi --}}
            @if ($errors->any())
                <div class="alert alert-danger rounded-3">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.pembayaran.store') }}" method="POST">
                @csrf

                <div class="row g-3">
                    {{-- Nama Siswa --}}
                    <div class="col-md-6">
                        <label for="siswa_id" class="form-label">Nama Siswa</label>
                        <select name="siswa_id" id="siswa_id" 
                                class="form-control @error('siswa_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Siswa --</option>
                            @foreach($siswas as $siswa)
                                <option value="{{ $siswa->id }}" {{ old('siswa_id') == $siswa->id ? 'selected' : '' }}>
                                    {{ $siswa->nama }} (NIS: {{ $siswa->nis }})
                                </option>
                            @endforeach
                        </select>
                        @error('siswa_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Jenis Pembayaran --}}
                    <div class="col-md-6">
                        <label for="jenis_pembayaran" class="form-label">Jenis Pembayaran</label>
                        <input type="text" name="jenis_pembayaran" id="jenis_pembayaran" 
                               class="form-control @error('jenis_pembayaran') is-invalid @enderror"
                               value="{{ old('jenis_pembayaran') }}" required>
                        @error('jenis_pembayaran')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Total Tagihan --}}
                    <div class="col-md-6">
                        <label for="total_tagihan" class="form-label">Total Tagihan</label>
                        <input type="number" name="total_tagihan" id="total_tagihan" 
                               class="form-control @error('total_tagihan') is-invalid @enderror"
                               value="{{ old('total_tagihan') }}" required>
                        @error('total_tagihan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Jumlah Bayar --}}
                    <div class="col-md-6">
                        <label for="jumlah_bayar" class="form-label">Jumlah Bayar</label>
                        <input type="number" name="jumlah_bayar" id="jumlah_bayar" 
                               class="form-control @error('jumlah_bayar') is-invalid @enderror"
                               value="{{ old('jumlah_bayar') }}" required>
                        @error('jumlah_bayar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Tanggal Bayar --}}
                    <div class="col-md-6">
                        <label for="tanggal_bayar" class="form-label">Tanggal Bayar</label>
                        <input type="date" name="tanggal_bayar" id="tanggal_bayar" 
                               class="form-control @error('tanggal_bayar') is-invalid @enderror"
                               value="{{ old('tanggal_bayar', date('Y-m-d')) }}" required>
                        @error('tanggal_bayar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Metode Pembayaran --}}
                    <div class="col-md-6">
                        <label for="metode_pembayaran" class="form-label">Metode Pembayaran</label>
                        <select name="metode_pembayaran" id="metode_pembayaran" 
                                class="form-control @error('metode_pembayaran') is-invalid @enderror" required>
                            <option value="">-- Pilih Metode --</option>
                            <option value="cash" {{ old('metode_pembayaran') == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="transfer_bank" {{ old('metode_pembayaran') == 'transfer_bank' ? 'selected' : '' }}>Transfer Bank</option>
                        </select>
                        @error('metode_pembayaran')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Keterangan (Opsional) --}}
                    <div class="col-md-12">
                        <label for="keterangan" class="form-label">Keterangan (Opsional)</label>
                        <textarea name="keterangan" id="keterangan" 
                                  class="form-control @error('keterangan') is-invalid @enderror" 
                                  rows="2">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-success">Simpan</button>
                    <a href="{{ route('admin.pembayaran.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection
