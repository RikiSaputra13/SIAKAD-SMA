@extends('layouts.app')

@section('title', 'Buat Ujian Harian Baru')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-plus-circle me-2"></i>Buat Ujian Harian Baru
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('guru.penilaian.uh.store') }}" enctype="multipart/form-data" id="formUjianHarian">
                        @csrf
                        
                        <!-- Informasi Umum -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">
                                    <i class="fas fa-info-circle me-2"></i>Informasi Umum
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="judul_ujian" class="form-label">
                                                Judul Ujian <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control @error('judul_ujian') is-invalid @enderror" 
                                                   id="judul_ujian" name="judul_ujian" 
                                                   value="{{ old('judul_ujian') }}" 
                                                   placeholder="Masukkan judul ujian" required>
                                            @error('judul_ujian')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="kelas_id" class="form-label">
                                                Kelas <span class="text-danger">*</span>
                                            </label>
                                            <select name="kelas_id" id="kelas_id" 
                                                    class="form-select @error('kelas_id') is-invalid @enderror" required>
                                                <option value="">Pilih Kelas</option>
                                                @foreach($kelasOptions as $id => $nama)
                                                    <option value="{{ $id }}" {{ old('kelas_id') == $id ? 'selected' : '' }}>
                                                        {{ $nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('kelas_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="mata_pelajaran" class="form-label">
                                                Mata Pelajaran <span class="text-danger">*</span>
                                            </label>
                                            <select name="mata_pelajaran" id="mata_pelajaran" 
                                                    class="form-select @error('mata_pelajaran') is-invalid @enderror" required>
                                                <option value="">Pilih Mata Pelajaran</option>
                                                @foreach($mapelOptions as $mapel)
                                                    <option value="{{ $mapel }}" {{ old('mata_pelajaran') == $mapel ? 'selected' : '' }}>
                                                        {{ $mapel }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('mata_pelajaran')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="total_nilai" class="form-label">
                                                Total Nilai <span class="text-danger">*</span>
                                            </label>
                                            <input type="number" class="form-control @error('total_nilai') is-invalid @enderror" 
                                                   id="total_nilai" name="total_nilai" 
                                                   value="{{ old('total_nilai', 100) }}" 
                                                   min="1" max="100" required>
                                            @error('total_nilai')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Nilai maksimal untuk ujian ini (1-100)</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="deskripsi" class="form-label">Deskripsi Ujian</label>
                                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                              id="deskripsi" name="deskripsi" rows="3" 
                                              placeholder="Deskripsi singkat tentang ujian ini (opsional)">{{ old('deskripsi') }}</textarea>
                                    @error('deskripsi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Berkas Ujian -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">
                                    <i class="fas fa-file-pdf me-2"></i>Berkas Ujian
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="berkas_soal" class="form-label">
                                                Berkas Soal (PDF) <span class="text-danger">*</span>
                                            </label>
                                            <input type="file" class="form-control @error('berkas_soal') is-invalid @enderror" 
                                                   id="berkas_soal" name="berkas_soal" 
                                                   accept=".pdf" required>
                                            @error('berkas_soal')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Maksimal 10MB. Format PDF
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="berkas_kunci_jawaban" class="form-label">
                                                Berkas Kunci Jawaban (PDF)
                                            </label>
                                            <input type="file" class="form-control @error('berkas_kunci_jawaban') is-invalid @enderror" 
                                                   id="berkas_kunci_jawaban" name="berkas_kunci_jawaban" 
                                                   accept=".pdf">
                                            @error('berkas_kunci_jawaban')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Opsional. Maksimal 10MB. Format PDF
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Waktu Pelaksanaan -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">
                                    <i class="fas fa-clock me-2"></i>Waktu Pelaksanaan
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="waktu_mulai" class="form-label">
                                                Waktu Mulai <span class="text-danger">*</span>
                                            </label>
                                            <input type="datetime-local" class="form-control @error('waktu_mulai') is-invalid @enderror" 
                                                   id="waktu_mulai" name="waktu_mulai" 
                                                   value="{{ old('waktu_mulai', $waktuDefault['mulai']) }}" required>
                                            @error('waktu_mulai')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="waktu_selesai" class="form-label">
                                                Waktu Selesai <span class="text-danger">*</span>
                                            </label>
                                            <input type="datetime-local" class="form-control @error('waktu_selesai') is-invalid @enderror" 
                                                   id="waktu_selesai" name="waktu_selesai" 
                                                   value="{{ old('waktu_selesai', $waktuDefault['selesai']) }}" required>
                                            @error('waktu_selesai')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="batas_pengumpulan" class="form-label">
                                                Batas Pengumpulan
                                            </label>
                                            <input type="datetime-local" class="form-control @error('batas_pengumpulan') is-invalid @enderror" 
                                                   id="batas_pengumpulan" name="batas_pengumpulan" 
                                                   value="{{ old('batas_pengumpulan', $waktuDefault['batas']) }}">
                                            @error('batas_pengumpulan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Opsional. Jika kosong, menggunakan waktu selesai</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Instruksi Tambahan -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">
                                    <i class="fas fa-list-alt me-2"></i>Instruksi Tambahan
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="instruksi" class="form-label">Instruksi Pengerjaan</label>
                                    <textarea class="form-control @error('instruksi') is-invalid @enderror" 
                                              id="instruksi" name="instruksi" rows="4" 
                                              placeholder="Instruksi khusus untuk siswa (opsional)">{{ old('instruksi') }}</textarea>
                                    @error('instruksi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('guru.penilaian.uh.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Kembali
                            </a>
                            <div>
                                <button type="submit" name="action" value="draft" class="btn btn-warning me-2">
                                    <i class="fas fa-save me-1"></i>Simpan Draft
                                </button>
                                <button type="submit" name="action" value="publish" class="btn btn-success">
                                    <i class="fas fa-paper-plane me-1"></i>Buat & Publish
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        border: none;
        border-radius: 0.5rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    
    .card-header {
        border-bottom: 1px solid rgba(0, 0, 0, 0.125);
        font-weight: 600;
    }
    
    .form-label {
        font-weight: 500;
        margin-bottom: 0.5rem;
    }
    
    .form-text {
        font-size: 0.875rem;
        color: #6c757d;
    }
    
    .btn {
        border-radius: 0.375rem;
        font-weight: 500;
    }
    
    .required-field::after {
        content: " *";
        color: #dc3545;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set minimum datetime for time inputs
        const now = new Date();
        const minDateTime = now.toISOString().slice(0, 16);
        
        document.getElementById('waktu_mulai').min = minDateTime;
        document.getElementById('waktu_selesai').min = minDateTime;
        document.getElementById('batas_pengumpulan').min = minDateTime;
        
        // Auto set batas_pengumpulan if empty
        const waktuSelesai = document.getElementById('waktu_selesai');
        const batasPengumpulan = document.getElementById('batas_pengumpulan');
        
        waktuSelesai.addEventListener('change', function() {
            if (!batasPengumpulan.value) {
                // Set batas pengumpulan 1 hour after waktu selesai
                const selesaiTime = new Date(this.value);
                selesaiTime.setHours(selesaiTime.getHours() + 1);
                batasPengumpulan.value = selesaiTime.toISOString().slice(0, 16);
            }
        });
        
        // Form validation
        const form = document.getElementById('formUjianHarian');
        form.addEventListener('submit', function(e) {
            const waktuMulai = new Date(document.getElementById('waktu_mulai').value);
            const waktuSelesai = new Date(document.getElementById('waktu_selesai').value);
            
            if (waktuSelesai <= waktuMulai) {
                e.preventDefault();
                alert('Waktu selesai harus setelah waktu mulai!');
                return false;
            }
            
            const batasPengumpulan = document.getElementById('batas_pengumpulan').value;
            if (batasPengumpulan) {
                const batasTime = new Date(batasPengumpulan);
                if (batasTime <= waktuMulai) {
                    e.preventDefault();
                    alert('Batas pengumpulan harus setelah waktu mulai!');
                    return false;
                }
            }
            
            // File validation
            const berkasSoal = document.getElementById('berkas_soal').files[0];
            if (berkasSoal) {
                const maxSize = 10 * 1024 * 1024; // 10MB
                if (berkasSoal.size > maxSize) {
                    e.preventDefault();
                    alert('Ukuran file soal terlalu besar! Maksimal 10MB.');
                    return false;
                }
                
                if (berkasSoal.type !== 'application/pdf') {
                    e.preventDefault();
                    alert('Format file soal harus PDF!');
                    return false;
                }
            }
            
            const berkasKunci = document.getElementById('berkas_kunci_jawaban').files[0];
            if (berkasKunci) {
                const maxSize = 10 * 1024 * 1024; // 10MB
                if (berkasKunci.size > maxSize) {
                    e.preventDefault();
                    alert('Ukuran file kunci jawaban terlalu besar! Maksimal 10MB.');
                    return false;
                }
                
                if (berkasKunci.type !== 'application/pdf') {
                    e.preventDefault();
                    alert('Format file kunci jawaban harus PDF!');
                    return false;
                }
            }
        });
        
        // Preview file names
        document.getElementById('berkas_soal').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name || 'Tidak ada file dipilih';
            document.querySelector('label[for="berkas_soal"] + .form-text').innerHTML = 
                `<i class="fas fa-info-circle me-1"></i>File: ${fileName}`;
        });
        
        document.getElementById('berkas_kunci_jawaban').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name || 'Tidak ada file dipilih';
            document.querySelector('label[for="berkas_kunci_jawaban"] + .form-text').innerHTML = 
                `<i class="fas fa-info-circle me-1"></i>File: ${fileName}`;
        });
    });
</script>
@endpush