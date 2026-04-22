<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
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

        $role = Role::where('slug', 'admin')->first();

        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin@123'),
            'role_id' => $role ? $role->id : 1,
        ]);

        $categories = [
            'Neo',
            'KOYA',
            'CAT Rental',
            'AGI Hire',
            'Conplant',
            'ACT Hire',
            'Brooks Hire',
            'V Rent',
            'Kennards Hire',
            'DARE Equipment',
            'Loadex',
            'MEH Hire and Equipment',
            'Ozzie Water Carts',
            'RAM Equipment',
            'Coates Hire',
        ];

        foreach ($categories as $name) {
            DB::table('supplier_categories')->insert([
                'name'       => $name,
                'status'     => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $nominatecategories = ['Materials', 'Subcontractor', 'Plant Hire', 'Labour Hire'];

        foreach ($nominatecategories as $name) {
            DB::table('supplier_nominate_categories')->insert([
                'name'       => $name,
                'status'     => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
