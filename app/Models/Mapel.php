<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'kode',
        'deskripsi'
    ];

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class);
    }

    public function tokens()
    {
        return $this->hasMany(Token::class);
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }
}