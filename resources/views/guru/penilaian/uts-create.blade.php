@extends('layouts.app')

@section('title', 'Buat Ujian Tengah Semester Baru')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-alt me-2"></i>Buat Ujian Tengah Semester Baru
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('guru.penilaian.uts.store') }}" enctype="multipart/form-data" id="formUjianUTS">
                        @csrf
                        
                        <!-- Informasi Umum -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">
                                    <i class="fas fa-info-circle me-2"></i>Informasi Umum UTS
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Informasi:</strong> Ujian Tengah Semester (UTS) biasanya memiliki durasi lebih panjang dan bobot nilai yang lebih tinggi dibanding ujian harian.
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="judul_ujian" class="form-label">
                                                Judul Ujian <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control @error('judul_ujian') is-invalid @enderror" 
                                                   id="judul_ujian" name="judul_ujian" 
                                                   value="{{ old('judul_ujian') }}" 
                                                   placeholder="Contoh: UTS Matematika Semester Genap 2024" required>
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
                                            <div class="form-text">Nilai maksimal untuk UTS ini (1-100)</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="deskripsi" class="form-label">Deskripsi Ujian</label>
                                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                              id="deskripsi" name="deskripsi" rows="3" 
                                              placeholder="Deskripsi singkat tentang UTS ini, materi yang diujikan, dll (opsional)">{{ old('deskripsi') }}</textarea>
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
                                    <i class="fas fa-file-pdf me-2"></i>Berkas Ujian UTS
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>Perhatian:</strong> Pastikan soal UTS sudah sesuai dengan kisi-kisi dan materi yang telah diajarkan.
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="berkas_soal" class="form-label">
                                                Berkas Soal UTS (PDF) <span class="text-danger">*</span>
                                            </label>
                                            <input type="file" class="form-control @error('berkas_soal') is-invalid @enderror" 
                                                   id="berkas_soal" name="berkas_soal" 
                                                   accept=".pdf" required>
                                            @error('berkas_soal')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Maksimal 10MB. Format PDF. Disarankan menggunakan format yang jelas dan mudah dibaca.
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
                                                Opsional. Maksimal 10MB. Format PDF. Berguna untuk koreksi yang konsisten.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="file-preview mt-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div id="soal-preview" class="file-preview-item d-none">
                                                <i class="fas fa-file-pdf text-danger me-2"></i>
                                                <span id="soal-filename" class="small"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div id="kunci-preview" class="file-preview-item d-none">
                                                <i class="fas fa-file-pdf text-danger me-2"></i>
                                                <span id="kunci-filename" class="small"></span>
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
                                    <i class="fas fa-clock me-2"></i>Waktu Pelaksanaan UTS
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <i class="fas fa-clock me-2"></i>
                                    <strong>Durasi UTS:</strong> Ujian Tengah Semester biasanya berlangsung 2-3 jam.
                                </div>
                                
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
                                            <div class="form-text">Durasi: <span id="durasi-ujian">3 jam</span></div>
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
                                
                                <div class="time-validation mt-3">
                                    <div id="time-warning" class="alert alert-warning d-none">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        <span id="warning-message"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Instruksi Tambahan -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">
                                    <i class="fas fa-list-alt me-2"></i>Instruksi dan Pengaturan UTS
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="instruksi" class="form-label">Instruksi Pengerjaan UTS</label>
                                    <textarea class="form-control @error('instruksi') is-invalid @enderror" 
                                              id="instruksi" name="instruksi" rows="4" 
                                              placeholder="Contoh: 
