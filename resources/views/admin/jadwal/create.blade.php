@extends('layouts.app')

@section('title', 'Tambah Jadwal')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Tambah Jadwal Pelajaran</h5>
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

            <form action="{{ route('admin.jadwal.store') }}" method="POST">
                @csrf

                <div class="row g-3">
                    {{-- Guru --}}
                    <div class="col-md-6">
                        <label for="guru_id" class="form-label">Guru & Mata Pelajaran</label>
                        <select name="guru_id" id="guru_id" class="form-control" required>
                            <option value="">-- Pilih Guru --</option>
                            @foreach($guru as $item)
                                <option value="{{ $item->id }}" {{ old('guru_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->nama }} - {{ $item->mapel }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Mata pelajaran akan otomatis sesuai dengan guru yang dipilih</small>
                    </div>

                    {{-- Kelas --}}
                    <div class="col-md-6">
                        <label for="kelas_id" class="form-label">Kelas</label>
                        <select name="kelas_id" id="kelas_id" class="form-control" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelas as $item)
                                <option value="{{ $item->id }}" {{ old('kelas_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Hari --}}
                    <div class="col-md-6">
                        <label for="hari" class="form-label">Hari</label>
                        <select name="hari" id="hari" class="form-control" required>
                            <option value="">-- Pilih Hari --</option>
                            @php
                                $hariList = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
                            @endphp
                            @foreach($hariList as $hari)
                                <option value="{{ $hari }}" {{ old('hari') == $hari ? 'selected' : '' }}>
                                    {{ $hari }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Mata Pelajaran (Hidden/Display Only) --}}
                    <div class="col-md-6">
                        <label class="form-label">Mata Pelajaran</label>
                        <div class="form-control bg-light" id="display_mapel">
                            <em class="text-muted">Pilih guru terlebih dahulu</em>
                        </div>
                        <input type="hidden" name="mata_pelajaran" id="mata_pelajaran" value="{{ old('mata_pelajaran') }}">
                    </div>

                    {{-- Jam Mulai --}}
                    <div class="col-md-6">
                        <label for="jam_mulai" class="form-label">Jam Mulai</label>
                        <input type="time" name="jam_mulai" id="jam_mulai" 
                               class="form-control" value="{{ old('jam_mulai') }}" required>
                    </div>

                    {{-- Jam Selesai --}}
                    <div class="col-md-6">
                        <label for="jam_selesai" class="form-label">Jam Selesai</label>
                        <input type="time" name="jam_selesai" id="jam_selesai" 
                               class="form-control" value="{{ old('jam_selesai') }}" required>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-success">Simpan</button>
                    <a href="{{ route('admin.jadwal.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('guru_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    if (selectedOption.value !== '') {
        // Extract mata pelajaran dari teks option (format: "Nama Guru - Mata Pelajaran")
        const text = selectedOption.text;
        const mapel = text.split(' - ')[1];
        
        document.getElementById('display_mapel').textContent = mapel;
        document.getElementById('mata_pelajaran').value = mapel;
    } else {
        document.getElementById('display_mapel').innerHTML = '<em class="text-muted">Pilih guru terlebih dahulu</em>';
        document.getElementById('mata_pelajaran').value = '';
    }
});

// Initialize on page load jika ada old value
document.addEventListener('DOMContentLoaded', function() {
    const guruSelect = document.getElementById('guru_id');
    if (guruSelect.value !== '') {
        guruSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection