<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(FiturSeeder::class);

        User::firstOrCreate(['email' => 'test@example.com'],
        [
            'name' => 'Test User',
            'username' => 'testuser',
            'no_telp' => '08123456789',
            'password' => Hash::make('password123'),
        ]);
    }
}
