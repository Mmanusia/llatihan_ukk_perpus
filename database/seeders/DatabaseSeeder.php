<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@test.com'],
            [
                'nama_lengkap' => 'atmin',
                'username' => 'aku atmin',
                'alamat' => 'Alamat atmin',
                'role' => 'admin',
                'password' => '12345678',
            ]
        );
    }
}
