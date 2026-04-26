<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

test('guests are redirected to the login page', function () {
    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('login'));
});

test('authenticated users can visit the dashboard', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('dashboard'));
    $response->assertOk();
});

test('authenticated users without permission cannot visit users page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('users.index'))
        ->assertForbidden();
});

test('authenticated users with permission can visit users page', function () {
    $user = User::factory()->create();
    Permission::query()->create(['name' => 'users.view', 'guard_name' => 'web']);
    $user->givePermissionTo('users.view');

    $this->actingAs($user)
        ->get(route('users.index'))
        ->assertOk();
});
