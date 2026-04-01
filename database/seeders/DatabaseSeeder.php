<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Akun kepala perpustakaan - fixed, hanya satu
        User::create([
            'name'     => 'Ikhsan Kardashian Halim',
            'email'    => 'ursqwerty34@gmail.com',
            'password' => bcrypt('1sanBella.'),
            'role'     => 'kepala_perpustakaan',
        ]);
    }
}
