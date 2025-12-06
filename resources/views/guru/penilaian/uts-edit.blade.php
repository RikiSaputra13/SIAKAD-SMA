@extends('layouts.app')

@section('title', 'Edit Ujian Tengah Semester')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2"></i>Edit Ujian Tengah Semester
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('guru.penilaian.uts.update', $ujian->id) }}" enctype="multipart/form-data" id="formEditUjianUTS">
                        @csrf
                        @method('PUT')
                        
                        <!-- Status Ujian Info -->
                        @if($ujian->status == 'published')
                        <div class="alert alert-info mb-4">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Status: PUBLISHED</strong> - Ujian ini sudah dapat diakses siswa. Perubahan mungkin mempengaruhi siswa yang sedang/sudah mengerjakan.
                        </div>
                        @elseif($ujian->status == 'draft')
                        <div class="alert alert-warning mb-4">
                            <i class="fas fa-pencil-alt me-2"></i>
                            <strong>Status: DRAFT</strong> - Ujian ini belum dipublish dan belum dapat diakses siswa.
                        </div>
                        @endif
                        
                        <!-- Informasi Umum -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">
                                    <i class="fas fa-info-circle me-2"></i>Informasi Umum Ujian Tengah Semester
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-primary">
                                    <i class="fas fa-exclamation-circle me-2"></i>
                                    <strong>Penting:</strong> Ujian Tengah Semester (UTS/PTS) merupakan evaluasi tengah semester dengan bobot nilai yang signifikan.
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="judul_ujian" class="form-label">
                                                Judul Ujian <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control @error('judul_ujian') is-invalid @enderror" 
                                                   id="judul_ujian" name="judul_ujian" 
                                                   value="{{ old('judul_ujian', $ujian->judul_ujian) }}" 
                                                   placeholder="Contoh: UTS Matematika Semester Genap 2024" required>
                                            @error('judul_ujian')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Gunakan format yang jelas, misal: "UTS/PTS [Mata Pelajaran] [Semester]"</div>
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
                                                    <option value="{{ $id }}" {{ old('kelas_id', $ujian->kelas_id) == $id ? 'selected' : '' }}>
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
                                                    <option value="{{ $mapel }}" {{ old('mata_pelajaran', $ujian->mata_pelajaran) == $mapel ? 'selected' : '' }}>
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
                                                       value="{{ old('total_nilai', $ujian->total_nilai) }}" 
                                                       min="1" max="100" required>
                                                <span class="input-group-text">/ 100</span>
                                            </div>
                                            @error('total_nilai')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Nilai maksimal untuk UTS ini. Biasanya 100 untuk evaluasi tengah semester.</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="deskripsi" class="form-label">Deskripsi Ujian</label>
                                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                              id="deskripsi" name="deskripsi" rows="3" 
                                              placeholder="Deskripsi tentang UTS ini, cakupan materi, kisi-kisi, dll (opsional)">{{ old('deskripsi', $ujian->deskripsi) }}</textarea>
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
                                    <i class="fas fa-file-pdf me-2"></i>Berkas Ujian Tengah Semester
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-warning">
                                    <i class="fas fa-clipboard-check me-2"></i>
                                    <strong>Kualitas Soal:</strong> Pastikan soal UTS telah melalui proses verifikasi dan sesuai dengan kurikulum.
                                </div>
                                
                                <!-- Current Files Info -->
                                <div class="current-files mb-4">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="file-info-card">
                                                <h6 class="mb-2">Berkas Soal Saat Ini:</h6>
                                                @if($ujian->berkas_soal)
                                                <div class="d-flex align-items-center justify-content-between p-3 border rounded bg-light">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-file-pdf text-danger me-2 fs-5"></i>
                                                        <div>
                                                            <div class="fw-bold small">{{ basename($ujian->berkas_soal) }}</div>
                                                            <div class="text-muted smaller">Diunggah: {{ $ujian->created_at->format('d/m/Y H:i') }}</div>
                                                        </div>
                                                    </div>
                                                    
                                                        <i class="fas fa-eye me-1"></i>Lihat
                                                    </a>
                                                </div>
                                                @else
                                                <div class="alert alert-warning py-2">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                                    Belum ada berkas soal
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="file-info-card">
                                                <h6 class="mb-2">Berkas Kunci Jawaban Saat Ini:</h6>
                                                @if($ujian->berkas_kunci_jawaban)
                                                <div class="d-flex align-items-center justify-content-between p-3 border rounded bg-light">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-file-pdf text-danger me-2 fs-5"></i>
                                                        <div>
                                                            <div class="fw-bold small">{{ basename($ujian->berkas_kunci_jawaban) }}</div>
                                                            <div class="text-muted smaller">Diunggah: {{ $ujian->created_at->format('d/m/Y H:i') }}</div>
                                                        </div>
                                                    </div>
                                                    <a href="{{ Storage::url($ujian->berkas_kunci_jawaban) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye me-1"></i>Lihat
                                                    </a>
                                                </div>
                                                @else
                                                <div class="alert alert-warning py-2">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                                    Belum ada berkas kunci jawaban
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="berkas_soal" class="form-label">
                                                Ganti Berkas Soal UTS (PDF)
                                                @if(!$ujian->berkas_soal)
                                                <span class="text-danger">*</span>
                                                @endif
                                            </label>
                                            <input type="file" class="form-control @error('berkas_soal') is-invalid @enderror" 
                                                   id="berkas_soal" name="berkas_soal" 
                                                   accept=".pdf">
                                            @error('berkas_soal')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Kosongkan jika tidak ingin mengganti. Maksimal 10MB. Format PDF.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="berkas_kunci_jawaban" class="form-label">
                                                Ganti Berkas Kunci Jawaban (PDF)
                                                @if(!$ujian->berkas_kunci_jawaban)
                                                <span class="text-danger">*</span>
                                                @endif
                                            </label>
                                            <input type="file" class="form-control @error('berkas_kunci_jawaban') is-invalid @enderror" 
                                                   id="berkas_kunci_jawaban" name="berkas_kunci_jawaban" 
                                                   accept=".pdf">
                                            @error('berkas_kunci_jawaban')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Kosongkan jika tidak ingin mengganti. Maksimal 10MB. Format PDF.
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
                                                <span class="badge bg-warning ms-2">BARU</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div id="kunci-preview" class="file-preview-item d-none">
                                                <i class="fas fa-file-pdf text-danger me-2"></i>
                                                <span id="kunci-filename" class="small fw-bold"></span>
                                                <span class="badge bg-warning ms-2">BARU</span>
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
                                    <i class="fas fa-calendar-alt me-2"></i>
                                    <strong>Jadwal UTS:</strong> Ujian Tengah Semester biasanya berlangsung lebih lama dari ujian harian (2-3 jam).
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="waktu_mulai" class="form-label">
                                                Waktu Mulai <span class="text-danger">*</span>
                                            </label>
                                            <input type="datetime-local" class="form-control @error('waktu_mulai') is-invalid @enderror" 
                                                   id="waktu_mulai" name="waktu_mulai" 
                                                   value="{{ old('waktu_mulai', \Carbon\Carbon::parse($ujian->waktu_mulai)->format('Y-m-d\TH:i')) }}" required>
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
                                                   value="{{ old('waktu_selesai', \Carbon\Carbon::parse($ujian->waktu_selesai)->format('Y-m-d\TH:i')) }}" required>
                                            @error('waktu_selesai')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">
                                                Durasi: <span id="durasi-ujian" class="fw-bold">2 jam</span>
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
                                                   value="{{ old('batas_pengumpulan', $ujian->batas_pengumpulan ? \Carbon\Carbon::parse($ujian->batas_pengumpulan)->format('Y-m-d\TH:i') : '') }}">
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
                                    <i class="fas fa-list-alt me-2"></i>Instruksi dan Pengaturan UTS
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="instruksi" class="form-label">Instruksi Pengerjaan UTS</label>
                                    <textarea class="form-control @error('instruksi') is-invalid @enderror" 
                                              id="instruksi" name="instruksi" rows="5" 
                                              placeholder="Contoh instruksi UTS:
