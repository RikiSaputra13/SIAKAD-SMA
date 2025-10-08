<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengumpulanUjian extends Model
{
    use HasFactory;

    protected $table = 'pengumpulan_ujian';

    protected $fillable = [
        'siswa_id',
        'ujian_id',
        'berkas_jawaban',
        'catatan_siswa',
        'catatan_guru',
        'nilai',
        'waktu_pengumpulan',
        'status'
    ];

    protected $casts = [
        'waktu_pengumpulan' => 'datetime',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function ujian()
    {
        return $this->belongsTo(Ujian::class);
    }
}