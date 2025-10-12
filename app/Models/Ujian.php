<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ujian extends Model
{
    use HasFactory;

    protected $table = 'ujian';

    protected $fillable = [
        'guru_id',
        'kelas_id',
        'tipe_ujian_id',
        'mata_pelajaran',
        'judul_ujian',
        'deskripsi',
        'berkas_soal',
        'berkas_kunci_jawaban',
        'total_nilai',
        'waktu_mulai',
        'waktu_selesai',
        'batas_pengumpulan',
        'is_active',
        'status',
        'instruksi'
    ];

    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
        'batas_pengumpulan' => 'datetime',
    ];

    /** 🔹 Relasi antar model */
    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function tipeUjian()
    {
        return $this->belongsTo(TipeUjian::class);
    }

    public function pengumpulan()
    {
        return $this->hasMany(PengumpulanUjian::class, 'ujian_id');
    }

    /** Alias opsional untuk pengumpulan */
    public function jawaban()
    {
        return $this->hasMany(PengumpulanUjian::class, 'ujian_id');
    }

    /** 🔹 Fungsi bantu statistik pengumpulan */
    public function getPengumpulanStats()
    {
        $this->loadMissing(['pengumpulan', 'kelas.siswas']);

        $totalSiswa = $this->kelas->siswas->count() ?? 0;
        $submitted = $this->pengumpulan->whereNotNull('berkas_jawaban')->count();
        $graded = $this->pengumpulan->whereNotNull('nilai')->count();
        $pending = $submitted - $graded;

        return [
            'total_siswa' => $totalSiswa,
            'sudah_dikumpulkan' => $submitted,
            'belum_dikumpulkan' => max($totalSiswa - $submitted, 0),
            'sudah_dinilai' => $graded,
            'belum_dinilai' => max($pending, 0),
        ];
    }
}
