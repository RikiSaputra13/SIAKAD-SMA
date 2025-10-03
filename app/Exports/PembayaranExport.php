<?php

namespace App\Exports;

use App\Models\Pembayaran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PembayaranExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $startDate;
    protected $endDate;
    protected $jenisPembayaran;

    public function __construct($startDate = null, $endDate = null, $jenisPembayaran = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->jenisPembayaran = $jenisPembayaran;
    }

    public function collection()
    {
        $query = Pembayaran::with('siswa');

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tanggal_bayar', [$this->startDate, $this->endDate]);
        }

        if ($this->jenisPembayaran) {
            $query->where('jenis_pembayaran', $this->jenisPembayaran);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Nama Siswa',
            'Jenis Pembayaran',
            'Total Tagihan',
            'Jumlah Bayar',
            'Sisa',
            'Status',
            'Metode Pembayaran',
            'Tanggal Bayar'
        ];
    }

    public function map($pembayaran): array
    {
        return [
            $pembayaran->siswa->nama ?? '-',
            $pembayaran->jenis_pembayaran,
            $pembayaran->total_tagihan,
            $pembayaran->jumlah_bayar,
            $pembayaran->status == 'Belum Lunas' ? $pembayaran->total_tagihan - $pembayaran->jumlah_bayar : 0,
            $pembayaran->status,
            ucfirst(str_replace('_', ' ', $pembayaran->metode_pembayaran)),
            $pembayaran->tanggal_bayar
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => ['font' => ['bold' => true]],
        ];
    }
}