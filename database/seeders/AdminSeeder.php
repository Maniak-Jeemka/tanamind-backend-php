<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Buat akun admin default.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@pakcoy.com'],
            [
                'name'     => 'Admin PakChoy',
                'password' => bcrypt('admin123'),
                'role'     => 'admin',
            ]
        );
    }
}
