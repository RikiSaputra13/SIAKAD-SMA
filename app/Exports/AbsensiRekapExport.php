<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AbsensiRekapExport implements FromCollection, WithHeadings
{
    protected $absensis;

    // Terima collection langsung dari controller
    public function __construct($absensis)
    {
        $this->absensis = $absensis;
    }

    public function collection()
    {
        return $this->absensis->map(function ($item) {
            return [
                'Nama Siswa'       => $item->siswa->nama ?? '-',
                'Kelas'            => $item->siswa->kelas->nama_kelas ?? '-',
                'Tanggal'          => $item->tanggal,
                'Status'           => $item->status,
                'Keterangan Izin'  => $item->keterangan_izin ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return ['Nama Siswa', 'Kelas', 'Tanggal', 'Status', 'Keterangan Izin'];
    }
}
