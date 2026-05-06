<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

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
            'no_telp' => '081234567890',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'name' => 'Customer',
            'username' => 'customer',
            'email' => 'customer@gmail.com',
            'password' => Hash::make('123'),
            'role' => 'customer',
            'no_telp' => '086677889900',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->call(FiturSeeder::class);

        User::firstOrCreate(['email' => 'test@example.com'],
        [
            'name' => 'Test User',
            'username' => 'testuser',
            'no_telp' => '081122334455',
            'password' => Hash::make('password123'),
        ]);
    }
}