- Kerjakan semua soal dengan teliti
- Tulis nama dan kelas di lembar jawaban
- Dilarang bekerjasama
- Waktu pengerjaan 120 menit
- Dll...">{{ old('instruksi') }}</textarea>
                                    @error('instruksi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        Instruksi khusus untuk siswa dalam mengerjakan UTS
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="confirm_uts" required>
                                        <label class="form-check-label" for="confirm_uts">
                                            Saya telah memeriksa semua informasi UTS dan memastikan soal sesuai dengan materi yang telah diajarkan
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('guru.penilaian.uts.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Kembali ke Daftar UTS
                            </a>
                            <div>
                                <button type="submit" name="action" value="draft" class="btn btn-warning me-2">
                                    <i class="fas fa-save me-1"></i>Simpan Draft
                                </button>
                                <button type="submit" name="action" value="publish" class="btn btn-success" id="btn-publish">
                                    <i class="fas fa-paper-plane me-1"></i>Buat & Publish UTS
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
    
    .file-preview-item {
        padding: 0.5rem;
        background-color: #f8f9fa;
        border-radius: 0.375rem;
        border: 1px solid #dee2e6;
    }
    
    .alert {
        border-radius: 0.375rem;
    }
    
    .required-field::after {
        content: " *";
        color: #dc3545;
    }
    
    #durasi-ujian {
        font-weight: 600;
        color: #0d6efd;
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
        
        // Elements
        const waktuMulaiInput = document.getElementById('waktu_mulai');
        const waktuSelesaiInput = document.getElementById('waktu_selesai');
        const batasPengumpulanInput = document.getElementById('batas_pengumpulan');
        const durasiElement = document.getElementById('durasi-ujian');
        const timeWarning = document.getElementById('time-warning');
        const warningMessage = document.getElementById('warning-message');
        const btnPublish = document.getElementById('btn-publish');
        const confirmCheckbox = document.getElementById('confirm_uts');
        
        // Calculate and display duration
        function calculateDuration() {
            const mulai = new Date(waktuMulaiInput.value);
            const selesai = new Date(waktuSelesaiInput.value);
            
            if (mulai && selesai && selesai > mulai) {
                const diffMs = selesai - mulai;
                const diffHours = Math.floor(diffMs / (1000 * 60 * 60));
                const diffMinutes = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60));
                
                let durationText = '';
                if (diffHours > 0) {
                    durationText += `${diffHours} jam `;
                }
                if (diffMinutes > 0) {
                    durationText += `${diffMinutes} menit`;
                }
                
                durasiElement.textContent = durationText || '0 menit';
                
                // Show warning if duration is too short or too long for UTS
                if (diffHours < 1) {
                    showWarning('Durasi UTS terlalu pendek. UTS biasanya berlangsung 2-3 jam.');
                } else if (diffHours > 4) {
                    showWarning('Durasi UTS terlalu panjang. Pastikan durasi sesuai dengan kebutuhan.');
                } else {
                    hideWarning();
                }
            }
        }
        
        function showWarning(message) {
            warningMessage.textContent = message;
            timeWarning.classList.remove('d-none');
        }
        
        function hideWarning() {
            timeWarning.classList.add('d-none');
        }
        
        // Auto set batas_pengumpulan if empty
        waktuSelesaiInput.addEventListener('change', function() {
            calculateDuration();
            
            if (!batasPengumpulanInput.value) {
                // Set batas pengumpulan 1 hour after waktu selesai for UTS
                const selesaiTime = new Date(this.value);
                selesaiTime.setHours(selesaiTime.getHours() + 1);
                batasPengumpulanInput.value = selesaiTime.toISOString().slice(0, 16);
            }
        });
        
        waktuMulaiInput.addEventListener('change', calculateDuration);
        waktuSelesaiInput.addEventListener('change', calculateDuration);
        
        // Initial duration calculation
        calculateDuration();
        
        // File preview functionality
        document.getElementById('berkas_soal').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('soal-preview');
            const filename = document.getElementById('soal-filename');
            
            if (file) {
                filename.textContent = file.name;
                preview.classList.remove('d-none');
            } else {
                preview.classList.add('d-none');
            }
        });
        
        document.getElementById('berkas_kunci_jawaban').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('kunci-preview');
            const filename = document.getElementById('kunci-filename');
            
            if (file) {
                filename.textContent = file.name;
                preview.classList.remove('d-none');
            } else {
                preview.classList.add('d-none');
            }
        });
        
        // Form validation
        const form = document.getElementById('formUjianUTS');
        form.addEventListener('submit', function(e) {
            // Time validation
            const waktuMulai = new Date(waktuMulaiInput.value);
            const waktuSelesai = new Date(waktuSelesaiInput.value);
            
            if (waktuSelesai <= waktuMulai) {
                e.preventDefault();
                alert('Waktu selesai harus setelah waktu mulai!');
                waktuSelesaiInput.focus();
                return false;
            }
            
            const batasPengumpulan = batasPengumpulanInput.value;
            if (batasPengumpulan) {
                const batasTime = new Date(batasPengumpulan);
                if (batasTime <= waktuMulai) {
                    e.preventDefault();
                    alert('Batas pengumpulan harus setelah waktu mulai!');
                    batasPengumpulanInput.focus();
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
            
            // Confirmation checkbox validation
            if (!confirmCheckbox.checked) {
                e.preventDefault();
                alert('Harap konfirmasi bahwa Anda telah memeriksa semua informasi UTS!');
                confirmCheckbox.focus();
                return false;
            }
        });
        
        // Publish button enhancement
        btnPublish.addEventListener('click', function(e) {
            const durasi = new Date(waktuSelesaiInput.value) - new Date(waktuMulaiInput.value);
            const durasiJam = durasi / (1000 * 60 * 60);
            
            if (durasiJam < 1) {
                if (!confirm('Durasi UTS kurang dari 1 jam. Apakah Anda yakin ingin melanjutkan?')) {
                    e.preventDefault();
                    return false;
                }
            }
            
            if (!confirm('Apakah Anda yakin ingin mempublish Ujian Tengah Semester ini? Siswa akan dapat melihat dan mengerjakan UTS setelah dipublish.')) {
                e.preventDefault();
                return false;
            }
        });
    });
</script>
@endpush