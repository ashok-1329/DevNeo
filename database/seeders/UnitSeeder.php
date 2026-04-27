<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('units')->insert([
            ['id' => 1, 'name' => 'Tonne', 'type' => 1, 'status' => 1],
            ['id' => 2, 'name' => 'm', 'type' => 1, 'status' => 1],
            ['id' => 3, 'name' => 'm2', 'type' => 1, 'status' => 1],
            ['id' => 4, 'name' => 'm3', 'type' => 1, 'status' => 1],
            ['id' => 5, 'name' => 'no.', 'type' => 1, 'status' => 1],
            ['id' => 6, 'name' => 'unit', 'type' => 1, 'status' => 1],
            ['id' => 7, 'name' => 'item', 'type' => 1, 'status' => 1],
            ['id' => 8, 'name' => 'each', 'type' => 1, 'status' => 1],
            ['id' => 9, 'name' => 'minutes', 'type' => 1, 'status' => 1],
            ['id' => 10, 'name' => 'kg', 'type' => 1, 'status' => 1],
            ['id' => 11, 'name' => 'litres', 'type' => 1, 'status' => 1],
            ['id' => 12, 'name' => 'Other', 'type' => 1, 'status' => 1],
            ['id' => 13, 'name' => 'Day Rate', 'type' => 2, 'status' => 1],
            ['id' => 14, 'name' => 'Hourly Rate', 'type' => 2, 'status' => 1],

            ['id' => 50, 'name' => 'abc', 'type' => 1, 'status' => 1],
            ['id' => 51, 'name' => 'xyz', 'type' => 1, 'status' => 1],
            ['id' => 52, 'name' => 'new', 'type' => 1, 'status' => 1],
            ['id' => 53, 'name' => 'new 1', 'type' => 1, 'status' => 1],
            ['id' => 54, 'name' => 'new 2', 'type' => 1, 'status' => 1],
            ['id' => 55, 'name' => 'new 3', 'type' => 1, 'status' => 1],
            ['id' => 56, 'name' => 'abc', 'type' => 1, 'status' => 1],
            ['id' => 57, 'name' => 'xyz', 'type' => 1, 'status' => 1],
            ['id' => 58, 'name' => 'ttt', 'type' => 1, 'status' => 1],
            ['id' => 59, 'name' => 'tttt1', 'type' => 1, 'status' => 1],
            ['id' => 60, 'name' => 'ttt', 'type' => 1, 'status' => 1],
            ['id' => 61, 'name' => 'tttt1', 'type' => 1, 'status' => 1],
            ['id' => 62, 'name' => '33333', 'type' => 1, 'status' => 1],
            ['id' => 63, 'name' => '444', 'type' => 1, 'status' => 1],
            ['id' => 64, 'name' => 'ttt', 'type' => 1, 'status' => 1],
            ['id' => 65, 'name' => 'ttere', 'type' => 1, 'status' => 1],
            ['id' => 66, 'name' => 'sdff', 'type' => 1, 'status' => 1],
            ['id' => 67, 'name' => 'yes', 'type' => 1, 'status' => 1],
            ['id' => 68, 'name' => 'yesss', 'type' => 1, 'status' => 1],
            ['id' => 69, 'name' => '956', 'type' => 1, 'status' => 1],
        ]);
    }
}
