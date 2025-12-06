<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    use HasFactory;

    protected $fillable = [
        'guru_id',
        'mapel_id', 
        'kelas_id',
        'token_kode', // gunakan token_kode yang sudah ada
        'expired_at',
        'attendance_date',
        'jam_mulai',
        'jam_selesai',
        'is_used',
        'created_by'
    ];

    protected $casts = [
        'expired_at' => 'datetime',
        'attendance_date' => 'date',
        'is_used' => 'boolean',
    ];

    // Custom accessor untuk mendapatkan token
    public function getTokenAttribute()
    {
        return $this->token_kode;
    }

    // Relasi ke Guru
    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    // Relasi ke Mapel
    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }

    // Relasi ke Kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    // Relasi ke Absensi
    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'token_id');
    }

    // Scope untuk token yang aktif
    public function scopeActive($query)
    {
        return $query->where('expired_at', '>', now())
                    ->where('is_used', false);
    }

    // Scope untuk token hari ini
    public function scopeToday($query)
    {
        return $query->whereDate('attendance_date', today());
    }

    // Scope untuk token berdasarkan guru
    public function scopeByGuru($query, $guruId)
    {
        return $query->where('guru_id', $guruId);
    }

    // Cek apakah token masih valid
    public function isValid()
    {
        if (!$this->expired_at) return false;
        
        return $this->expired_at->isFuture() && 
               $this->attendance_date->isToday() &&
               !$this->is_used;
    }
}