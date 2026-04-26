<?php

use App\Livewire\Dashboard\User\Index;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

test('users can be created and updated with roles', function () {
    $authenticated = User::factory()->create();
    Permission::query()->create(['name' => 'users.view', 'guard_name' => 'web']);
    Permission::query()->create(['name' => 'users.manage', 'guard_name' => 'web']);
    $adminRole = Role::query()->create(['name' => 'admin', 'guard_name' => 'web']);
    $managerRole = Role::query()->create(['name' => 'manager', 'guard_name' => 'web']);
    $authenticated->givePermissionTo(['users.view', 'users.manage']);

    $this->actingAs($authenticated);

    Livewire::test(Index::class)
        ->call('create')
        ->set('name', 'Maria Silva')
        ->set('email', 'maria@example.com')
        ->set('password', 'password123')
        ->set('password_confirmation', 'password123')
        ->set('role_ids', [$adminRole->id])
        ->call('save')
        ->assertHasNoErrors();

    $user = User::query()->where('email', 'maria@example.com')->first();

    expect($user)->not->toBeNull();
    expect($user->hasRole('admin'))->toBeTrue();

    Livewire::test(Index::class)
        ->call('edit', $user->id)
        ->set('role_ids', [$managerRole->id])
        ->call('save')
        ->assertHasNoErrors();

    expect($user->fresh()->hasRole('manager'))->toBeTrue();
    expect($user->fresh()->hasRole('admin'))->toBeFalse();
});
