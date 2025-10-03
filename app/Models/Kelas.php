<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'nama_kelas',
        'wali_kelas_id'
    ];

    /**
     * Relasi ke model Guru
     * Sebuah Kelas dimiliki oleh satu Guru (wali kelas)
     */
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'wali_kelas_id');
    }

    /**
     * Relasi ke model Siswa
     * Sebuah Kelas memiliki banyak Siswa
     */
    public function siswas()
    {
        return $this->hasMany(Siswa::class, 'kelas_id');
    }
}