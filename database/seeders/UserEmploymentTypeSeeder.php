<?php

namespace Database\Seeders;

use App\Models\UserEmploymentType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserEmploymentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $data = [
            ['id' => 1, 'name' => 'Salary', 'created_at' => '2023-06-28 13:22:03', 'updated_at' => '2023-06-29 08:45:08'],
            ['id' => 3, 'name' => 'Wages', 'created_at' => '2023-06-28 13:22:37', 'updated_at' => '2023-06-28 13:22:37'],
            ['id' => 5, 'name' => 'Casual', 'created_at' => '2023-06-28 13:24:00', 'updated_at' => '2023-06-28 13:24:00'],
            ['id' => 6, 'name' => 'Labour Hire', 'created_at' => '2023-06-28 13:24:29', 'updated_at' => '2023-06-28 13:24:29'],
            ['id' => 7, 'name' => 'Subcontractor', 'created_at' => '2023-06-28 13:24:42', 'updated_at' => '2023-06-28 13:24:42'],
        ];

        foreach ($data as $item) {
            UserEmploymentType::create($item);
        }
    }
}
