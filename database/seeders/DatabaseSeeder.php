<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Jalankan seeder lain
        $this->call([
            AdminSeeder::class,
            UserSeeder::class,
        ]);
    }
}
