<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CertificationTitleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('certification_titles')->insert([
            [
                'id' => 1,
                'name' => 'White Card',
                'created_at' => '2023-03-31 14:23:43',
                'updated_at' => '2023-07-14 12:13:41',
            ],
            [
                'id' => 2,
                'name' => "Driver's Licence",
                'created_at' => '2023-03-31 14:23:58',
                'updated_at' => '2023-07-14 12:13:56',
            ],
            [
                'id' => 3,
                'name' => 'First Aid',
                'created_at' => '2023-03-31 14:24:16',
                'updated_at' => '2023-07-14 12:14:23',
            ],
            [
                'id' => 4,
                'name' => 'Traffic Management',
                'created_at' => '2023-03-31 14:24:31',
                'updated_at' => '2023-07-14 12:14:34',
            ],
            [
                'id' => 5,
                'name' => 'VOCs',
                'created_at' => '2023-03-31 14:24:43',
                'updated_at' => '2023-07-14 12:14:44',
            ],
            [
                'id' => 6,
                'name' => 'High-Risk Tickets',
                'created_at' => '2023-07-14 12:14:57',
                'updated_at' => '2023-07-14 12:14:57',
            ],
            [
                'id' => 7,
                'name' => 'Other',
                'created_at' => '2023-07-14 12:15:29',
                'updated_at' => '2023-07-14 12:15:29',
            ],
        ]);
    }
}