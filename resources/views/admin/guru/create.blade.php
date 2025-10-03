@extends('layouts.app')

@section('title', 'Tambah Guru')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Tambah Guru</h5>
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

            <form action="{{ route('admin.guru.store') }}" method="POST">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" name="nama" id="nama" class="form-control" 
                               value="{{ old('nama') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="nip" class="form-label">NIP</label>
                        <input type="text" name="nip" id="nip" class="form-control" 
                               value="{{ old('nip') }}">
                    </div>

                    <div class="col-md-6">
                        <label for="mapel" class="form-label">Mata Pelajaran</label>
                        <input type="text" name="mapel" id="mapel" class="form-control" 
                               value="{{ old('mapel') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                        <select name="jenis_kelamin" id="jenis_kelamin" class="form-control" required>
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea name="alamat" id="alamat" class="form-control" rows="2">{{ old('alamat') }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label for="no_hp" class="form-label">No HP</label>
                        <input type="text" name="no_hp" id="no_hp" class="form-control" 
                               value="{{ old('no_hp') }}">
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-success">Simpan</button>
                    <a href="{{ route('admin.guru.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection
