<?php

namespace Database\Seeders;

use App\Models\ProjectRegion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectRegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['id' => 1, 'name' => 'Metro & Hills', 'status' => 1, 'created_at' => '2023-08-03 15:36:17', 'updated_at' => '2023-08-04 08:26:23'],
            ['id' => 2, 'name' => 'Regional', 'status' => 1, 'created_at' => '2023-08-03 15:36:17', 'updated_at' => '2023-08-03 15:36:17'],
            ['id' => 3, 'name' => 'North', 'status' => 1, 'created_at' => '2023-08-03 15:36:17', 'updated_at' => '2023-08-03 15:36:17'],
            ['id' => 4, 'name' => 'South', 'status' => 1, 'created_at' => '2023-08-03 15:36:17', 'updated_at' => '2023-08-03 15:36:17'],
            ['id' => 6, 'name' => 'Other', 'status' => null, 'created_at' => '2024-06-05 03:33:12', 'updated_at' => '2024-06-05 03:34:27'],
            ['id' => 7, 'name' => 'New Region', 'status' => null, 'created_at' => '2024-12-05 12:06:36', 'updated_at' => '2024-12-05 12:06:36'],
            ['id' => 8, 'name' => 'Office', 'status' => 1, 'created_at' => '2025-06-16 17:43:11', 'updated_at' => null],
        ];

        foreach ($data as $item) {
            ProjectRegion::create($item);
        }
    }
}
