<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // User modelini import ediyoruz
use Illuminate\Support\Facades\Hash; // Hash'i import ediyoruz

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Admin Kullanıcı',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'), // Şifre: password123
            'role' => 'admin' // Rolü admin olarak ayarlıyoruz
        ]);
    }
}