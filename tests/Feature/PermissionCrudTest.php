<?php

use App\Livewire\Dashboard\Permission\Index;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

test('authenticated users can visit the permissions page', function () {
    $user = User::factory()->create();
    Permission::query()->create(['name' => 'permissions.view', 'guard_name' => 'web']);
    $user->givePermissionTo('permissions.view');

    $this->actingAs($user)
        ->get(route('permissions.index'))
        ->assertOk()
        ->assertSee('Permissions');
});

test('permissions can be created updated and deleted', function () {
    $user = User::factory()->create();
    Permission::query()->create(['name' => 'permissions.view', 'guard_name' => 'web']);
    Permission::query()->create(['name' => 'permissions.manage', 'guard_name' => 'web']);
    $roleA = Role::query()->create(['name' => 'admin', 'guard_name' => 'web']);
    $roleB = Role::query()->create(['name' => 'editor', 'guard_name' => 'web']);
    $user->givePermissionTo(['permissions.view', 'permissions.manage']);

    $this->actingAs($user);

    Livewire::test(Index::class)
        ->call('create')
        ->set('name', 'services.view')
        ->set('role_ids', [$roleA->id])
        ->call('save')
        ->assertHasNoErrors();

    $permission = Permission::query()->where('name', 'services.view')->first();

    expect($permission)->not->toBeNull();
    expect($roleA->fresh()->hasPermissionTo('services.view'))->toBeTrue();

    Livewire::test(Index::class)
        ->call('edit', $permission->id)
        ->set('name', 'services.manage')
        ->set('role_ids', [$roleA->id, $roleB->id])
        ->call('save')
        ->assertHasNoErrors();

    expect($permission->fresh()->name)->toBe('services.manage');
    expect($roleB->fresh()->hasPermissionTo('services.manage'))->toBeTrue();

    Livewire::test(Index::class)
        ->call('confirmDelete', $permission->id)
        ->call('destroy')
        ->assertHasNoErrors();

    $this->assertDatabaseMissing('permissions', ['id' => $permission->id]);
});
