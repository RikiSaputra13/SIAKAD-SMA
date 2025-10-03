<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    use HasFactory;

    // Tambahkan kolom-kolom yang bisa diisi secara massal
    protected $fillable = [
        'token_kode',
        'expired_at',
        'is_used',
    ];
}