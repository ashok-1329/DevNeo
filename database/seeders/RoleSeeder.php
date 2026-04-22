<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
   use App\Models\Role;
class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */


public function run()
{
    $roles = [
        ['name' => 'Admin', 'slug' => 'admin'],
        ['name' => 'Project Manager', 'slug' => 'project_manager'],
        ['name' => 'Field Staff', 'slug' => 'field_staff'],
        ['name' => 'HR', 'slug' => 'hr'],
    ];

    foreach ($roles as $role) {
        Role::updateOrCreate(
            ['slug' => $role['slug']],
            ['name' => $role['name']]
        );
    }
}
}
