@extends('layouts.app')

@section('title', 'Detail Pengumpulan Ujian Harian')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-list-check me-2"></i>Detail Pengumpulan - {{ $ujian->judul_ujian }}
                            </h5>
                            <a href="{{ route('guru.penilaian.uh.index') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left me-1"></i>Kembali
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Exam Info -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <th width="120">Kelas</th>
                                        <td>{{ $ujian->kelas->nama_kelas }}</td>
                                    </tr>
                                    <tr>
                                        <th>Mata Pelajaran</th>
                                        <td>{{ $ujian->mata_pelajaran }}</td>
                                    </tr>
                                    <tr>
                                        <th>Total Nilai</th>
                                        <td>{{ $ujian->total_nilai }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <th width="120">Waktu Mulai</th>
                                        <td>{{ $ujian->waktu_mulai->format('d M Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Waktu Selesai</th>
                                        <td>{{ $ujian->waktu_selesai->format('d M Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            <span
                                                class="badge bg-{{ $ujian->status == 'published' ? 'success' : ($ujian->status == 'completed' ? 'info' : 'warning') }}">
                                                {{ ucfirst($ujian->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- Statistics Cards -->
                        <div class="row mb-4">
                            <div class="col-xl-2 col-md-4 mb-3">
                                <div class="card border-left-primary shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                    Total Siswa</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                    {{ $statistikPengumpulan['total_siswa'] }}</div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-users fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-2 col-md-4 mb-3">
                                <div class="card border-left-success shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                    Sudah Kumpul</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                    {{ $statistikPengumpulan['sudah_dikumpulkan'] }}</div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-check fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-2 col-md-4 mb-3">
                                <div class="card border-left-warning shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                    Belum Kumpul</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                    {{ $statistikPengumpulan['belum_dikumpulkan'] }}</div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-clock fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-2 col-md-4 mb-3">
                                <div class="card border-left-info shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                    Sudah Dinilai</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                    {{ $statistikPengumpulan['sudah_dinilai'] }}</div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-star fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-2 col-md-4 mb-3">
                                <div class="card border-left-secondary shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                                    Belum Dinilai</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                    {{ $statistikPengumpulan['belum_dinilai'] }}</div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-edit fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submissions Table -->
                        <div class="card">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">
                                    <i class="fas fa-file-upload me-2"></i>Daftar Pengumpulan Siswa
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-striped">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Siswa</th>
                                                <th>NIS</th>
                                                <th>Berkas Jawaban</th>
                                                <th>Waktu Pengumpulan</th>
                                                <th>Catatan Siswa</th>
                                                <th>Nilai</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($semuaSiswa as $siswa)
                                                @php
                                                    $pengumpulan = $pengumpulanMap->get($siswa->id);
                                                @endphp
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $siswa->nama }}</td>
                                                    <td>{{ $siswa->nis }}</td>
                                                    <td class="text-center">
                                                        @if ($pengumpulan && $pengumpulan->berkas_jawaban)
                                                            <div class="btn-group btn-group-sm">
                                                                <a href="{{ route('guru.penilaian.uh.show.jawaban', ['ujian' => $ujian->id, 'pengumpulan' => $pengumpulan->id]) }}"
                                                                    class="btn btn-outline-primary" target="_blank"
                                                                    title="Lihat PDF">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                                <a href="{{ route('guru.penilaian.uh.download.jawaban', ['ujian' => $ujian->id, 'pengumpulan' => $pengumpulan->id]) }}"
                                                                    class="btn btn-outline-success" title="Download">
                                                                    <i class="fas fa-download"></i>
                                                                </a>
                                                            </div>
                                                        @else
                                                            <span class="badge bg-warning">Belum mengumpulkan</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ $pengumpulan && $pengumpulan->waktu_pengumpulan ? $pengumpulan->waktu_pengumpulan->format('d M Y H:i') : '-' }}
                                                    </td>
                                                    <td>
                                                        {{ $pengumpulan && $pengumpulan->catatan_siswa ? Str::limit($pengumpulan->catatan_siswa, 50) : '-' }}
                                                    </td>
                                                    <td class="text-center">
                                                        @if ($pengumpulan && $pengumpulan->nilai !== null)
                                                            <strong>{{ $pengumpulan->nilai }}</strong>/{{ $ujian->total_nilai }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if ($pengumpulan)
                                                            <span
                                                                class="badge bg-{{ $pengumpulan->status == 'dinilai' ? 'success' : ($pengumpulan->status == 'dikumpulkan' ? 'warning' : 'secondary') }}">
                                                                {{ ucfirst(str_replace('_', ' ', $pengumpulan->status)) }}
                                                            </span>
                                                        @else
                                                            <span class="badge bg-danger">Belum mengumpulkan</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($pengumpulan && $pengumpulan->berkas_jawaban)
                                                            <button type="button" class="btn btn-sm btn-primary"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modalNilai{{ $pengumpulan->id }}"
                                                                title="Beri Nilai">
                                                                <i class="fas fa-edit"></i> Nilai
                                                            </button>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                </tr>

                                                <!-- Modal Penilaian -->
                                                @if ($pengumpulan && $pengumpulan->berkas_jawaban)
                                                    <div class="modal fade" id="modalNilai{{ $pengumpulan->id }}"
                                                        tabindex="-1">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Beri Nilai -
                                                                        {{ $siswa->nama }}</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <form
                                                                    action="{{ route('guru.penilaian.uh.update.nilai', ['ujian' => $ujian->id, 'pengumpulan' => $pengumpulan->id]) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    <div class="modal-body">
                                                                        <div class="mb-3">
                                                                            <label for="nilai{{ $pengumpulan->id }}"
                                                                                class="form-label">Nilai (0 -
                                                                                {{ $ujian->total_nilai }})</label>
                                                                            <input type="number" class="form-control"
                                                                                id="nilai{{ $pengumpulan->id }}"
                                                                                name="nilai"
                                                                                value="{{ $pengumpulan->nilai ?? 0 }}"
                                                                                min="0"
                                                                                max="{{ $ujian->total_nilai }}"
                                                                                step="0.1" required>
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <label
                                                                                for="catatan_guru{{ $pengumpulan->id }}"
                                                                                class="form-label">Catatan Guru</label>
                                                                            <textarea class="form-control" id="catatan_guru{{ $pengumpulan->id }}" name="catatan_guru" rows="3">{{ $pengumpulan->catatan_guru }}</textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-bs-dismiss="modal">Batal</button>
                                                                        <button type="submit"
                                                                            class="btn btn-primary">Simpan Nilai</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .table th {
            border-top: none;
            font-weight: 600;
        }

        .badge {
            font-size: 0.75em;
            padding: 0.4em 0.6em;
        }

        .btn-group-sm>.btn {
            padding: 0.25rem 0.5rem;
        }
    </style>
@endpush
