<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Hapus dulu semua user (opsional, hati-hati!)
        // DB::table('users')->truncate();

       // Admin
        DB::table('users')->updateOrInsert(
            ['email' => 'admin@pjayakarta.sch.id'],
            [
                'name' => 'Admin Pangeran',
                'email' => 'admin_sma@gmail.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Siswa
        DB::table('users')->updateOrInsert(
            ['email' => 'riki@student.com'],
            [
                'name' => 'Riki Saputra',
                'email' => 'riki@student.com',
                'password' => Hash::make('123456'),
                'role' => 'siswa',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // // guru
        // DB::table('users')->updateOrInsert(
        //     ['email' => 'guru@pjayakarta.sch.id'],
        //     [
        //         'name' => 'Guru Pangeran',
        //         'email' => 'guru@pjayakarta.sch.id',
        //         'password' => Hash::make('password123'),
        //         'role' => 'guru',
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]
        // );
        // Tambahkan siswa lain sesuai kebutuhan
    }
}
