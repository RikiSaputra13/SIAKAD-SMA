@extends('layouts.app')

@section('title', 'Daftar Absensi')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Daftar Absensi</h5>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="mb-3 d-flex gap-2">
                <button class="btn btn-primary" id="btnLihatRekap">Rekap Absensi</button>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th> 
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Keterangan Izin</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($absensis as $index => $absensi)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $absensi->siswa->nis }}</td>
                            <td>{{ $absensi->siswa->nama }}</td>
                            <td>{{ $absensi->siswa->kelas->nama_kelas ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($absensi->tanggal)->format('d-m-Y') }}</td>
                            <td>
                                @php
                                    $status = $absensi->status;
                                    $badge = match($status) {
                                        'Hadir' => 'success',
                                        'Sakit' => 'warning',
                                        'Izin' => 'info',
                                        'Alpha' => 'danger',
                                        default => 'secondary'
                                    };
                                @endphp
                                <span class="badge bg-{{ $badge }}">{{ $status }}</span>
                            </td>
                            <td>{{ $absensi->keterangan_izin ?? '-' }}</td>
                            <td>
                                <a href="{{ route('admin.absensi.edit', $absensi->id) }}" class="btn btn-warning">Edit</a>
                                <form action="{{ route('admin.absensi.destroy', $absensi->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus absensi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">Belum ada absensi.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Rekap -->
<div class="modal fade" id="rekapModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Rekap Absensi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-2 mb-3">
                    <div class="col-md-3">
                        <label>Dari:</label>
                        <input type="date" id="start_date" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label>Sampai:</label>
                        <input type="date" id="end_date" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label>Kelas:</label>
                        <select id="kelas_id" class="form-control">
                            <option value="">Semua Kelas</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button class="btn btn-info w-100" id="btnFilter">Tampilkan</button>
                    </div>
                </div>

                <div class="mb-3 d-flex gap-2">
                    <button class="btn btn-success" id="btnCetakPdf">Cetak PDF</button>
                    <button class="btn btn-primary" id="btnCetakExcel">Cetak Excel</button>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center" id="rekapTable">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>NIS</th>
                                <th>Nama Siswa</th>
                                <th>Hadir</th>
                                <th>Sakit</th>
                                <th>Izin</th>
                                <th>Alpha</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td colspan="8">Belum ada data.</td></tr> <!-- Diubah kembali ke 8 -->
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="3">Total</th> <!-- Diubah kembali ke 3 -->
                                <th id="totalHadir">0</th>
                                <th id="totalSakit">0</th>
                                <th id="totalIzin">0</th>
                                <th id="totalAlpha">0</th>
                                <th id="grandTotal">0</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const rekapModal = new bootstrap.Modal(document.getElementById('rekapModal'));
    const btnLihatRekap = document.getElementById('btnLihatRekap');
    const btnFilter = document.getElementById('btnFilter');
    const btnCetakPdf = document.getElementById('btnCetakPdf');
    const btnCetakExcel = document.getElementById('btnCetakExcel');
    const rekapTableBody = document.querySelector('#rekapTable tbody');

    function fetchRekap(start = '', end = '', kelas_id = '') {
        rekapTableBody.innerHTML = `<tr><td colspan="8">Memuat data...</td></tr>`; <!-- Diubah kembali ke 8 -->

        fetch(`{{ route('admin.absensi.rekap') }}?start_date=${start}&end_date=${end}&kelas_id=${kelas_id}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            credentials: 'same-origin'
        })
        .then(res => res.json())
        .then(res => {
            rekapTableBody.innerHTML = '';
            if(res.success){
                let i = 1;
                let stats = {Hadir:0, Sakit:0, Izin:0, Alpha:0, total:0};

                if(res.rekap.length === 0){
                    rekapTableBody.innerHTML = `<tr><td colspan="8">Tidak ada data.</td></tr>`; <!-- Diubah kembali ke 8 -->
                }

                res.rekap.forEach(item => {
                    const total = item.Hadir + item.Sakit + item.Izin + item.Alpha;
                    stats.Hadir += item.Hadir;
                    stats.Sakit += item.Sakit;
                    stats.Izin += item.Izin;
                    stats.Alpha += item.Alpha;
                    stats.total += total;

                    rekapTableBody.innerHTML += `
                        <tr>
                            <td>${i++}</td>
                            <td>${item.nis}</td>
                            <td>${item.nama_siswa}</td>
                            <!-- Kolom Kelas dihapus dari sini -->
                            <td>${item.Hadir}</td>
                            <td>${item.Sakit}</td>
                            <td>${item.Izin}</td>
                            <td>${item.Alpha}</td>
                            <td>${total}</td>
                        </tr>
                    `;
                });

                document.getElementById('totalHadir').textContent = stats.Hadir;
                document.getElementById('totalSakit').textContent = stats.Sakit;
                document.getElementById('totalIzin').textContent = stats.Izin;
                document.getElementById('totalAlpha').textContent = stats.Alpha;
                document.getElementById('grandTotal').textContent = stats.total;
            } else {
                rekapTableBody.innerHTML = `<tr><td colspan="8">Gagal memuat data.</td></tr>`; <!-- Diubah kembali ke 8 -->
            }
        })
        .catch(err => {
            console.error(err);
            rekapTableBody.innerHTML = `<tr><td colspan="8">Terjadi kesalahan server.</td></tr>`; <!-- Diubah kembali ke 8 -->
        });
    }

    btnLihatRekap.addEventListener('click', () => {
        rekapModal.show();
        fetchRekap();
    });

    btnFilter.addEventListener('click', () => {
        const start = document.getElementById('start_date').value;
        const end = document.getElementById('end_date').value;
        const kelas_id = document.getElementById('kelas_id').value;
        fetchRekap(start, end, kelas_id);
    });

    btnCetakPdf.addEventListener('click', () => {
        const start = document.getElementById('start_date').value;
        const end = document.getElementById('end_date').value;
        const kelas_id = document.getElementById('kelas_id').value;
        window.open(`{{ route('admin.absensi.rekap.cetak') }}?start_date=${start}&end_date=${end}&kelas_id=${kelas_id}`, '_blank');
    });

    btnCetakExcel.addEventListener('click', () => {
        const start = document.getElementById('start_date').value;
        const end = document.getElementById('end_date').value;
        const kelas_id = document.getElementById('kelas_id').value;
        window.location.href = `{{ route('admin.absensi.export-excel') }}?start_date=${start}&end_date=${end}&kelas_id=${kelas_id}`;
    });
});
</script>
@endpush