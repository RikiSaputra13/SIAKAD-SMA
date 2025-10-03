<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Models\Token;
use Illuminate\Http\Request;

class TokenController extends Controller
{
    public function generateToken()
    {
        // Hapus token lama yang sudah kadaluarsa atau sudah digunakan
        Token::where('expired_at', '<', now())->orWhere('is_used', true)->delete();

        // Buat token baru
        $token = Token::create([
            'token_kode' => Str::random(6), // Menghasilkan 6 karakter acak
            'expired_at' => now()->addMinutes(10), // Token berlaku selama 10 menit
            'is_used' => false,
        ]);

        // Ganti pesan sukses agar juga mengirimkan token baru
        return back()->with('success_token', 'Token baru berhasil dibuat: ' . $token->token_kode);
    }
}