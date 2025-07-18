<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Admin kullanıcı
        User::create([
            'name' => ' ADMİN',
            'email' => 'admin@dgn.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);

        // Eğitmenler
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'name' => 'Eğitmen ' . $i,
                'email' => 'instructor' . $i . '@example.com',
                'password' => Hash::make('password'),
                'role' => 'instructor'
            ]);
        }

        // Öğrenciler
        for ($i = 1; $i <= 20; $i++) {
            User::create([
                'name' => 'Öğrenci ' . $i,
                'email' => 'student' . $i . '@example.com',
                'password' => Hash::make('password'),
                'role' => 'student'
            ]);
        }
    }
}
