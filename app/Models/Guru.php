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
    ];

    /**
     * Relasi ke model Kelas
     * Seorang Guru bisa menjadi wali kelas untuk satu Kelas
     */
    public function kelas()
    {
        return $this->hasOne(Kelas::class, 'wali_kelas_id');
    }

    /**
     * Accessor untuk menampilkan nama guru dengan gelar (opsional)
     */
    public function getNamaLengkapAttribute()
    {
        return $this->nama . ' - ' . $this->mapel;
    }
}