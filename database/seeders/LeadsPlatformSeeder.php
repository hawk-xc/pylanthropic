<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LeadsPlatformSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $leads = [
            ['Amal Sholeh', 'https://core.sholeh.app/api/v1/programs?s=', true],
            ['Sharing Happiness', 'https://be.sharinghappiness.org/api/v1/program?keyword=', true],
            ['Raih Mimpi', 'https://api2.raihmimpi.id/campaign/search?search=', false],
            ['Kita Bisa', 'https://gateway.kitabisa.com/search/?q=', false],
            ['Bantu Tetangga', 'https://core.bantutetangga.com/campaign?page=1&str=bantu&per_page=100', true]
        ];

        foreach ($leads as $lead) {
            \App\Models\LeadsPlatform::create([
                'name' => $lead[0],
                'url' => $lead[1],
                'is_active' => $lead[2] ? 1 : 0
            ]);
        }
    }
}
