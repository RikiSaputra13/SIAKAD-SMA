<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $table = 'jadwals';
    protected $guarded = ['id'];

    /**
     * Get the guru that owns the Jadwal.
     */
    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    /**
     * Get the mapel that owns the Jadwal.
     */
    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }

    /**
     * Get the kelas that owns the Jadwal.
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    /**
     * Accessor untuk mendapatkan nama mata pelajaran
     * Jika ada relasi mapel, gunakan nama mapel, else gunakan mata_pelajaran
     */
    public function getNamaMapelAttribute()
    {
        return $this->mapel ? $this->mapel->nama : $this->mata_pelajaran;
    }
}