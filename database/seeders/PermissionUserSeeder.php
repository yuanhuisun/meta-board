<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionUserSeeder extends Seeder
{
    /**
     * Create the initial roles and permissions.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions - CRUD : Create / Read / Update / Delete , by Yuanhui at 2024/12/06
        // task / ticket level
        Permission::create(['name' => 'create tasks']);
        Permission::create(['name' => 'read tasks']);
        Permission::create(['name' => 'update tasks']);
        Permission::create(['name' => 'delete tasks']);
        // workflow level
        Permission::create(['name' => 'create workflows']);
        Permission::create(['name' => 'read workflows']);
        Permission::create(['name' => 'update workflows']);
        Permission::create(['name' => 'execute workflows']);
        Permission::create(['name' => 'delete workflows']);
        Permission::create(['name' => 'publish workflows']);
        Permission::create(['name' => 'unpublish workflows']);
        

        // create roles and assign existing permissions
        $role1 = Role::create(['name' => 'user']);
        $role1->givePermissionTo('create tasks');
        $role1->givePermissionTo('read tasks');
        $role1->givePermissionTo('update tasks');
        $role1->givePermissionTo('delete tasks');
        $role1->givePermissionTo('execute workflows');

        $role2 = Role::create(['name' => 'app-admin']);
        $role2->givePermissionTo('create workflows');
        $role2->givePermissionTo('read workflows');
        $role2->givePermissionTo('update workflows');
        $role2->givePermissionTo('execute workflows');
        $role2->givePermissionTo('delete workflows');
        $role2->givePermissionTo('publish workflows');
        $role2->givePermissionTo('unpublish workflows');

        $role3 = Role::create(['name' => 'sys-admin']);
        // gets all permissions via Gate::before rule; see AuthServiceProvider

        // create demo users
        $user = \App\Models\User::factory()->create([
            'name' => 'Example User',
            'email' => 'user@example.com',
        ]);
        $user->assignRole($role1);

        $user = \App\Models\User::factory()->create([
            'name' => 'Example App Admin User',
            'email' => 'appadmin@example.com',
        ]);
        $user->assignRole($role2);

        $user = \App\Models\User::factory()->create([
            'name' => 'Example Sys-Admin User',
            'email' => 'sysadmin@example.com',
        ]);
        $user->assignRole($role3);
    }
}
