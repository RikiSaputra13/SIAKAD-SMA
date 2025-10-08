<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'nip',
        'mapel',
        'alamat',
        'jenis_kelamin',
        'no_hp',
        'user_id', 
    ];

    /**
     * Relasi ke model User (jika menggunakan sistem auth terpisah)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke model Jadwal
     */
    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }

    /**
     * Relasi ke model Kelas
     */
    public function kelas()
    {
        return $this->hasOne(Kelas::class, 'wali_kelas_id');
    }

    public function getNamaLengkapAttribute()
    {
        return $this->nama . ' - ' . $this->mapel;
    }

    public function ujian()
    {
        return $this->hasMany(Ujian::class);
    }
    public function penilaian()
    {
        return $this->hasMany(Penilaian::class);
    }
    
}