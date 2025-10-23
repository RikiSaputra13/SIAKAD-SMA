@extends('layouts.app')

@section('title', 'Edit Penilaian')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-warning text-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2"></i>Edit Penilaian Siswa
                    </h5>
                </div>
                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <div>
                                    <h6 class="mb-1">Terjadi kesalahan:</h6>
                                    <ul class="mb-0 ps-3 small">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Student Information Card -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-light border-0 py-3">
                            <h6 class="mb-0 text-dark">
                                <i class="fas fa-user-graduate me-2"></i>Informasi Siswa
                            </h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold text-muted small">NIS</label>
                                        <p class="fw-bold text-dark mb-0">{{ $penilaian->siswa->nis }}</p>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold text-muted small">Nama Siswa</label>
                                        <p class="fw-bold text-dark mb-0">{{ $penilaian->siswa->nama }}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold text-muted small">Kelas</label>
                                        <p class="fw-bold text-dark mb-0">{{ $penilaian->kelas->nama_kelas }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Form -->
                    <form method="POST" action="{{ route('guru.penilaian.update', $penilaian->id) }}">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="siswa_id" value="{{ $penilaian->siswa_id }}">
                        <input type="hidden" name="kelas_id" value="{{ $penilaian->kelas_id }}">
                        
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-light border-0 py-3">
                                <h6 class="mb-0 text-dark">
                                    <i class="fas fa-book me-2"></i>Data Penilaian
                                </h6>
                            </div>
                            <div class="card-body p-4">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="mata_pelajaran" class="form-label fw-semibold">Mata Pelajaran</label>
                                            <select name="mata_pelajaran" id="mata_pelajaran" 
                                                    class="form-select border-secondary-subtle @error('mata_pelajaran') is-invalid @enderror" required>
                                                <option value="">Pilih Mata Pelajaran</option>
                                                @foreach($mapelOptions as $mapel)
                                                    <option value="{{ $mapel }}" 
                                                        {{ old('mata_pelajaran', $penilaian->mata_pelajaran) == $mapel ? 'selected' : '' }}>
                                                        {{ $mapel }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('mata_pelajaran')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="semester" class="form-label fw-semibold">Semester</label>
                                            <select name="semester" id="semester" 
                                                    class="form-select border-secondary-subtle @error('semester') is-invalid @enderror" required>
                                                <option value="">Pilih Semester</option>
                                                <option value="1" {{ old('semester', $penilaian->semester) == '1' ? 'selected' : '' }}>1</option>
                                                <option value="2" {{ old('semester', $penilaian->semester) == '2' ? 'selected' : '' }}>2</option>
                                            </select>
                                            @error('semester')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="tahun_ajaran" class="form-label fw-semibold">Tahun Ajaran</label>
                                            <input type="number" name="tahun_ajaran" id="tahun_ajaran" 
                                                   class="form-control border-secondary-subtle @error('tahun_ajaran') is-invalid @enderror"
                                                   value="{{ old('tahun_ajaran', $penilaian->tahun_ajaran) }}" 
                                                   min="2020" max="2030" required>
                                            @error('tahun_ajaran')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Nilai Components -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-light border-0 py-3">
                                <h6 class="mb-0 text-dark">
                                    <i class="fas fa-chart-bar me-2"></i>Komponen Nilai
                                </h6>
                            </div>
                            <div class="card-body p-4">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="nilai_uh" class="form-label fw-semibold">
                                                Nilai UH (Ulangan Harian)
                                                <span class="text-muted small">(0-100)</span>
                                            </label>
                                            <input type="number" name="nilai_uh" id="nilai_uh" 
                                                   class="form-control border-secondary-subtle @error('nilai_uh') is-invalid @enderror"
                                                   value="{{ old('nilai_uh', $penilaian->nilai_uh) }}" 
                                                   min="0" max="100" step="0.1" required>
                                            @error('nilai_uh')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="nilai_uts" class="form-label fw-semibold">
                                                Nilai UTS
                                                <span class="text-muted small">(0-100)</span>
                                            </label>
                                            <input type="number" name="nilai_uts" id="nilai_uts" 
                                                   class="form-control border-secondary-subtle @error('nilai_uts') is-invalid @enderror"
                                                   value="{{ old('nilai_uts', $penilaian->nilai_uts) }}" 
                                                   min="0" max="100" step="0.1" required>
                                            @error('nilai_uts')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="nilai_uas" class="form-label fw-semibold">
                                                Nilai UAS
                                                <span class="text-muted small">(0-100)</span>
                                            </label>
                                            <input type="number" name="nilai_uas" id="nilai_uas" 
                                                   class="form-control border-secondary-subtle @error('nilai_uas') is-invalid @enderror"
                                                   value="{{ old('nilai_uas', $penilaian->nilai_uas) }}" 
                                                   min="0" max="100" step="0.1" required>
                                            @error('nilai_uas')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="nilai_tugas" class="form-label fw-semibold">
                                                Nilai Tugas
                                                <span class="text-muted small">(0-100)</span>
                                            </label>
                                            <input type="number" name="nilai_tugas" id="nilai_tugas" 
                                                   class="form-control border-secondary-subtle @error('nilai_tugas') is-invalid @enderror"
                                                   value="{{ old('nilai_tugas', $penilaian->nilai_tugas) }}" 
                                                   min="0" max="100" step="0.1" required>
                                            @error('nilai_tugas')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Bobot Nilai Information -->
                                <div class="alert alert-info border-0 shadow-sm mt-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <div>
                                            <h6 class="mb-1 fw-semibold">Informasi Bobot Nilai:</h6>
                                            <p class="mb-0 small">
                                                Nilai Akhir = (UH × 25%) + (UTS × 25%) + (UAS × 30%) + (Tugas × 20%)
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Preview Nilai Akhir -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-light border-0 py-3">
                                <h6 class="mb-0 text-dark">
                                    <i class="fas fa-calculator me-2"></i>Preview Nilai Akhir
                                </h6>
                            </div>
                            <div class="card-body p-4">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Nilai Akhir</label>
                                            <div class="d-flex align-items-center">
                                                <input type="text" id="nilai_akhir_preview" 
                                                       class="form-control border-secondary-subtle bg-light fw-bold fs-5" 
                                                       value="{{ number_format($penilaian->nilai_akhir, 2) }}" 
                                                       readonly style="max-width: 150px;">
                                                <span class="ms-3 fw-semibold" id="predikat_preview">
                                                    {{ $penilaian->predikat }}
                                                </span>
                                                <span class="ms-2 badge" id="predikat_badge">
                                                    @php
                                                        $badgeClass = match($penilaian->predikat) {
                                                            'A' => 'bg-success',
                                                            'B' => 'bg-info',
                                                            'C' => 'bg-warning',
                                                            'D' => 'bg-danger',
                                                            default => 'bg-secondary'
                                                        };
                                                    @endphp
                                                    <span class="{{ $badgeClass }}">{{ $penilaian->predikat }}</span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Keterangan Predikat</label>
                                            <div id="keterangan_predikat" class="small text-muted">
                                                @php
                                                    $keterangan = match($penilaian->predikat) {
                                                        'A' => 'Sangat Baik (85-100)',
                                                        'B' => 'Baik (70-84)',
                                                        'C' => 'Cukup (60-69)',
                                                        'D' => 'Kurang (0-59)',
                                                        default => 'Tidak diketahui'
                                                    };
                                                @endphp
                                                {{ $keterangan }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <a href="{{ route('guru.penilaian.list') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Kembali
                                </a>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="reset" class="btn btn-outline-danger">
                                    <i class="fas fa-undo me-2"></i>Reset
                                </button>
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-save me-2"></i>Update Penilaian
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
        border-radius: 0.75rem;
    }
    
    .border-secondary-subtle {
        border-color: #e9ecef !important;
    }
    
    .shadow-sm {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
    }
    
    .form-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
    }
    
    .bg-light {
        background-color: #f8f9fa !important;
    }
    
    #nilai_akhir_preview {
        color: #198754;
        font-weight: bold;
    }
    
    .badge {
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const nilaiInputs = ['nilai_uh', 'nilai_uts', 'nilai_uas', 'nilai_tugas'];
        const bobot = {
            'nilai_uh': 0.25,
            'nilai_uts': 0.25,
            'nilai_uas': 0.30,
            'nilai_tugas': 0.20
        };

        function calculateFinalGrade() {
            let total = 0;
            let isValid = true;

            nilaiInputs.forEach(inputId => {
                const input = document.getElementById(inputId);
                const value = parseFloat(input.value) || 0;
                
                if (value < 0 || value > 100) {
                    isValid = false;
                    return;
                }
                
                total += value * bobot[inputId];
            });

            if (!isValid) return;

            const nilaiAkhir = Math.min(100, Math.max(0, total));
            const predikat = getPredikat(nilaiAkhir);
            
            updatePreview(nilaiAkhir, predikat);
        }

        function getPredikat(nilai) {
            if (nilai >= 85) return 'A';
            if (nilai >= 70) return 'B';
            if (nilai >= 60) return 'C';
            return 'D';
        }

        function getKeteranganPredikat(predikat) {
            const keterangan = {
                'A': 'Sangat Baik (85-100)',
                'B': 'Baik (70-84)',
                'C': 'Cukup (60-69)',
                'D': 'Kurang (0-59)'
            };
            return keterangan[predikat] || 'Tidak diketahui';
        }

        function getBadgeClass(predikat) {
            const classes = {
                'A': 'bg-success',
                'B': 'bg-info',
                'C': 'bg-warning',
                'D': 'bg-danger'
            };
            return classes[predikat] || 'bg-secondary';
        }

        function updatePreview(nilaiAkhir, predikat) {
            const previewInput = document.getElementById('nilai_akhir_preview');
            const predikatSpan = document.getElementById('predikat_preview');
            const predikatBadge = document.getElementById('predikat_badge');
            const keteranganDiv = document.getElementById('keterangan_predikat');

            previewInput.value = nilaiAkhir.toFixed(2);
            predikatSpan.textContent = predikat;
            
            // Update badge
            const badgeClass = getBadgeClass(predikat);
            predikatBadge.innerHTML = `<span class="badge ${badgeClass}">${predikat}</span>`;
            
            // Update keterangan
            keteranganDiv.textContent = getKeteranganPredikat(predikat);
        }

        // Add event listeners to all nilai inputs
        nilaiInputs.forEach(inputId => {
            const input = document.getElementById(inputId);
            if (input) {
                input.addEventListener('input', calculateFinalGrade);
                input.addEventListener('change', calculateFinalGrade);
            }
        });

        // Initial calculation
        calculateFinalGrade();

        // Form validation
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            let isValid = true;
            
            nilaiInputs.forEach(inputId => {
                const input = document.getElementById(inputId);
                const value = parseFloat(input.value);
                
                if (isNaN(value) || value < 0 || value > 100) {
                    isValid = false;
                    input.classList.add('is-invalid');
                } else {
                    input.classList.remove('is-invalid');
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('Mohon periksa kembali nilai yang dimasukkan. Nilai harus antara 0-100.');
            }
        });
    });
</script>
@endpush