<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Relasi ke model Siswa - YANG DIPERBAIKI
     * Asumsi: tabel siswas memiliki kolom 'user_id' sebagai foreign key
     */
    public function siswa()
    {
        return $this->hasOne(Siswa::class, 'user_id', 'id');
        // Parameter:
        // 1. Model tujuan (Siswa)
        // 2. Foreign key di tabel siswas (user_id)
        // 3. Local key di tabel users (id)
    }

    /**
     * Scope untuk user dengan role siswa
     */
    public function scopeSiswa($query)
    {
        return $query->where('role', 'siswa');
    }

    /**
     * Scope untuk user dengan role guru
     */
    public function scopeGuru($query)
    {
        return $query->where('role', 'guru');
    }

    /**
     * Scope untuk user dengan role admin
     */
    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }

    /**
     * Cek apakah user adalah siswa
     */
    public function isSiswa()
    {
        return $this->role === 'siswa';
    }

    /**
     * Cek apakah user adalah guru
     */
    public function isGuru()
    {
        return $this->role === 'guru';
    }

    /**
     * Cek apakah user adalah admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}