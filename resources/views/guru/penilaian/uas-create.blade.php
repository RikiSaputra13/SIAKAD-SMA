@extends('layouts.app')

@section('title', 'Buat Ujian Akhir Semester Baru')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-graduation-cap me-2"></i>Buat Ujian Akhir Semester Baru
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('guru.penilaian.uas.store') }}" enctype="multipart/form-data" id="formUjianUAS">
                        @csrf
                        
                        <!-- Informasi Umum -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">
                                    <i class="fas fa-info-circle me-2"></i>Informasi Umum Ujian Akhir Semester
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-danger">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>Penting:</strong> Ujian Akhir Semester (UAS/PAS) merupakan evaluasi akhir dengan bobot nilai tertinggi. Pastikan soal mencakup seluruh materi semester.
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
                                                   placeholder="Contoh: UAS Matematika Semester Genap 2024" required>
                                            @error('judul_ujian')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Gunakan format yang jelas, misal: "UAS/PAS [Mata Pelajaran] [Semester]"</div>
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
                                            <div class="input-group">
                                                <input type="number" class="form-control @error('total_nilai') is-invalid @enderror" 
                                                       id="total_nilai" name="total_nilai" 
                                                       value="{{ old('total_nilai', 100) }}" 
                                                       min="1" max="100" required>
                                                <span class="input-group-text">/ 100</span>
                                            </div>
                                            @error('total_nilai')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Nilai maksimal untuk UAS ini. Biasanya 100 untuk evaluasi akhir.</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="deskripsi" class="form-label">Deskripsi Ujian</label>
                                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                              id="deskripsi" name="deskripsi" rows="3" 
                                              placeholder="Deskripsi tentang UAS ini, cakupan materi, kisi-kisi, dll (opsional)">{{ old('deskripsi') }}</textarea>
                                    @error('deskripsi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Disarankan untuk mencantumkan kisi-kisi atau materi yang diujikan</div>
                                </div>
                            </div>
                        </div>

                        <!-- Berkas Ujian -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">
                                    <i class="fas fa-file-pdf me-2"></i>Berkas Ujian Akhir Semester
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-warning">
                                    <i class="fas fa-clipboard-check me-2"></i>
                                    <strong>Kualitas Soal:</strong> Pastikan soal UAS telah melalui proses verifikasi dan sesuai dengan kurikulum. Soal harus mencakup seluruh kompetensi dasar semester.
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="berkas_soal" class="form-label">
                                                Berkas Soal UAS (PDF) <span class="text-danger">*</span>
                                            </label>
                                            <input type="file" class="form-control @error('berkas_soal') is-invalid @enderror" 
                                                   id="berkas_soal" name="berkas_soal" 
                                                   accept=".pdf" required>
                                            @error('berkas_soal')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Maksimal 10MB. Format PDF. Pastikan format jelas dan mudah dibaca.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="berkas_kunci_jawaban" class="form-label">
                                                Berkas Kunci Jawaban (PDF) <span class="text-danger">*</span>
                                            </label>
                                            <input type="file" class="form-control @error('berkas_kunci_jawaban') is-invalid @enderror" 
                                                   id="berkas_kunci_jawaban" name="berkas_kunci_jawaban" 
                                                   accept=".pdf" required>
                                            @error('berkas_kunci_jawaban')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Wajib untuk UAS. Maksimal 10MB. Format PDF.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="file-preview mt-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div id="soal-preview" class="file-preview-item d-none">
                                                <i class="fas fa-file-pdf text-danger me-2"></i>
                                                <span id="soal-filename" class="small fw-bold"></span>
                                                <span class="badge bg-danger ms-2">WAJIB</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div id="kunci-preview" class="file-preview-item d-none">
                                                <i class="fas fa-file-pdf text-danger me-2"></i>
                                                <span id="kunci-filename" class="small fw-bold"></span>
                                                <span class="badge bg-danger ms-2">WAJIB</span>
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
                                    <i class="fas fa-clock me-2"></i>Waktu Pelaksanaan UAS
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <i class="fas fa-calendar-alt me-2"></i>
                                    <strong>Jadwal UAS:</strong> Ujian Akhir Semester biasanya berlangsung lebih lama (3-4 jam) dan dijadwalkan sesuai kalender akademik.
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
                                            <div class="form-text">
                                                Durasi: <span id="durasi-ujian" class="fw-bold">4 jam</span>
                                                <span id="durasi-warning" class="text-warning ms-2 d-none"></span>
                                            </div>
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
                                            <div class="form-text">Opsional. Jika kosong, menggunakan waktu selesai + toleransi</div>
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
                                    <i class="fas fa-list-alt me-2"></i>Instruksi dan Pengaturan UAS
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="instruksi" class="form-label">Instruksi Pengerjaan UAS</label>
                                    <textarea class="form-control @error('instruksi') is-invalid @enderror" 
                                              id="instruksi" name="instruksi" rows="5" 
                                              placeholder="Contoh instruksi UAS:
● UJIAN AKHIR SEMESTER - MATA PELAJARAN MATEMATIKA
● Waktu: 180 menit (3 jam)
● Jumlah soal: 40 butir pilihan ganda + 5 essay
● Bobot nilai: Pilihan ganda (60%), Essay (40%)
● Tulis nama, kelas, dan nomor peserta di lembar jawaban
● Dilarang keras bekerjasama, menyontek, atau menggunakan alat bantu tidak sah
● Periksa kembali jawaban sebelum dikumpulkan
● Teliti dalam membaca soal dan menjawab">{{ old('instruksi') }}</textarea>
                                    @error('instruksi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        Berikan instruksi yang jelas dan lengkap untuk Ujian Akhir Semester
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="confirm_kurikulum" required>
                                                <label class="form-check-label" for="confirm_kurikulum">
                                                    Soal telah disusun sesuai dengan kurikulum dan mencakup seluruh kompetensi dasar semester
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="confirm_verifikasi" required>
                                                <label class="form-check-label" for="confirm_verifikasi">
                                                    Soal dan kunci jawaban telah melalui proses verifikasi dan validasi
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('guru.penilaian.uas.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Kembali ke Daftar UAS
                            </a>
                            <div>
                                <button type="submit" name="action" value="draft" class="btn btn-warning me-2">
                                    <i class="fas fa-save me-1"></i>Simpan Draft
                                </button>
                                <button type="submit" name="action" value="publish" class="btn btn-danger" id="btn-publish">
                                    <i class="fas fa-paper-plane me-1"></i>Buat & Publish UAS
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
        padding: 0.75rem;
        background-color: #f8f9fa;
        border-radius: 0.375rem;
        border: 1px solid #dee2e6;
        display: flex;
        align-items: center;
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
        color: #dc3545;
    }
    
    .input-group-text {
        background-color: #e9ecef;
        border: 1px solid #ced4da;
    }
    
    .badge {
        font-size: 0.7em;
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
        const durasiWarning = document.getElementById('durasi-warning');
        const timeWarning = document.getElementById('time-warning');
        const warningMessage = document.getElementById('warning-message');
        const btnPublish = document.getElementById('btn-publish');
        const confirmKurikulum = document.getElementById('confirm_kurikulum');
        const confirmVerifikasi = document.getElementById('confirm_verifikasi');
        
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
                
                // Show warning if duration is not appropriate for UAS
                if (diffHours < 2) {
                    showWarning('Durasi UAS terlalu pendek. UAS biasanya berlangsung 3-4 jam.');
                    durasiWarning.textContent = '⚠ Durasi kurang ideal';
                    durasiWarning.classList.remove('d-none');
                } else if (diffHours > 5) {
                    showWarning('Durasi UAS terlalu panjang. Pastikan durasi sesuai dengan standar akademik.');
                    durasiWarning.textContent = '⚠ Durasi terlalu panjang';
                    durasiWarning.classList.remove('d-none');
                } else {
                    hideWarning();
                    durasiWarning.classList.add('d-none');
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
                // Set batas pengumpulan 1.5 hours after waktu selesai for UAS
                const selesaiTime = new Date(this.value);
                selesaiTime.setHours(selesaiTime.getHours() + 1, selesaiTime.getMinutes() + 30);
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
        const form = document.getElementById('formUjianUAS');
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
            
            // File validation - Kunci jawaban wajib untuk UAS
            const berkasSoal = document.getElementById('berkas_soal').files[0];
            const berkasKunci = document.getElementById('berkas_kunci_jawaban').files[0];
            
            if (!berkasSoal) {
                e.preventDefault();
                alert('Berkas soal UAS wajib diunggah!');
                return false;
            }
            
            if (!berkasKunci) {
                e.preventDefault();
                alert('Berkas kunci jawaban UAS wajib diunggah!');
                return false;
            }
            
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
            
            // Confirmation checkboxes validation
            if (!confirmKurikulum.checked || !confirmVerifikasi.checked) {
                e.preventDefault();
                alert('Harap konfirmasi semua persyaratan UAS!');
                return false;
            }
        });
        
        // Publish button enhancement for UAS
        btnPublish.addEventListener('click', function(e) {
            const durasi = new Date(waktuSelesaiInput.value) - new Date(waktuMulaiInput.value);
            const durasiJam = durasi / (1000 * 60 * 60);
            
            if (durasiJam < 2) {
                if (!confirm('Durasi UAS kurang dari 2 jam. UAS biasanya membutuhkan waktu lebih lama. Apakah Anda yakin ingin melanjutkan?')) {
                    e.preventDefault();
                    return false;
                }
            }
            
            if (!confirm('PERINGATAN: Ujian Akhir Semester akan dipublish dan dapat diakses siswa. Pastikan semua data sudah benar. Lanjutkan?')) {
                e.preventDefault();
                return false;
            }
        });
    });
</script>
@endpush