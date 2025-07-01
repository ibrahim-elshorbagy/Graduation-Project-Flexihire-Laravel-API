<?php

namespace Database\Seeders;

use App\Models\JobList;
use App\Models\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // $this->call(RoleAndPermissionSeeder::class);
        $this->call(SkillsJobsSeeder::class);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole = Role::firstOrCreate(['name' => 'user']);
        $companyRole = Role::firstOrCreate(['name' => 'company']);

        User::factory()->create([
            'first_name' => 'Admin',
            'last_name' => 'ibrahim',
            'email' => 'a@a.a',
            'password' => Hash::make('asdasdasd'),
        ])->assignRole($adminRole);

        User::factory()->create([
            'first_name' => 'User',
            'last_name' => 'ahmed',
            'email' => 'u@u.u',
            'password' => Hash::make('asdasdasd'),
        ])->assignRole($userRole);

        User::factory()->create([
            'first_name' => 'Company',
            'last_name' => 'Mohmed',
            'email' => 'c@c.c',
            'password' => Hash::make('asdasdasd'),
        ])->assignRole($companyRole);
        
        // Call the comprehensive seeder to create companies, jobs, and users
        $this->call(CompanyJobUserSeeder::class);


    }
}
