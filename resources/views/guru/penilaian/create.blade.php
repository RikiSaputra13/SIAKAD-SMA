@extends('layouts.app')

@section('title', 'Tambah Penilaian')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-plus-circle me-2"></i>Tambah Penilaian Siswa
                        </h5>
                    </div>

                    <div class="card-body">
                        {{-- Alert Success/Error --}}
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Terjadi kesalahan:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('guru.penilaian.store') }}" method="POST" id="penilaian-form">
                            @csrf

                            <div class="row">
                                {{-- Kelas --}}
                                <div class="col-md-4 mb-3">
                                    <label for="kelas_id" class="form-label">Kelas <span class="text-danger">*</span></label>
                                    <select name="kelas_id" id="kelas_id" class="form-select" required>
                                        <option value="">-- Pilih Kelas --</option>
                                        @foreach ($kelasOptions as $id => $namaKelas)
                                            <option value="{{ $id }}" {{ old('kelas_id') == $id ? 'selected' : '' }}>
                                                {{ $namaKelas }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('kelas_id')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Mata Pelajaran --}}
                                <div class="col-md-4 mb-3">
                                    <label for="mata_pelajaran" class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
                                    <select name="mata_pelajaran" id="mata_pelajaran" class="form-select" required>
                                        <option value="">-- Pilih Mapel --</option>
                                        @foreach ($mapelOptions as $mapel)
                                            <option value="{{ $mapel }}" {{ old('mata_pelajaran') == $mapel ? 'selected' : '' }}>
                                                {{ $mapel }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('mata_pelajaran')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Semester --}}
                                <div class="col-md-2 mb-3">
                                    <label for="semester" class="form-label">Semester <span class="text-danger">*</span></label>
                                    <select name="semester" id="semester" class="form-select" required>
                                        <option value="">-- Pilih --</option>
                                        @foreach ($semesterOptions as $key => $value)
                                            <option value="{{ $key }}" {{ old('semester') == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('semester')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Tahun Ajaran --}}
                                <div class="col-md-2 mb-3">
                                    <label for="tahun_ajaran" class="form-label">Tahun Ajaran <span class="text-danger">*</span></label>
                                    <input type="number" name="tahun_ajaran" id="tahun_ajaran" class="form-control"
                                        value="{{ old('tahun_ajaran', $tahunAjaran) }}" min="2020" max="2030" required>
                                    @error('tahun_ajaran')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Section Input Nilai Siswa --}}
                            <div id="siswa-section" class="mt-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0">
                                        <i class="fas fa-user-graduate me-2"></i>Input Nilai Siswa
                                    </h6>
                                    <div id="siswa-count" class="badge bg-info" style="display: none;">
                                        <span id="count-number">0</span> siswa ditemukan
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped align-middle">
                                        <thead class="bg-light">
                                            <tr>
                                                <th width="5%" class="text-center">No</th>
                                                <th width="25%">Nama Siswa</th>
                                                <th width="15%" class="text-center">UH</th>
                                                <th width="15%" class="text-center">UTS</th>
                                                <th width="15%" class="text-center">UAS</th>
                                                <th width="15%" class="text-center">Tugas</th>
                                                <th width="10%" class="text-center">Rata-rata</th>
                                            </tr>
                                        </thead>
                                        <tbody id="table-siswa-body">
                                            <tr class="text-center text-muted">
                                                <td colspan="7" class="py-4">
                                                    <i class="fas fa-info-circle me-2"></i>
                                                    Silakan pilih kelas terlebih dahulu untuk menampilkan daftar siswa
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                {{-- Alert untuk tidak ada siswa --}}
                                <div id="no-siswa-alert" class="alert alert-warning text-center" style="display: none;">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Tidak ada siswa di kelas yang dipilih
                                </div>

                                {{-- Error loading --}}
                                <div id="error-alert" class="alert alert-danger text-center" style="display: none;">
                                    <i class="fas fa-times-circle me-2"></i>
                                    <span id="error-message">Gagal memuat data siswa</span>
                                </div>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="mt-4 d-flex justify-content-between align-items-center">
                                <a href="{{ route('guru.penilaian.list') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i>Kembali
                                </a>
                                
                                <div>
                                    <button type="button" id="reset-btn" class="btn btn-outline-secondary me-2">
                                        <i class="fas fa-redo me-1"></i>Reset
                                    </button>
                                    <button type="submit" id="submit-btn" class="btn btn-success" disabled>
                                        <i class="fas fa-save me-1"></i>Simpan Penilaian
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
        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
        }
        .table th {
            font-weight: 600;
            font-size: 0.875rem;
        }
        .table td {
            vertical-align: middle;
        }
        .form-control-sm {
            min-height: calc(1.5em + 0.5rem + 2px);
        }
        .nilai-input {
            text-align: center;
            font-weight: 500;
        }
        .rata-rata {
            font-weight: 600;
            color: #198754;
        }
        #siswa-count {
            font-size: 0.875rem;
        }
    </style>
