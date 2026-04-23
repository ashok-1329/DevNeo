<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentTermSeeder extends Seeder
{
    public function run(): void
    {
        $terms = [
            ['name' => '7 Days',     'days' => 7],
            ['name' => '8 EOM',      'days' => 8],
            ['name' => '10 Days',    'days' => 10],
            ['name' => '14 Days',    'days' => 14],
            ['name' => '28 Days',    'days' => 28],
            ['name' => '30 Days',    'days' => 30],
            ['name' => '30 Days EOM','days' => 30],
        ];

        foreach ($terms as $term) {
            DB::table('payment_terms')->updateOrInsert(
                ['name' => $term['name']],
                ['days' => $term['days'], 'is_active' => true, 'updated_at' => now(), 'created_at' => now()]
            );
        }
    }
}