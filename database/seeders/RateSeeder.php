<?php

namespace Database\Seeders;

use App\Models\GstRate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        GstRate::create([
            'rate' => 10.00,
            'created_at' => '2023-03-30 14:18:40',
            'updated_at' => '2023-03-23 14:18:40',
        ]);
    }
}
