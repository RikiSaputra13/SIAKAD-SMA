<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nis',
        'nama',
        'kelas_id',
        'alamat',
        'tlp_orang_tua',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'foto',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // TAMBAHKAN RELASI INI: Relasi ke Absensi (satu siswa banyak absensi)
    public function absensis()
    {
        return $this->hasMany(Absensi::class, 'siswa_id');
    }

    // Relasi ke Kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
}
