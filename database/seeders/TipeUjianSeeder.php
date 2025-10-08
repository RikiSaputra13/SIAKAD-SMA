<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipeUjian;

class TipeUjianSeeder extends Seeder
{
    /**
     * Jalankan seeder untuk mengisi data tipe ujian.
     */
    public function run(): void
    {
        $data = [
            [
                'nama' => 'Ujian Harian',
                'kode' => 'uh',
                'deskripsi' => 'Ujian untuk mengukur pemahaman siswa pada materi tertentu',
                'bobot' => 10,
                'is_active' => 1,
            ],
            [
                'nama' => 'Penilaian Tengah Semester',
                'kode' => 'pts',
                'deskripsi' => 'Ujian tengah semester',
                'bobot' => 30,
                'is_active' => 1,
            ],
            [
                'nama' => 'Penilaian Akhir Semester',
                'kode' => 'pas',
                'deskripsi' => 'Ujian akhir semester ganjil',
                'bobot' => 30,
                'is_active' => 1,
            ],
            [
                'nama' => 'Penilaian Akhir Tahun',
                'kode' => 'pat',
                'deskripsi' => 'Ujian akhir semester genap / akhir tahun pelajaran',
                'bobot' => 30,
                'is_active' => 1,
            ],
        ];

        foreach ($data as $item) {
            TipeUjian::updateOrCreate(
                ['kode' => $item['kode']], 
                $item                      
            );
        }
    }
}