● UJIAN TENGAH SEMESTER - MATA PELAJARAN MATEMATIKA
● Waktu: 120 menit (2 jam)
● Jumlah soal: 30 butir pilihan ganda + 3 essay
● Bobot nilai: Pilihan ganda (70%), Essay (30%)
● Tulis nama, kelas, dan nomor peserta di lembar jawaban
● Dilarang bekerjasama, menyontek, atau menggunakan alat bantu tidak sah
● Periksa kembali jawaban sebelum dikumpulkan
● Teliti dalam membaca soal dan menjawab">{{ old('instruksi', $ujian->instruksi) }}</textarea>
                                    @error('instruksi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        Berikan instruksi yang jelas dan lengkap untuk Ujian Tengah Semester
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="confirm_kurikulum" {{ old('confirm_kurikulum') ? 'checked' : '' }} required>
                                                <label class="form-check-label" for="confirm_kurikulum">
                                                    Soal telah disusun sesuai dengan kurikulum dan mencakup materi tengah semester
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="confirm_verifikasi" {{ old('confirm_verifikasi') ? 'checked' : '' }} required>
                                                <label class="form-check-label" for="confirm_verifikasi">
                                                    Soal dan kunci jawaban telah melalui proses verifikasi
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <a href="{{ route('guru.penilaian.uts.index') }}" class="btn btn-secondary me-2">
                                    <i class="fas fa-arrow-left me-1"></i>Kembali ke Daftar UTS
                                </a>
                                <a href="{{ route('guru.penilaian.uts.show', $ujian->id) }}" class="btn btn-outline-info">
                                    <i class="fas fa-eye me-1"></i>Lihat Detail
                                </a>
                            </div>
                            <div>
                                @if($ujian->status == 'draft')
                                <button type="submit" name="action" value="draft" class="btn btn-warning me-2">
                                    <i class="fas fa-save me-1"></i>Update Draft
                                </button>
                                <button type="submit" name="action" value="publish" class="btn btn-success" id="btn-publish">
                                    <i class="fas fa-paper-plane me-1"></i>Update & Publish
                                </button>
                                @else
                                <button type="submit" name="action" value="update" class="btn btn-primary" id="btn-update">
                                    <i class="fas fa-save me-1"></i>Update UTS
                                </button>
                                @endif
                            </div>
                        </div>
                    </form>
                    
                    <!-- Delete Form -->
                    @if($ujian->status == 'draft')
                    <div class="mt-4 pt-3 border-top">
                        <form action="{{ route('guru.penilaian.uts.destroy', $ujian->id) }}" method="POST" class="d-inline" id="deleteForm">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="confirmDelete()">
                                <i class="fas fa-trash me-1"></i>Hapus Ujian
                            </button>
                        </form>
                    </div>
                    @endif
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
    
    .current-files .file-info-card {
        margin-bottom: 1rem;
    }
    
    .smaller {
        font-size: 0.75rem;
    }
    
    .file-info-card h6 {
        color: #495057;
        font-weight: 600;
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
        const btnUpdate = document.getElementById('btn-update');
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
                
                // Show warning if duration is not appropriate for UTS
                if (diffHours < 1) {
                    showWarning('Durasi UTS terlalu pendek. UTS biasanya berlangsung 2-3 jam.');
                    durasiWarning.textContent = '⚠ Durasi kurang ideal';
                    durasiWarning.classList.remove('d-none');
                } else if (diffHours > 4) {
                    showWarning('Durasi UTS terlalu panjang. Pastikan durasi sesuai dengan standar akademik.');
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
                // Set batas pengumpulan 30 minutes after waktu selesai for UTS
                const selesaiTime = new Date(this.value);
                selesaiTime.setMinutes(selesaiTime.getMinutes() + 30);
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
        const form = document.getElementById('formEditUjianUTS');
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
            const berkasKunci = document.getElementById('berkas_kunci_jawaban').files[0];
            
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
                alert('Harap konfirmasi semua persyaratan UTS!');
                return false;
            }
        });
        
        // Publish button enhancement for UTS
        if (btnPublish) {
            btnPublish.addEventListener('click', function(e) {
                const durasi = new Date(waktuSelesaiInput.value) - new Date(waktuMulaiInput.value);
                const durasiJam = durasi / (1000 * 60 * 60);
                
                if (durasiJam < 1) {
                    if (!confirm('Durasi UTS kurang dari 1 jam. UTS biasanya membutuhkan waktu lebih lama. Apakah Anda yakin ingin melanjutkan?')) {
                        e.preventDefault();
                        return false;
                    }
                }
                
                if (!confirm('Ujian Tengah Semester akan dipublish dan dapat diakses siswa. Pastikan semua data sudah benar. Lanjutkan?')) {
                    e.preventDefault();
                    return false;
                }
            });
        }
        
        // Update button enhancement for published UTS
        if (btnUpdate) {
            btnUpdate.addEventListener('click', function(e) {
                if (!confirm('PERINGATAN: Mengubah UTS yang sudah dipublish dapat mempengaruhi siswa yang sedang/sudah mengerjakan. Pastikan perubahan benar-benar diperlukan. Lanjutkan?')) {
                    e.preventDefault();
                    return false;
                }
            });
        }
    });
    
    // Delete confirmation
    function confirmDelete() {
        if (confirm('Apakah Anda yakin ingin menghapus Ujian Tengah Semester ini? Tindakan ini tidak dapat dibatalkan!')) {
            document.getElementById('deleteForm').submit();
        }
    }
</script>
@endpush