<!DOCTYPE html>
<html>
<head>
    <title>Rekap Pembayaran</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
            color: #333;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e9ecef;
        }
        .header h2 {
            color: #2c3e50;
            margin: 0;
            font-size: 28px;
        }
        .header .subtitle {
            color: #6c757d;
            font-size: 16px;
            margin-top: 5px;
        }
        .filter-info {
            background: #f8f9fa;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 15px;
            border-left: 4px solid #007bff;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 14px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 10px 8px;
            font-weight: 600;
            text-align: left;
            border: none;
        }
        td {
            padding: 8px 8px;
            border-bottom: 1px solid #e9ecef;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        tr:hover {
            background-color: #e3f2fd;
            transition: background-color 0.2s ease;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
        .status-lunas {
            background-color: #d4edda;
            color: #155724;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }
        .status-belum {
            background-color: #f8d7da;
            color: #721c24;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }
        .summary {
            background: #e8f5e8;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
            margin-bottom: 10px;
            border-left: 4px solid #28a745;
        }
        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
            font-size: 14px;
        }
        .summary-total {
            font-size: 16px;
            font-weight: bold;
            color: #2c3e50;
            border-top: 2px solid #b8dacc;
            padding-top: 8px;
            margin-top: 8px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #e9ecef;
            color: #6c757d;
            font-size: 13px;
        }
        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
        }
        .badge-cash { background: #d1ecf1; color: #0c5460; }
        .badge-transfer { background: #d1edf1; color: #0c5460; }
        .badge-qris { background: #e8f5e8; color: #155724; }
        
        /* Tanda Tangan */
        .ttd-table {
            width: 100%;
            margin-top: 30px;
            border: 0;
        }
        .ttd-cell {
            text-align: center;
            border: 0;
            width: 50%;
            vertical-align: top;
        }
        .ttd-space {
            height: 60px;
        }
        .ttd-name {
            margin-top: 5px;
            font-weight: bold;
        }
        
        /* Section spacing */
        .section-title {
            color: #2c3e50;
            margin-bottom: 10px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>LAPORAN REKAP PEMBAYARAN</h2>
            <div class="subtitle">SMA PANGERAN JAYAKARTA</div>
        </div>
        
        @if($startDate && $endDate || $jenisPembayaran)
        <div class="filter-info">
            @if($startDate && $endDate)
                <div><strong>Periode:</strong> {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}</div>
            @endif
            
            @if($jenisPembayaran)
                <div><strong>Jenis Pembayaran:</strong> {{ $jenisPembayaran }}</div>
            @endif
        </div>
        @endif

        <h3 class="section-title">Detail Pembayaran</h3>
        <table>
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th>Nama Siswa</th>
                    <th>NIS</th>
                    <th>Jenis Pembayaran</th>
                    <th class="text-right">Total Tagihan</th>
                    <th class="text-right">Jumlah Bayar</th>
                    <th class="text-right">Sisa</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Metode</th>
                    <th class="text-center">Tanggal Bayar</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalTagihan = 0;
                    $totalBayar = 0;
                    $totalSisa = 0;
                @endphp
                
                @foreach($pembayarans as $index => $p)
                @php
                    $totalTagihan += $p->total_tagihan;
                    $totalBayar += $p->jumlah_bayar;
                    $sisa = $p->total_tagihan - $p->jumlah_bayar;
                    if($p->status == 'Belum Lunas') {
                        $totalSisa += $sisa;
                    }
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $p->siswa->nama ?? '-' }}</td>
                    <td>{{ $p->siswa->nis ?? '-' }}</td>
                    <td>{{ $p->jenis_pembayaran }}</td>
                    <td class="text-right">Rp {{ number_format($p->total_tagihan, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($p->jumlah_bayar, 0, ',', '.') }}</td>
                    <td class="text-right">
                        @if($p->status == 'Belum Lunas')
                            Rp {{ number_format($sisa, 0, ',', '.') }}
                        @else
                            <span style="color: #6c757d;">-</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <span class="{{ $p->status == 'Lunas' ? 'status-lunas' : 'status-belum' }}">
                            {{ $p->status }}
                        </span>
                    </td>
                    <td class="text-center">
                        <span class="badge badge-{{ strtolower($p->metode_pembayaran) }}">
                            {{ ucfirst(str_replace('_', ' ', $p->metode_pembayaran)) }}
                        </span>
                    </td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($p->tanggal_bayar)->translatedFormat('d/m/Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="summary">
            <div class="summary-item">
                <span>Total Tagihan:</span>
                <span class="bold">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</span>
            </div>
            <div class="summary-item">
                <span>Total Dibayar:</span>
                <span class="bold">Rp {{ number_format($totalBayar, 0, ',', '.') }}</span>
            </div>
            <div class="summary-item">
                <span>Total Sisa Tagihan:</span>
                <span class="bold">Rp {{ number_format($totalSisa, 0, ',', '.') }}</span>
            </div>
            <div class="summary-item summary-total">
                <span>Saldo Terbayar:</span>
                <span>Rp {{ number_format($totalBayar, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- Tanda tangan --}}
        <table class="ttd-table">
            <tr>
                <td class="ttd-cell">
                    Mengetahui,<br>
                    Kepala Sekolah<br>
                    <div class="ttd-space"></div>
                    <div class="ttd-name">(_____________________)</div>
                </td>
                <td class="ttd-cell">
                    Bekasi, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                    Bendahara<br>
                    <div class="ttd-space"></div>
                    <div class="ttd-name">(_____________________)</div>
                </td>
            </tr>
        </table>

        <div class="footer">
            <p>Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i:s') }}</p>
            <p>&copy; {{ date('Y') }} SMA PANGERAN JAYAKARTA</p>
        </div>
    </div>
</body>
</html>