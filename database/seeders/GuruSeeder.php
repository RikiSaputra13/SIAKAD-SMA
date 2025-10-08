<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Guru;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class GuruSeeder extends Seeder
{
    public function run()
    {
        DB::transaction(function () {
            // Cari atau buat user guru
            $user = User::updateOrInsert(
                ['email' => 'guru@pjayakarta.sch.id'],
                [
                    'name' => 'Guru Pangeran',
                    'email' => 'guru@pjayakarta.sch.id',
                    'password' => Hash::make('password123'),
                    'role' => 'guru',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            // Ambil user yang baru dibuat/ditemukan
            $user = User::where('email', 'guru@pjayakarta.sch.id')->first();

            // Buat data guru yang terkait
            Guru::updateOrInsert(
                ['user_id' => $user->id],
                [
                    'nama' => 'Guru Pangeran',
                    'nip' => '1234567890',
                    'mapel' => 'Matematika',
                    'alamat' => 'Jl. Contoh Alamat No. 123',
                    'jenis_kelamin' => 'L',
                    'no_hp' => '081234567890',
                    'user_id' => $user->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        });
    }
}