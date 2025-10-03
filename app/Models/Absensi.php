<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $fillable = [
        'siswa_id',
        'tanggal',
        'status',
        'keterangan_izin',
    ];

    /**
     * Get the Siswa that owns the Absensi.
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}