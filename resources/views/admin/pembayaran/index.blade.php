@extends('layouts.app')

@section('title', 'Daftar Pembayaran')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-credit-card me-2"></i>Daftar Pembayaran</h5>                
            </button>
        </div>
        
        <!-- Filter Section -->
        <div class="collapse" id="filterSection">
            <div class="p-3" style="background-color: #f8f9fa;">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Jenis Pembayaran</label>
                        <select class="form-select">
                            <option selected>Semua Jenis</option>
                            <option>SPP</option>
                            <option>Uang Bangunan</option>
                            <option>Uang Seragam</option>
                            <option>Uang Kegiatan</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select class="form-select">
                            <option selected>Semua Status</option>
                            <option>Lunas</option>
                            <option>Belum Lunas</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Metode Pembayaran</label>
                        <select class="form-select">
                            <option selected>Semua Metode</option>
                            <option>Tunai</option>
                            <option>Transfer Bank</option>
                            <option>E-Wallet</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Bulan</label>
                        <select class="form-select">
                            <option selected>Semua Bulan</option>
                            <option>Januari</option>
                            <option>Februari</option>
                            <option>Maret</option>
                            <option>April</option>
                            <option>Mei</option>
                            <option>Juni</option>
                            <option>Juli</option>
                            <option>Agustus</option>
                            <option>September</option>
                            <option>Oktober</option>
                            <option>November</option>
                            <option>Desember</option>
                        </select>
                    </div>
                    <div class="col-12 d-flex justify-content-end">
                        <button class="btn btn-outline-secondary">Reset</button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success d-flex align-items-center" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <div>{{ session('success') }}</div>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger d-flex align-items-center" role="alert">
                    <i class="bi bi-exclamation-circle-fill me-2"></i>
                    <div>{{ session('error') }}</div>
                </div>
            @endif

            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('admin.pembayaran.create') }}" class="btn btn-success">
                        <i class="bi bi-plus-circle me-1"></i> Tambah
                    </a>
                    <button class="btn btn-primary" id="btnLihatRekap">
                        <i class="bi bi-file-text me-1"></i>Rekap
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="text-center">No</th>
                            <th scope="col">NIS</th>
                            <th scope="col">Nama Siswa</th>
                            <th scope="col" class="text-center">Jenis Pembayaran</th>
                            <th scope="col" class="text-center">Total Tagihan</th>
                            <th scope="col" class="text-center">Jumlah Bayar</th>
                            <th scope="col" class="text-center">Sisa</th>
                            <th scope="col" class="text-center">Status</th>
                            <th scope="col" class="text-center">Metode</th>
                            <th scope="col" class="text-center">Tanggal Bayar</th>
                            <th scope="col" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pembayarans as $p)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $p->siswa->nis ?? '-' }}</td>
                                <td>{{ $p->siswa->nama ?? '-' }}</td>
                                <td class="text-center">{{ $p->jenis_pembayaran }}</td>
                                <td class="text-center">Rp {{ number_format($p->total_tagihan,0,',','.') }}</td>
                                <td class="text-center">Rp {{ number_format($p->jumlah_bayar,0,',','.') }}</td>
                                <td class="text-center">
                                    @if($p->status == 'Belum Lunas')
                                        Rp {{ number_format($p->total_tagihan - $p->jumlah_bayar,0,',','.') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ $p->status=='Lunas' ? 'bg-success' : 'bg-warning' }} status-badge">
                                        {{ $p->status }}
                                    </span>
                                </td>
                                <td class="text-center">{{ ucfirst(str_replace('_',' ',$p->metode_pembayaran)) }}</td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($p->tanggal_bayar)->format('d-m-Y') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.pembayaran.edit', $p->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('admin.pembayaran.destroy', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus pembayaran?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center py-4">
                                    <i class="bi bi-receipt text-muted" style="font-size: 2rem;"></i>
                                    <p class="mt-2 text-muted">Belum ada data pembayaran.</p>
                                </td>
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
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bi bi-graph-up me-2"></i>Rekap Pembayaran</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label class="form-label">Dari Tanggal:</label>
                        <input type="date" id="start_date" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Sampai Tanggal:</label>
                        <input type="date" id="end_date" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Jenis Pembayaran:</label>
                        <select id="jenis_pembayaran" class="form-select">
                            <option value="">Semua Jenis</option>
                            <option>SPP</option>
                            <option>Uang Seragam</option>
                            <option>Uang Kegiatan</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button class="btn btn-info w-100" id="btnFilterPembayaran">
                            <i class="bi bi-filter me-1"></i> Tampilkan
                        </button>
                    </div>
                </div>

                <div class="d-flex gap-2 mb-3">
                    <button class="btn btn-success" id="btnCetakPdfPembayaran">
                        <i class="bi bi-file-earmark-pdf me-1"></i> Cetak PDF
                    </button>
                    <button class="btn btn-primary" id="btnCetakExcelPembayaran">
                        <i class="bi bi-file-earmark-spreadsheet me-1"></i> Cetak Excel
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center" id="rekapTablePembayaran">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>NIS</th>
                                <th>Nama Siswa</th>
                                <th>Jenis Pembayaran</th>
                                <th>Total Tagihan</th>
                                <th>Jumlah Bayar</th>
                                <th>Sisa</th>
                                <th>Status</th>
                                <th>Metode</th>
                                <th>Tanggal Bayar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="10" class="text-center">Pilih filter dan klik Tampilkan untuk melihat rekap</td>
                            </tr>
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="4">Total</th>
                                <th id="totalTagihan">Rp 0</th>
                                <th id="totalBayar">Rp 0</th>
                                <th id="totalSisa">Rp 0</th>
                                <th colspan="3">-</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }
    
    @media (max-width: 768px) {
        .btn {
            margin-bottom: 5px;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const rekapModal = new bootstrap.Modal(document.getElementById('rekapModal'));
    const btnLihatRekap = document.getElementById('btnLihatRekap');
    const btnFilterPembayaran = document.getElementById('btnFilterPembayaran');
    const btnCetakPdfPembayaran = document.getElementById('btnCetakPdfPembayaran');
    const btnCetakExcelPembayaran = document.getElementById('btnCetakExcelPembayaran');
    const rekapTableBody = document.querySelector('#rekapTablePembayaran tbody');

    btnLihatRekap.addEventListener('click', () => {
        rekapModal.show();
    });

    btnFilterPembayaran.addEventListener('click', () => {
        const start = document.getElementById('start_date').value;
        const end = document.getElementById('end_date').value;
        const jenis = document.getElementById('jenis_pembayaran').value;
        
        // Tampilkan loading
        rekapTableBody.innerHTML = '<tr><td colspan="10" class="text-center">Memuat data...</td></tr>';
        
        // Ambil data dari server menggunakan AJAX
        fetch('{{ route("admin.pembayaran.rekap") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                start_date: start,
                end_date: end,
                jenis_pembayaran: jenis
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Kosongkan tabel
                rekapTableBody.innerHTML = '';
                
                // Isi tabel dengan data dari server
                let totalTagihan = 0;
                let totalBayar = 0;
                let totalSisa = 0;
                
                data.rekap.forEach((item, index) => {
                    const sisa = item.status === 'Belum Lunas' ? item.total_tagihan - item.jumlah_bayar : 0;
                    
                    rekapTableBody.innerHTML += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${item.nis || '-'}</td>
                            <td>${item.nama_siswa || '-'}</td>
                            <td>${item.jenis_pembayaran}</td>
                            <td>Rp ${formatRupiah(item.total_tagihan)}</td>
                            <td>Rp ${formatRupiah(item.jumlah_bayar)}</td>
                            <td>${item.status === 'Belum Lunas' ? 'Rp ' + formatRupiah(sisa) : '-'}</td>
                            <td>
                                <span class="badge ${item.status === 'Lunas' ? 'bg-success' : 'bg-warning'} status-badge">
                                    ${item.status}
                                </span>
                            </td>
                            <td>${item.metode_pembayaran ? ucfirst(item.metode_pembayaran.replace('_', ' ')) : '-'}</td>
                            <td>${formatTanggal(item.tanggal_bayar)}</td>
                        </tr>
                    `;
                    
                    totalTagihan += item.total_tagihan;
                    totalBayar += item.jumlah_bayar;
                    totalSisa += sisa;
                });
                
                // Update footer
                document.getElementById('totalTagihan').textContent = 'Rp ' + formatRupiah(totalTagihan);
                document.getElementById('totalBayar').textContent = 'Rp ' + formatRupiah(totalBayar);
                document.getElementById('totalSisa').textContent = 'Rp ' + formatRupiah(totalSisa);
            } else {
                rekapTableBody.innerHTML = '<tr><td colspan="10" class="text-center">Gagal memuat data</td></tr>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            rekapTableBody.innerHTML = '<tr><td colspan="10" class="text-center">Terjadi kesalahan</td></tr>';
        });
    });

    btnCetakPdfPembayaran.addEventListener('click', () => {
        const start = document.getElementById('start_date').value;
        const end = document.getElementById('end_date').value;
        const jenis = document.getElementById('jenis_pembayaran').value;
        
        // Redirect ke route cetak PDF dengan parameter filter
        window.open('{{ route("admin.pembayaran.cetak-pdf") }}?start_date=' + start + '&end_date=' + end + '&jenis_pembayaran=' + jenis, '_blank');
    });

    btnCetakExcelPembayaran.addEventListener('click', () => {
        const start = document.getElementById('start_date').value;
        const end = document.getElementById('end_date').value;
        const jenis = document.getElementById('jenis_pembayaran').value;
        
        // Redirect ke route cetak Excel dengan parameter filter
        window.open('{{ route("admin.pembayaran.cetak-excel") }}?start_date=' + start + '&end_date=' + end + '&jenis_pembayaran=' + jenis, '_blank');
    });

    // Fungsi untuk format rupiah
    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID').format(angka);
    }

    // Fungsi untuk format tanggal
    function formatTanggal(tanggal) {
        if (!tanggal) return '-';
        const date = new Date(tanggal);
        return date.toLocaleDateString('id-ID');
    }

    // Fungsi untuk capitalize first letter dan replace underscore
    function ucfirst(str) {
        if (!str) return '-';
        return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
    }
});
</script>
@endsection