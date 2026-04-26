<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FiturSeeder extends Seeder
{
    public function run()
    {
        DB::table('fitur')->insert([
            ['nama_fitur' => 'Security Alerts'],
            ['nama_fitur' => 'Agent Status'],
            ['nama_fitur' => 'Top Triggered Rules'],
        ]);
    }
}