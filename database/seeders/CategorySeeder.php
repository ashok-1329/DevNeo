<?php

namespace Database\Seeders;

use App\Models\DiaryProductCategorie;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id' => 1,
                'name' => 'General',
                'created_at' => '2023-10-05 07:44:31',
                'updated_at' => '2023-10-05 07:44:39',
            ],
            [
                'id' => 2,
                'name' => 'Stormwater',
                'created_at' => '2023-10-05 07:27:33',
                'updated_at' => '2023-10-05 07:27:33',
            ],
            [
                'id' => 3,
                'name' => 'Quarry',
                'created_at' => '2023-10-05 07:27:46',
                'updated_at' => '2023-10-05 07:27:46',
            ],
            [
                'id' => 4,
                'name' => 'Sewer',
                'created_at' => '2023-10-05 07:28:11',
                'updated_at' => '2023-10-05 07:28:11',
            ],
            [
                'id' => 5,
                'name' => 'Water',
                'created_at' => '2023-10-05 07:45:01',
                'updated_at' => '2023-10-05 07:45:01',
            ],
            [
                'id' => 13,
                'name' => 'Steel',
                'created_at' => '2024-06-14 04:43:49',
                'updated_at' => '2024-06-14 04:43:49',
            ],
        ];

        foreach ($data as $item) {
            DiaryProductCategorie::create($item);
        }
    }
}
