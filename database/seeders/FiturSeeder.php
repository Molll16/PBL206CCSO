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
            ['nama_fitur' => 'Threat Summary'],
            ['nama_fitur' => 'Top Triggered Rules'],
            ['nama_fitur' => 'Failed Login Monitoring'],
            ['nama_fitur' => 'System Resources'],
            ['nama_fitur' => 'Network Traffic'],
            ['nama_fitur' => 'Service Status'],
            ['nama_fitur' => 'File Integrity Monitoring'],
            ['nama_fitur' => 'Active Connections'],
            ['nama_fitur' => 'Firewall Events'],
            ['nama_fitur' => 'User Login Activity'],
            ['nama_fitur' => 'GeoIP Attack Map'],
        ]);
    }
}