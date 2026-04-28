<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = collect([
            'users.view',
            'users.manage',
            'services.view',
            'services.view.all',
            'services.manage',
            'roles.view',
            'roles.manage',
            'permissions.view',
            'permissions.manage',
        ])->map(fn(string $permission) => Permission::query()->firstOrCreate([
            'name' => $permission,
            'guard_name' => 'web',
        ]));

        $adminRole = Role::query()->firstOrCreate([
            'name' => 'master',
            'guard_name' => 'web',
        ]);
        $adminRole->syncPermissions($permissions);

        // User::factory(10)->create();

        $user = User::query()->firstOrCreate(
            ['email' => 'master@admin.com'],
            ['name' => 'Master', 'password' => 'password']
        );

        $user->assignRole($adminRole);
    }
}
