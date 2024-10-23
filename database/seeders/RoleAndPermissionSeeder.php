<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Clear cached permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define permissions
        $permissions = [

            // Only admins can
            'category-CRUD',
            'create-CRUD',
            'create-CRUD',
            'create-CRUD',
            'create-CRUD',


            // All users can
            'application-apply',
            'project-CRUD',
            'create-order-detail', 'read-order-detail', 'update-order-detail', 'delete-order-detail',

        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Define roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        // Assign permissions to roles
        // Admin gets all permissions
        $adminPermissions = Permission::all();
        $adminRole->syncPermissions($adminPermissions);

        // Users get permissions related to applications, projects, and order details
        $userPermissions = Permission::whereIn('name', [
            'create-application', 'read-application', 'update-application', 'delete-application',
            'create-project', 'read-project', 'update-project', 'delete-project',
            'create-order-detail', 'read-order-detail', 'update-order-detail', 'delete-order-detail'
        ])->get();
        $userRole->syncPermissions($userPermissions);

        // Seed example users
        User::factory(10)->create(); // Creating 10 users with default values

        // Create a specific admin user
        $adminUser = User::factory()->create([
            'name' => 'ibrahim',
            'email' => 'a@a.a',
            'password' => Hash::make('a'),
        ]);
        $adminUser->assignRole('admin');

        // Optionally assign 'user' role to the admin user if needed
        // $adminUser->assignRole('user'); // Uncomment if the admin should also have user permissions
    }
}