@endpush
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const kelasSelect = document.getElementById('kelas_id');
            const tbody = document.getElementById('table-siswa-body');
            const siswaCount = document.getElementById('siswa-count');
            const countNumber = document.getElementById('count-number');
            const noSiswaAlert = document.getElementById('no-siswa-alert');
            const errorAlert = document.getElementById('error-alert');
            const errorMessage = document.getElementById('error-message');
            const submitBtn = document.getElementById('submit-btn');
            const resetBtn = document.getElementById('reset-btn');
            const form = document.getElementById('penilaian-form');

            console.log('JavaScript loaded successfully');

            // Fungsi untuk menghitung rata-rata
            function hitungRataRata(uh, uts, uas, tugas) {
                const nilaiUH = parseFloat(uh) || 0;
                const nilaiUTS = parseFloat(uts) || 0;
                const nilaiUAS = parseFloat(uas) || 0;
                const nilaiTugas = parseFloat(tugas) || 0;
                
                return ((nilaiUH + nilaiUTS + nilaiUAS + nilaiTugas) / 4).toFixed(1);
            }

            // Fungsi untuk update rata-rata real-time
            function updateRataRata(input) {
                const row = input.closest('tr');
                const uh = row.querySelector('input[name*="nilai_uh"]').value;
                const uts = row.querySelector('input[name*="nilai_uts"]').value;
                const uas = row.querySelector('input[name*="nilai_uas"]').value;
                const tugas = row.querySelector('input[name*="nilai_tugas"]').value;
                
                const rataRata = hitungRataRata(uh, uts, uas, tugas);
                row.querySelector('.rata-rata').textContent = rataRata;
            }

            // Event listener untuk perubahan nilai input
            document.addEventListener('input', function(e) {
                if (e.target.name.includes('nilai_')) {
                    updateRataRata(e.target);
                }
            });

            // Event listener untuk perubahan kelas
            kelasSelect.addEventListener('change', function() {
                const kelasId = this.value;
                
                console.log('Kelas changed:', kelasId);
                
                // Reset state
                submitBtn.disabled = true;
                siswaCount.style.display = 'none';
                noSiswaAlert.style.display = 'none';
                errorAlert.style.display = 'none';

                if (!kelasId) {
                    tbody.innerHTML = `
                        <tr class="text-center text-muted">
                            <td colspan="7" class="py-4">
                                <i class="fas fa-info-circle me-2"></i>
                                Silakan pilih kelas terlebih dahulu untuk menampilkan daftar siswa
                            </td>
                        </tr>
                    `;
                    return;
                }

                // Show loading
                tbody.innerHTML = `
                    <tr class="text-center">
                        <td colspan="7" class="py-4">
                            <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                            Memuat data siswa...
                        </td>
                    </tr>
                `;

                // Fetch data siswa - PERBAIKAN: Gunakan route name yang benar
                const url = `{{ route('guru.penilaian.getSiswa', '') }}/${kelasId}`;
                console.log('Fetching URL:', url);

                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Data received:', data);
                    
                    // Hide all alerts
                    noSiswaAlert.style.display = 'none';
                    errorAlert.style.display = 'none';

                    if (data.error) {
                        throw new Error(data.error);
                    }

                    if (!data.length) {
                        tbody.innerHTML = '';
                        noSiswaAlert.style.display = 'block';
                        siswaCount.style.display = 'none';
                        console.log('No students found');
                        return;
                    }

                    // Populate table dengan siswa
                    tbody.innerHTML = '';
                    data.forEach((siswa, index) => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td class="text-center">${index + 1}</td>
                            <td>
                                ${siswa.nama}
                                <input type="hidden" name="siswa_id[]" value="${siswa.id}">
                            </td>
                            <td>
                                <input type="number" name="nilai_uh[${siswa.id}]" 
                                       class="form-control form-control-sm nilai-input" 
                                       min="0" max="100" placeholder="0-100" required
                                       oninput="validateNilai(this)">
                            </td>
                            <td>
                                <input type="number" name="nilai_uts[${siswa.id}]" 
                                       class="form-control form-control-sm nilai-input" 
                                       min="0" max="100" placeholder="0-100" required
                                       oninput="validateNilai(this)">
                            </td>
                            <td>
                                <input type="number" name="nilai_uas[${siswa.id}]" 
                                       class="form-control form-control-sm nilai-input" 
                                       min="0" max="100" placeholder="0-100" required
                                       oninput="validateNilai(this)">
                            </td>
                            <td>
                                <input type="number" name="nilai_tugas[${siswa.id}]" 
                                       class="form-control form-control-sm nilai-input" 
                                       min="0" max="100" placeholder="0-100" required
                                       oninput="validateNilai(this)">
                            </td>
                            <td class="text-center rata-rata">0.0</td>
                        `;
                        tbody.appendChild(row);
                    });

                    // Update count
                    countNumber.textContent = data.length;
                    siswaCount.style.display = 'inline-block';
                    submitBtn.disabled = false;
                    console.log('Students loaded successfully:', data.length);
                })
                .catch(error => {
                    console.error('Fetch Error:', error);
                    tbody.innerHTML = '';
                    errorMessage.textContent = error.message || 'Gagal memuat data siswa';
                    errorAlert.style.display = 'block';
                    siswaCount.style.display = 'none';
                });
            });

            // Validasi nilai (0-100)
            window.validateNilai = function(input) {
                const value = parseInt(input.value);
                if (value < 0) input.value = 0;
                if (value > 100) input.value = 100;
                updateRataRata(input);
            };

            // Reset button
            resetBtn.addEventListener('click', function() {
                form.reset();
                tbody.innerHTML = `
                    <tr class="text-center text-muted">
                        <td colspan="7" class="py-4">
                            <i class="fas fa-info-circle me-2"></i>
                            Silakan pilih kelas terlebih dahulu untuk menampilkan daftar siswa
                        </td>
                    </tr>
                `;
                submitBtn.disabled = true;
                siswaCount.style.display = 'none';
                noSiswaAlert.style.display = 'none';
                errorAlert.style.display = 'none';
            });

            // Form validation sebelum submit
            form.addEventListener('submit', function(e) {
                const kelasId = document.getElementById('kelas_id').value;
                const mapel = document.getElementById('mata_pelajaran').value;
                const semester = document.getElementById('semester').value;
                
                if (!kelasId || !mapel || !semester) {
                    e.preventDefault();
                    alert('Harap lengkapi semua field yang wajib diisi!');
                    return;
                }

                // Validasi semua input nilai
                const nilaiInputs = document.querySelectorAll('input[name*="nilai_"]');
                let semuaValid = true;
                
                nilaiInputs.forEach(input => {
                    const value = parseInt(input.value);
                    if (isNaN(value) || value < 0 || value > 100) {
                        input.classList.add('is-invalid');
                        semuaValid = false;
                    } else {
                        input.classList.remove('is-invalid');
                    }
                });

                if (!semuaValid) {
                    e.preventDefault();
                    alert('Harap periksa kembali nilai-nilai yang diinput (harus antara 0-100)!');
                }
            });
        });
    </script>
@endpush