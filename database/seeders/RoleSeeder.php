<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'View Books']);
        Permission::create(['name' => 'Create Books']);
        Permission::create(['name' => 'Edit Books']);
        Permission::create(['name' => 'Delete Books']);
        Permission::create(['name' => 'Borrow Books']);
        Permission::create(['name' => 'Approve Book Requests']);
        Permission::create(['name' => 'Deny Book Requests']);
        Permission::create(['name' => 'View Book Requests']);
        Permission::create(['name' => 'Create Book Requests']);
        Permission::create(['name' => 'Edit Book Requests']);
        Permission::create(['name' => 'Delete Book Requests']);

        Role::create(['name' => 'Super Admin']);
        Role::create(['name' => 'Admin'])
            ->givePermissionTo(['View Books', 'Approve Book Requests', 'Deny Book Requests']);
        Role::create(['name' => 'User'])
            ->givePermissionTo(['View Books', 'Borrow Books', 'View Book Requests', 'Create Book Requests', 'Edit Book Requests', 'Delete Book Requests']);
    }
}
