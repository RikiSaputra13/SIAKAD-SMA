<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $fillable = [
        'siswa_id',
        'guru_id',
        'mapel_id', 
        'token_id',
        'tanggal',
        'waktu',
        'status',
        'keterangan_izin',
        'keterangan',
        'token_used',
        'sesi'
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    /**
     * Get the Siswa that owns the Absensi.
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    /**
     * Get the Guru that owns the Absensi.
     */
    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    /**
     * Get the Mapel that owns the Absensi.
     */
    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }

    /**
     * Get the Token that owns the Absensi.
     */
    public function token()
    {
        return $this->belongsTo(Token::class);
    }
}