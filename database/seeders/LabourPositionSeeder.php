<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LabourPosition;
use Carbon\Carbon;

class LabourPositionSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['id' => 1, 'name' => 'Supervisor', 'rate' => 100, 'status' => 1, 'created_at' => '2023-07-19 05:36:17', 'updated_at' => '2023-07-19 05:36:17'],
            ['id' => 2, 'name' => 'Leading Hand', 'rate' => 90, 'status' => 1, 'created_at' => '2023-07-19 05:36:17', 'updated_at' => '2023-07-19 05:36:17'],
            ['id' => 3, 'name' => 'Pipelayer', 'rate' => 85, 'status' => 1, 'created_at' => '2023-07-19 05:36:17', 'updated_at' => '2023-07-19 05:36:17'],
            ['id' => 4, 'name' => 'Operator', 'rate' => 80, 'status' => 1, 'created_at' => '2023-07-19 05:36:17', 'updated_at' => '2023-07-19 05:36:17'],
            ['id' => 5, 'name' => 'Truck Driver', 'rate' => 75, 'status' => 1, 'created_at' => '2023-07-19 05:36:17', 'updated_at' => '2023-07-19 05:36:17'],
            ['id' => 6, 'name' => 'Labourer', 'rate' => 70, 'status' => 1, 'created_at' => '2023-07-19 05:36:17', 'updated_at' => '2023-07-19 05:36:17'],
            ['id' => 7, 'name' => 'Concreter', 'rate' => 65, 'status' => 1, 'created_at' => '2023-07-19 05:36:17', 'updated_at' => '2023-07-19 05:36:17'],
            ['id' => 8, 'name' => 'All Rounder', 'rate' => 60, 'status' => 1, 'created_at' => '2023-07-19 05:36:17', 'updated_at' => '2023-07-19 05:36:17'],
            ['id' => 9, 'name' => 'Subcontractor', 'rate' => 55, 'status' => 1, 'created_at' => '2023-07-19 05:36:17', 'updated_at' => '2023-07-19 05:36:17'],
            ['id' => 10, 'name' => 'Construction Manager', 'rate' => 50, 'status' => 1, 'created_at' => '2024-12-16 15:38:52', 'updated_at' => '2024-12-16 15:38:52'],
            ['id' => 11, 'name' => 'Operations Manager', 'rate' => 45, 'status' => 1, 'created_at' => '2024-12-16 15:40:29', 'updated_at' => '2024-12-16 15:40:29'],
            ['id' => 12, 'name' => 'Project Manager', 'rate' => 40, 'status' => 1, 'created_at' => '2024-12-16 15:41:03', 'updated_at' => '2024-12-16 15:41:03'],
            ['id' => 13, 'name' => 'Project Engineer', 'rate' => 35, 'status' => 1, 'created_at' => '2024-12-16 15:42:59', 'updated_at' => '2024-12-16 15:42:59'],
            ['id' => 14, 'name' => 'Site Engineer', 'rate' => 30, 'status' => 1, 'created_at' => '2024-12-16 15:44:06', 'updated_at' => '2024-12-16 15:44:06'],
            ['id' => 15, 'name' => 'Graduate Engineer', 'rate' => 25, 'status' => 1, 'created_at' => '2024-12-16 15:45:10', 'updated_at' => '2024-12-16 15:45:10'],
        ];

        foreach ($data as $item) {
            LabourPosition::create($item);
        }
    }
}