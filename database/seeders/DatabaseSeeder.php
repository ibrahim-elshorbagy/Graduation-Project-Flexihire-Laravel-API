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
            'password' => Hash::make('a'),
        ])->assignRole($adminRole);

        User::factory()->create([
            'first_name' => 'User',
            'last_name' => 'ahmed',
            'email' => 'u@u.u',
            'password' => Hash::make('u'),
        ])->assignRole($userRole);

        User::factory()->create([
            'first_name' => 'Company',
            'last_name' => 'Mohmed',
            'email' => 'c@c.c',
            'password' => Hash::make('c'),
        ])->assignRole($companyRole);

        User::factory()->count(25)->userRole()->create();
        User::factory()
            ->count(25)
            ->companyRole()
            ->afterCreating(function ($user) {
                JobList::factory()
                    ->count(3)
                    ->create([
                        'user_id' => $user->id
                    ]);
            })
            ->create();


    }
}
