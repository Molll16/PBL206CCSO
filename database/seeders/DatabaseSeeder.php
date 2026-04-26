<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123'),
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

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
