@extends('layouts.app')

@section('title', 'Edit Absensi')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Edit Absensi Siswa</h5>
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

            <form action="{{ route('admin.absensi.update', $absensi->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="siswa_id" class="form-label">Nama Siswa</label>
                        <select name="siswa_id" id="siswa_id" class="form-control" required>
                            <option value="">-- Pilih Siswa --</option>
                            @foreach($siswas as $siswa)
                                <option value="{{ $siswa->id }}" {{ old('siswa_id', $absensi->siswa_id) == $siswa->id ? 'selected' : '' }}>
                                    {{ $siswa->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="tanggal" class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal" class="form-control" 
                               value="{{ old('tanggal', $absensi->tanggal) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-control" required>
                            <option value="">-- Pilih Status --</option>
                            <option value="Hadir" {{ old('status', $absensi->status) == 'Hadir' ? 'selected' : '' }}>Hadir</option>
                            <option value="Sakit" {{ old('status', $absensi->status) == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                            <option value="Izin" {{ old('status', $absensi->status) == 'Izin' ? 'selected' : '' }}>Izin</option>
                            <option value="Alpha" {{ old('status', $absensi->status) == 'Alpha' ? 'selected' : '' }}>Alpha</option>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label for="keterangan_izin" class="form-label">Keterangan Izin</label>
                        <textarea name="keterangan_izin" id="keterangan_izin" class="form-control" rows="2">{{ old('keterangan_izin', $absensi->keterangan_izin) }}</textarea>
                        <small class="form-text text-muted">Hanya diisi jika status = Izin.</small>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-success">Perbarui</button>
                    <a href="{{ route('admin.absensi.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection
