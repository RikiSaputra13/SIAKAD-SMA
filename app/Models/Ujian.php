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
        return $this->hasMany(PengumpulanUjian::class);
    }
}