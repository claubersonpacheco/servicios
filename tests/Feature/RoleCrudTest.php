<?php

use App\Livewire\Dashboard\Role\Index;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

test('authenticated users can visit the roles page', function () {
    $user = User::factory()->create();
    Permission::query()->create(['name' => 'roles.view', 'guard_name' => 'web']);
    $user->givePermissionTo('roles.view');

    $this->actingAs($user)
        ->get(route('roles.index'))
        ->assertOk()
        ->assertSee('Roles');
});

test('roles can be created updated and deleted', function () {
    $user = User::factory()->create();
    Permission::query()->create(['name' => 'roles.view', 'guard_name' => 'web']);
    Permission::query()->create(['name' => 'roles.manage', 'guard_name' => 'web']);
    $permissionA = Permission::query()->create(['name' => 'users.view', 'guard_name' => 'web']);
    $permissionB = Permission::query()->create(['name' => 'users.edit', 'guard_name' => 'web']);
    $user->givePermissionTo(['roles.view', 'roles.manage']);

    $this->actingAs($user);

    Livewire::test(Index::class)
        ->call('create')
        ->set('name', 'manager')
        ->set('permission_ids', [$permissionA->id])
        ->call('save')
        ->assertHasNoErrors();

    $role = Role::query()->where('name', 'manager')->first();

    expect($role)->not->toBeNull();
    expect($role->hasPermissionTo('users.view'))->toBeTrue();

    Livewire::test(Index::class)
        ->call('edit', $role->id)
        ->set('name', 'super-manager')
        ->set('permission_ids', [$permissionA->id, $permissionB->id])
        ->call('save')
        ->assertHasNoErrors();

    expect($role->fresh()->name)->toBe('super-manager');
    expect($role->fresh()->hasPermissionTo('users.edit'))->toBeTrue();

    Livewire::test(Index::class)
        ->call('confirmDelete', $role->id)
        ->call('destroy')
        ->assertHasNoErrors();

    $this->assertDatabaseMissing('roles', ['id' => $role->id]);
});
