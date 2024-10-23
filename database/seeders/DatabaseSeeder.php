<?php

namespace Database\Seeders;

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
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole = Role::firstOrCreate(['name' => 'user']);
        $userRole = Role::firstOrCreate(['name' => 'freelancer']);
        $userRole = Role::firstOrCreate(['name' => 'customer']);

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'a@a.a',
            'password' => Hash::make('a'),
        ])->assignRole($adminRole);

        User::factory()->create([
            'name' => 'User',
            'email' => 'u@u.u',
            'password' => Hash::make('u'),
        ])->assignRole($userRole);
    }
}
