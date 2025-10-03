<!DOCTYPE html>
<html>
<head>
    <title>Rekap Absensi</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin-bottom: 5px; }
        .header p { margin: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: center; }
        th { background-color: #f2f2f2; }
        .text-left { text-align: left; }
        .summary-table th { background-color: #e8f4fd; }

        .statistik { margin-bottom: 20px; }
        .statistik-item { 
            display: inline-block; 
            margin-right: 10px; 
            padding: 6px 10px; 
            border-radius: 5px; 
            color: white; 
            font-weight: bold;
            font-size: 11px;
        }

        .count-hadir { color: #28a745; font-weight: bold; }
        .count-sakit { color: #ff9800; font-weight: bold; }
        .count-izin { color: #2196f3; font-weight: bold; }
        .count-alpha { color: #f44336; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Rekap Data Absensi</h1>
        <p>
            Periode: 
            {{ $start_date ? \Carbon\Carbon::parse($start_date)->format('d/m/Y') : 'Semua' }} - 
            {{ $end_date ? \Carbon\Carbon::parse($end_date)->format('d/m/Y') : 'Semua' }}
        </p>
        <p>Tanggal Cetak: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    {{-- Statistik --}}
    <div class="statistik">
        <div class="statistik-item hadir">Hadir: {{ $statistik['Hadir'] ?? 0 }}</div>
        <div class="statistik-item sakit">Sakit: {{ $statistik['Sakit'] ?? 0 }}</div>
        <div class="statistik-item izin">Izin: {{ $statistik['Izin'] ?? 0 }}</div>
        <div class="statistik-item alpha">Alpha: {{ $statistik['Alpha'] ?? 0 }}</div>
        <div class="statistik-item total">Total: {{ $statistik['total'] ?? 0 }}</div>
    </div>

    {{-- Tabel Detail Absensi --}}
    <table>
        <thead>
            <tr>
                <th class="text-left">NIS</th>
                <th class="text-left">Nama Siswa</th>
                <th>Kelas</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($absensis as $item)
                <tr>
                    <td class="text-left">{{ optional($item->siswa)->nis ?? '-' }}</td>
                    <td class="text-left">{{ optional($item->siswa)->nama ?? '-' }}</td>
                    <td>{{ optional(optional($item->siswa)->kelas)->nama_kelas ?? '-' }}</td>
                    <td>{{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') : '-' }}</td>
                    <td>
                        @switch($item->status)
                            @case('Hadir') <span style="color:green; font-weight:bold;">Hadir</span> @break
                            @case('Sakit') <span style="color:orange; font-weight:bold;">Sakit</span> @break
                            @case('Izin')  <span style="color:blue; font-weight:bold;">Izin</span> @break
                            @case('Alpha') <span style="color:red; font-weight:bold;">Alpha</span> @break
                            @default {{ $item->status ?? '-' }}
                        @endswitch
                    </td>
                    <td>{{ $item->keterangan_izin ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Tidak ada data absensi</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Tabel Ringkasan Presensi per Siswa --}}
    <h3 style="margin-top: 30px;">Ringkasan Presensi per Siswa</h3>
    <table class="summary-table">
        <thead>
            <tr>
                <th class="text-left">NIS</th>
                <th class="text-left">Nama Siswa</th>
                <th>Kelas</th>
                <th>Hadir</th>
                <th>Sakit</th>
                <th>Izin</th>
                <th>Alpha</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                // Group data by siswa untuk menghitung total presensi
                $siswaPresensi = [];
                foreach($absensis as $item) {
                    $siswaId = optional($item->siswa)->id;
                    if (!$siswaId) continue;
                    
                    if (!isset($siswaPresensi[$siswaId])) {
                        $siswaPresensi[$siswaId] = [
                            'nis' => optional($item->siswa)->nis ?? '-',
                            'nama' => optional($item->siswa)->nama ?? '-',
                            'kelas' => optional(optional($item->siswa)->kelas)->nama_kelas ?? '-',
                            'Hadir' => 0,
                            'Sakit' => 0,
                            'Izin' => 0,
                            'Alpha' => 0,
                            'Total' => 0
                        ];
                    }
                    
                    $siswaPresensi[$siswaId][$item->status]++;
                    $siswaPresensi[$siswaId]['Total']++;
                }
            @endphp
            
            @forelse($siswaPresensi as $presensi)
                <tr>
                    <td class="text-left">{{ $presensi['nis'] }}</td>
                    <td class="text-left">{{ $presensi['nama'] }}</td>
                    <td>{{ $presensi['kelas'] }}</td>
                    <td class="count-hadir">{{ $presensi['Hadir'] }}</td>
                    <td class="count-sakit">{{ $presensi['Sakit'] }}</td>
                    <td class="count-izin">{{ $presensi['Izin'] }}</td>
                    <td class="count-alpha">{{ $presensi['Alpha'] }}</td>
                    <td><strong>{{ $presensi['Total'] }}</strong></td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">Tidak ada data presensi</td>
                </tr>
            @endforelse
        </tbody>
        {{-- Footer dengan total --}}
        @if(count($siswaPresensi) > 0)
        <tfoot>
            <tr>
                <td colspan="3" class="text-left"><strong>Total Keseluruhan</strong></td>
                <td class="count-hadir"><strong>{{ $statistik['Hadir'] ?? 0 }}</strong></td>
                <td class="count-sakit"><strong>{{ $statistik['Sakit'] ?? 0 }}</strong></td>
                <td class="count-izin"><strong>{{ $statistik['Izin'] ?? 0 }}</strong></td>
                <td class="count-alpha"><strong>{{ $statistik['Alpha'] ?? 0 }}</strong></td>
                <td><strong>{{ $statistik['total'] ?? 0 }}</strong></td>
            </tr>
        </tfoot>
        @endif
    </table>

    {{-- Tanda tangan --}}
    <table style="width:100%; margin-top:40px; border:0;">
        <tr>
            <td style="text-align:left; border:0;">
                Mengetahui,<br>
                Kepala Sekolah<br><br><br><br>
                (_____________________)
            </td>
            <td style="text-align:right; border:0;">
                Bekasi, {{ now()->translatedFormat('d F Y') }}<br>
                 Wali Kelas<br><br><br><br>
                (_____________________)
            </td>
        </tr>
    </table>
</body>
</html>