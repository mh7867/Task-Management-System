<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Muhammad Hamza',
            'email' => 'hanzala.sispn@gmail.com',
            'password' => bcrypt('password'),
            'is_admin' => 1,
            'employee_role_id' => 1,
        ]);
    }
}
