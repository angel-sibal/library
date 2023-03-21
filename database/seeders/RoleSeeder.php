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

        Permission::create(['name' => 'view book']);
        Permission::create(['name' => 'add book']);
        Permission::create(['name' => 'edit book']);
        Permission::create(['name' => 'delete book']);
        Permission::create(['name' => 'borrow book']);
        Permission::create(['name' => 'approve book request']);
        Permission::create(['name' => 'deny book request']);

        Role::create(['name' => 'super-admin']);
        Role::create(['name' => 'admin'])
            ->givePermissionTo(['view book', 'approve book request', 'deny book request']);
        Role::create(['name' => 'user'])
            ->givePermissionTo(['view book', 'borrow book']);
    }
}
