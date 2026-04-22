<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeedData extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // $categories = [
        //     'Neo',
        //     'KOYA',
        //     'CAT Rental',
        //     'AGI Hire',
        //     'Conplant',
        //     'ACT Hire',
        //     'Brooks Hire',
        //     'V Rent',
        //     'Kennards Hire',
        //     'DARE Equipment',
        //     'Loadex',
        //     'MEH Hire and Equipment',
        //     'Ozzie Water Carts',
        //     'RAM Equipment',
        //     'Coates Hire',
        // ];

        // foreach ($categories as $name) {
        //     DB::table('supplier_categories')->insert([
        //         'name'       => $name,
        //         'status'     => 1,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);
        // }

        // $nominatecategories = ['Materials', 'Subcontractor', 'Plant Hire', 'Labour Hire'];

        // foreach ($nominatecategories as $name) {
        //     DB::table('supplier_nominate_categories')->insert([
        //         'name'       => $name,
        //         'status'     => 1,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);
        // }
    }
}
