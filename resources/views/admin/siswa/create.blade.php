@extends('layouts.app')

@section('title', 'Tambah Siswa')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Tambah Data Siswa</h5>
        </div>
        <div class="card-body">

            {{-- Tampilkan error validasi --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form tambah siswa --}}
            <form action="{{ route('admin.siswa.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="nis" class="form-label">NIS</label>
                        <input type="text" name="nis" class="form-control" value="{{ old('nis') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" id="password" name="password" class="form-control" required>
                            <button type="button" class="input-group-text toggle-password">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="kelas_id" class="form-label">Kelas</label>
                        <select name="kelas_id" class="form-control" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>
                                    {{ $k->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-control" required>
                            <option value="">-- Pilih --</option>
                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-Laki</option>
                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir') }}" required>
                    </div>
                    <div class="col-md-12">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea name="alamat" class="form-control" rows="2" required>{{ old('alamat') }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label for="tlp_orang_tua" class="form-label">Telepon Orang Tua</label>
                        <input type="text" name="tlp_orang_tua" class="form-control" value="{{ old('tlp_orang_tua') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="foto" class="form-label">Foto Profil</label>
                        <input type="file" name="foto" id="foto" class="form-control" accept="image/jpeg,image/png" required>
                        <small class="text-muted">Format: JPG, PNG. Maksimal 2MB</small>
                        <div class="mt-2">
                            <img id="preview" src="#" alt="Preview Foto" style="display: none; max-width: 200px; max-height: 200px; border-radius: 5px;">
                        </div>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-success">Simpan</button>
                    <a href="{{ route('admin.siswa.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(function(btn) {
        btn.addEventListener('click', function () {
            const input = this.parentElement.querySelector('input');
            const icon = this.querySelector('i');
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        });
    });

    // Preview image before upload
    const fotoInput = document.getElementById('foto');
    const preview = document.getElementById('preview');

    fotoInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            // Validasi ukuran file (2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert("Ukuran file maksimal 2MB!");
                this.value = "";
                preview.style.display = "none";
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                preview.style.display = 'block';
                preview.setAttribute('src', e.target.result);
            };
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
            preview.setAttribute('src', '#');
        }
    });
});
</script>
@endpush
