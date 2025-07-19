<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'employee',
            'email' => 'employee@gmail.com',
            'password' => bcrypt('password')
        ]);

        $user->assignRole('employee');

        Employee::create([
            'employee_id' => "1",
            'departement_id' => "1",
            'name' => 'employee',
            'address' => 'Indonesia',
            'user_id' => $user->id
        ]);
    }
}
