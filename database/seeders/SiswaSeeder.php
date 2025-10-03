<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class SiswaSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Riki Saputra',
            'email' => 'riki@siswa.sch.id',
            'password' => Hash::make('12345678'), // password siswa
            'role' => 'siswa',
        ]);
    }
}
