@extends('layouts.app')

@section('title', 'Edit Kelas')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Edit Kelas</h5>
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

            <form action="{{ route('admin.kelas.update', $kelas->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="nama_kelas" class="form-label">Nama Kelas</label>
                        <input 
                            type="text" 
                            name="nama_kelas" 
                            id="nama_kelas" 
                            class="form-control @error('nama_kelas') is-invalid @enderror"
                            value="{{ old('nama_kelas', $kelas->nama_kelas) }}" 
                            required
                        >
                        @error('nama_kelas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="wali_kelas_id" class="form-label">Wali Kelas</label>
                        <select 
                            name="wali_kelas_id" 
                            id="wali_kelas_id" 
                            class="form-control @error('wali_kelas_id') is-invalid @enderror" 
                            required
                        >
                            <option value="">-- Pilih Guru --</option>
                            @foreach($gurus as $guru)
                                <option value="{{ $guru->id }}" {{ old('wali_kelas_id', $kelas->wali_kelas_id) == $guru->id ? 'selected' : '' }}>
                                    {{ $guru->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('wali_kelas_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-success">Perbarui</button>
                    <a href="{{ route('admin.kelas.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection
