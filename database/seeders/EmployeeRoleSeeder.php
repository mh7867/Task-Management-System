<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmployeeRole;

class EmployeeRoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            ['name' => 'Project Manager'],
            ['name' => 'IOS Developer'],
            ['name' => 'Android Developer'],
            ['name' => 'Web Developer'],
            ['name' => 'Flutter Developer'],
            ['name' => 'Content Writer'],
            ['name' => 'Graphic Designer'],
            ['name' => 'UI/UX Designer'],
            ['name' => 'Game Developer'],
            ['name' => 'Software Developer'],
        ];

        foreach ($roles as $role) {
            EmployeeRole::create($role);
        }
    }
}

