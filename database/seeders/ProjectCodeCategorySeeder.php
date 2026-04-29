<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProjectCodeCategory;

class ProjectCodeCategorySeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['id' => 1, 'name' => 'Sloane Tillman', 'code_name' => '1', 'assign_margin' => 10, 'status' => 1, 'created_at' => '2025-04-08 10:52:35', 'updated_at' => '2026-03-12 12:11:06'],
            ['id' => 2, 'name' => 'Matthew Hyde', 'code_name' => '2', 'assign_margin' => 15, 'status' => 1, 'created_at' => '2025-04-08 10:52:22', 'updated_at' => '2026-03-12 12:11:06'],
            ['id' => 3, 'name' => 'Adrienne Mccormick', 'code_name' => '3', 'assign_margin' => 10, 'status' => 1, 'created_at' => '2025-04-08 10:52:07', 'updated_at' => '2026-03-12 12:11:06'],
            ['id' => 4, 'name' => 'Angela Hughes', 'code_name' => '4', 'assign_margin' => 10, 'status' => 1, 'created_at' => '2025-04-08 10:51:53', 'updated_at' => '2026-03-12 12:11:06'],
            ['id' => 5, 'name' => 'Harlan Guthrie', 'code_name' => '5', 'assign_margin' => 10, 'status' => 1, 'created_at' => '2025-04-08 10:51:40', 'updated_at' => '2026-03-12 12:11:06'],
            ['id' => 6, 'name' => 'Madaline Mcintosh', 'code_name' => '6', 'assign_margin' => 15, 'status' => 1, 'created_at' => '2025-04-08 10:51:25', 'updated_at' => '2026-03-12 12:11:06'],
            ['id' => 7, 'name' => 'Whitney Kemp', 'code_name' => '7', 'assign_margin' => 12, 'status' => 1, 'created_at' => '2025-04-08 10:51:13', 'updated_at' => '2026-03-12 12:11:06'],
            ['id' => 8, 'name' => 'Reece Mullins', 'code_name' => '8', 'assign_margin' => 15, 'status' => 1, 'created_at' => '2025-04-08 10:50:59', 'updated_at' => '2026-03-12 12:11:06'],
            ['id' => 9, 'name' => 'Eliana Rosales', 'code_name' => '9', 'assign_margin' => 13, 'status' => 1, 'created_at' => '2025-04-08 10:50:12', 'updated_at' => '2026-03-12 12:11:06'],
            ['id' => 10, 'name' => 'Miriam Richardson', 'code_name' => '10', 'assign_margin' => 12, 'status' => 1, 'created_at' => '2025-04-08 10:49:56', 'updated_at' => '2026-03-12 12:11:06'],
            ['id' => 11, 'name' => 'Damian Pace', 'code_name' => '11', 'assign_margin' => 10, 'status' => 1, 'created_at' => '2025-04-08 10:49:37', 'updated_at' => '2026-03-12 12:11:06'],
            ['id' => 12, 'name' => 'Lacota Wheeler', 'code_name' => '12', 'assign_margin' => 5, 'status' => 1, 'created_at' => '2025-04-08 10:49:22', 'updated_at' => '2026-03-12 12:11:06'],
            ['id' => 13, 'name' => 'Brynn Daniel', 'code_name' => 'P', 'assign_margin' => 15, 'status' => 1, 'created_at' => '2025-04-08 10:49:04', 'updated_at' => '2026-03-12 12:11:06'],
            ['id' => 14, 'name' => 'Zephania Benson', 'code_name' => 'D', 'assign_margin' => 10, 'status' => 1, 'created_at' => '2025-04-08 10:48:00', 'updated_at' => '2026-03-12 12:11:06'],
            ['id' => 54, 'name' => 'Linda Wall', 'code_name' => 'N110', 'assign_margin' => 10, 'status' => 1, 'created_at' => '2025-08-05 05:39:00', 'updated_at' => '2026-03-12 12:11:06'],
        ];

        foreach ($data as $item) {
            ProjectCodeCategory::create($item);
        }
    }
}
