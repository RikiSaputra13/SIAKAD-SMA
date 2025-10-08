<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    use HasFactory;

    protected $table = 'penilaian';

    protected $fillable = [
        'siswa_id',
        'kelas_id',
        'guru_id',
        'mata_pelajaran',
        'tahun_ajaran',
        'semester',
        'nilai_uh',
        'nilai_uts',
        'nilai_uas',
        'nilai_tugas',
        'nilai_praktik',
        'nilai_akhir',
        'predikat',
        'deskripsi'
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }
}