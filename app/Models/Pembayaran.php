<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    // ======================== Fillable Fields ========================
    protected $fillable = [
        'siswa_id',
        'jenis_pembayaran',
        'total_tagihan',
        'jumlah_bayar',
        'metode_pembayaran',
        'tanggal_bayar',
        'status',
    ];

    // ======================== Casts ========================
    protected $casts = [
        'total_tagihan' => 'decimal:2',
        'jumlah_bayar'  => 'decimal:2',
        'tanggal_bayar' => 'date',
    ];

    /**
     * Get the Siswa that owns the Pembayaran.
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
