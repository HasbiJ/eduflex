<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Akun guru utama
        User::create([
            'name'     => 'Bu Rina Wijaya',
            'email'    => 'rina@eduflex.com',
            'password' => Hash::make('password'),
            'role'     => 'guru',
        ]);

        // Akun non-guru, untuk menguji penolakan akses
        User::create([
            'name'     => 'Budi Santoso',
            'email'    => 'siswa@eduflex.com',
            'password' => Hash::make('password'),
            'role'     => 'siswa',
        ]);
    }
}
