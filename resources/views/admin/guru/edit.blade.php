@extends('layouts.app')

@section('title', 'Edit Guru')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Edit Guru</h5>
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

            <form action="{{ route('admin.guru.update', $guru->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">

                    <div class="col-md-6">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" name="nama" id="nama" 
                               class="form-control" value="{{ old('nama', $guru->nama) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" 
                               class="form-control" value="{{ old('email', $guru->email) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="password" class="form-label">Password (Opsional)</label>
                        <div class="input-group">
                            <input type="password" name="password" id="password" class="form-control">
                            <button type="button" class="input-group-text toggle-password">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                        <small class="text-muted">Biarkan kosong jika tidak ingin mengubah password.</small>
                    </div>

                    <div class="col-md-6">
                        <label for="nip" class="form-label">NIP</label>
                        <input type="text" name="nip" id="nip" 
                               class="form-control" value="{{ old('nip', $guru->nip) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="mapel" class="form-label">Mata Pelajaran</label>
                        <input type="text" name="mapel" id="mapel" 
                               class="form-control" value="{{ old('mapel', $guru->mapel) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                        <select name="jenis_kelamin" id="jenis_kelamin" class="form-control" required>
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="L" {{ old('jenis_kelamin', $guru->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin', $guru->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea name="alamat" id="alamat" class="form-control" rows="2">{{ old('alamat', $guru->alamat) }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label for="no_hp" class="form-label">No HP</label>
                        <input type="text" name="no_hp" id="no_hp" 
                               class="form-control" value="{{ old('no_hp', $guru->no_hp) }}">
                    </div>

                    <div class="col-md-6">
                        <label for="foto" class="form-label">Foto Profil</label>
                        <input type="file" name="foto" id="foto" class="form-control" accept="image/jpeg,image/png">
                        <small class="text-muted">Format: JPG & PNG, Maksimal 2MB</small>

                        <div class="mt-2">
                            @if($guru->foto)
                                <img id="preview" src="{{ asset('storage/guru/'.$guru->foto) }}" style="max-width:160px; border-radius:5px;">
                            @else
                                <img id="preview" src="#" style="display:none; max-width:160px; border-radius:5px;">
                            @endif
                        </div>
                    </div>

                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-success">Perbarui</button>
                    <a href="{{ route('admin.guru.index') }}" class="btn btn-secondary">Batal</a>
                </div>

            </form>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.toggle-password').forEach(function(btn) {
        btn.addEventListener('click', function () {
            const input = this.parentElement.querySelector('input');
            const icon = this.querySelector('i');
            input.type = input.type === 'password' ? 'text' : 'password';
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        });
    });

    const foto = document.getElementById('foto');
    const preview = document.getElementById('preview');

    foto.addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
            if (file.size > 2 * 1024 * 1024) {
                alert("Ukuran file maksimal 2MB!");
                this.value = "";
                preview.style.display = "none";
                return;
            }
            const reader = new FileReader();
            reader.onload = e => {
                preview.src = e.target.result;
                preview.style.display = "block";
            };
            reader.readAsDataURL(file);
        }
    });
});
</script>
@endpush
