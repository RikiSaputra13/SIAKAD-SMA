@extends('siswa.layouts.app')

@section('title', 'Daftar Jadwal')
@section('header', 'Daftar Jadwal')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Jadwal</h5>
            <div class="filter-section">
                <select id="hariFilter" class="form-select form-select-sm">
                    <option value="">Semua Hari</option>
                    <option value="Senin">Senin</option>
                    <option value="Selasa">Selasa</option>
                    <option value="Rabu">Rabu</option>
                    <option value="Kamis">Kamis</option>
                    <option value="Jumat">Jumat</option>
                    <option value="Sabtu">Sabtu</option>
                    <option value="Minggu">Minggu</option>
                </select>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            {{-- Info Filter Aktif --}}
            <div id="filterInfo" class="alert alert-info d-none">
                Menampilkan jadwal hari: <strong id="currentFilter"></strong>
                <button type="button" class="btn-close float-end" onclick="resetFilter()"></button>
            </div>

            {{-- Tabel Jadwal --}}
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center">
                    <thead class="bg-light">
                        <tr>
                            <th>No</th>
                            <th>Guru</th>
                            <th>Kelas</th>
                            <th>Mata Pelajaran</th>
                            <th>Hari</th>
                            <th>Jam Mulai</th>
                            <th>Jam Selesai</th>
                        </tr>
                    </thead>
                    <tbody id="jadwalTableBody">
                        @forelse($jadwals as $index => $jadwal)
                            <tr class="jadwal-row" data-hari="{{ $jadwal->hari }}">
                                <td>{{ $index + 1 }}</td>
                                <td>{{ optional($jadwal->guru)->nama ?? '-' }}</td>
                                <td>{{ optional($jadwal->kelas)->nama_kelas ?? '-' }}</td>
                                <td>{{ $jadwal->mata_pelajaran }}</td>
                                <td>{{ $jadwal->hari }}</td>
                                <td>{{ $jadwal->jam_mulai }}</td>
                                <td>{{ $jadwal->jam_selesai }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Belum ada data jadwal.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pesan ketika tidak ada data setelah filter --}}
            <div id="noDataMessage" class="alert alert-warning text-center d-none">
                Tidak ada jadwal untuk hari yang dipilih.
            </div>
        </div>
    </div>
</div>

<style>
.filter-section {
    min-width: 150px;
}

.jadwal-row {
    transition: all 0.3s ease;
}

.table-responsive {
    max-height: 600px;
    overflow-y: auto;
}

.bg-light {
    background-color: #f8f9fa !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const hariFilter = document.getElementById('hariFilter');
    const jadwalRows = document.querySelectorAll('.jadwal-row');
    const filterInfo = document.getElementById('filterInfo');
    const currentFilter = document.getElementById('currentFilter');
    const noDataMessage = document.getElementById('noDataMessage');
    const tableBody = document.getElementById('jadwalTableBody');

    // Filter berdasarkan hari
    hariFilter.addEventListener('change', function() {
        const selectedHari = this.value;
        let visibleRows = 0;

        jadwalRows.forEach(row => {
            if (selectedHari === '' || row.getAttribute('data-hari') === selectedHari) {
                row.style.display = '';
                visibleRows++;
            } else {
                row.style.display = 'none';
            }
        });

        // Tampilkan/sembunyikan pesan info filter
        if (selectedHari !== '') {
            currentFilter.textContent = selectedHari;
            filterInfo.classList.remove('d-none');
        } else {
            filterInfo.classList.add('d-none');
        }

        // Tampilkan pesan jika tidak ada data
        if (visibleRows === 0) {
            noDataMessage.classList.remove('d-none');
            tableBody.style.display = 'none';
        } else {
            noDataMessage.classList.add('d-none');
            tableBody.style.display = '';
        }

        // Update nomor urut
        updateRowNumbers();
    });

    // Auto-select hari ini
    autoSelectToday();
});

function updateRowNumbers() {
    const visibleRows = document.querySelectorAll('.jadwal-row:not([style*="display: none"])');
    visibleRows.forEach((row, index) => {
        row.cells[0].textContent = index + 1;
    });
}

function resetFilter() {
    document.getElementById('hariFilter').value = '';
    document.getElementById('hariFilter').dispatchEvent(new Event('change'));
}

function autoSelectToday() {
    const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    const today = new Date().getDay();
    const todayName = days[today];
    
    const hariFilter = document.getElementById('hariFilter');
    hariFilter.value = todayName;
    hariFilter.dispatchEvent(new Event('change'));
}
</script>
@endsection