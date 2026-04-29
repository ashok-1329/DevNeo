<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $data = [

            // Project Settings
            ['name' => 'project', 'type' => 1, 'key' => 'project_number_start', 'value' => '001'],
            ['name' => 'project', 'type' => 1, 'key' => 'project_number_prefix', 'value' => 'PSA'],

            // Contract Types
            ['name' => 'project', 'type' => 1, 'key' => 'contract_type_name', 'value' => 'AS2124'],
            ['name' => 'project', 'type' => 1, 'key' => 'contract_type_name', 'value' => 'LGA Minor Contract'],
            ['name' => 'project', 'type' => 1, 'key' => 'contract_type_name', 'value' => 'LGA Major Contract'],
            ['name' => 'project', 'type' => 1, 'key' => 'contract_type_name', 'value' => 'Purchase Order'],
            ['name' => 'project', 'type' => 1, 'key' => 'contract_type_name', 'value' => 'Purchase Contract'],
            ['name' => 'project', 'type' => 1, 'key' => 'contract_type_name', 'value' => 'Other'],

            // Payment Terms
            ['name' => 'project', 'type' => 1, 'key' => 'payment_term_day', 'value' => '8 EOM'],
            ['name' => 'project', 'type' => 1, 'key' => 'payment_term_day', 'value' => '14'],
            ['name' => 'project', 'type' => 1, 'key' => 'payment_term_day', 'value' => '28'],
            ['name' => 'project', 'type' => 1, 'key' => 'payment_term_day', 'value' => '30'],
            ['name' => 'project', 'type' => 1, 'key' => 'payment_term_day', 'value' => '7'],
            ['name' => 'project', 'type' => 1, 'key' => 'payment_term_day', 'value' => '30 Days EOM'],
            ['name' => 'project', 'type' => 1, 'key' => 'payment_term_day', 'value' => '10'],

            // Margin
            ['name' => 'margin', 'type' => 2, 'key' => 'margin', 'value' => '14'],
        ];

        foreach ($data as &$row) {
            $row['created_at'] = now();
            $row['updated_at'] = now();
        }

        DB::table('settings')->insert($data);
    }
}
