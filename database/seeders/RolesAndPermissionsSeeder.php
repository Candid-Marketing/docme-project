<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Create permissions
        $permissions = [
            'can.edit.profile',
            'can.view.dashboard',
            'can.manage.users',
            'can.create.content',
            'can.edit.content',
            'can.delete.content',
            'can.view.reports',
            'can.manage.settings',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles
        $roles = [
            'super_admin',
            'admin',
            'user',
        ];

        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }

        // Assign permissions to roles
        $superAdmin = Role::where('name', 'super_admin')->first();
        $admin = Role::where('name', 'admin')->first();
        $user = Role::where('name', 'user')->first();

        // Super Admin has all permissions
        $superAdmin->permissions()->attach(Permission::all());

        // Admin has specific permissions
        $admin->permissions()->attach([
            Permission::where('name', 'can.view.dashboard')->first()->id,
            Permission::where('name', 'can.manage.users')->first()->id,
            Permission::where('name', 'can.create.content')->first()->id,
            Permission::where('name', 'can.edit.content')->first()->id,
            Permission::where('name', 'can.delete.content')->first()->id,
            Permission::where('name', 'can.view.reports')->first()->id,
        ]);

        // User has limited permissions
        $user->permissions()->attach([
            Permission::where('name', 'can.view.dashboard')->first()->id,
            Permission::where('name', 'can.create.content')->first()->id,
        ]);
    }
}
