<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'nama' => 'Administrator Sistem',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        User::create([
            'nama' => 'Kepala Instalasi Gizi',
            'username' => 'kepalagizi',
            'password' => Hash::make('gizi123'),
            'role' => 'kepala_gizi',
            'status' => 'active',
        ]);

        User::create([
            'nama' => 'Staf Keuangan',
            'username' => 'keuangan',
            'password' => Hash::make('keu123'),
            'role' => 'keuangan',
            'status' => 'active',
        ]);
    }
}