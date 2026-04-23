<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FrequencyPaymentSeeder extends Seeder
{
    public function run(): void
    {
        $records = [
            ['id' => 1, 'name' => 'Weekly',      'status' => 1, 'created_at' => '2023-05-22 09:42:47', 'updated_at' => '2024-06-06 08:57:00'],
            ['id' => 2, 'name' => 'Fortnightly',  'status' => 1, 'created_at' => '2025-06-24 17:51:34', 'updated_at' => '2025-06-24 17:51:39'],
            ['id' => 3, 'name' => 'Monthly',      'status' => 1, 'created_at' => '2023-05-22 09:58:46', 'updated_at' => '2023-05-22 09:58:46'],
            ['id' => 4, 'name' => 'Quarterly',    'status' => 0, 'created_at' => '2023-05-22 09:59:02', 'updated_at' => '2023-05-22 09:59:02'],
            ['id' => 5, 'name' => 'Yearly',       'status' => 0, 'created_at' => '2023-05-22 09:59:16', 'updated_at' => '2023-05-22 09:59:16'],
        ];

        foreach ($records as $record) {
            DB::table('frequency_payments')->updateOrInsert(
                ['id' => $record['id']],
                $record
            );
        }
    }
}
