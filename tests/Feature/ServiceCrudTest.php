<?php

use App\Enums\AdressType;
use App\Enums\Status;
use App\Livewire\Dashboard\Home\Index as HomeIndex;
use App\Livewire\Dashboard\Service\Index;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

test('authenticated users can visit the services page', function () {
    $user = User::factory()->create();
    Permission::query()->create(['name' => 'services.view', 'guard_name' => 'web']);
    $user->givePermissionTo('services.view');

    $this->actingAs($user)
        ->get(route('services.index'))
        ->assertOk()
        ->assertSee('Servicios');
});

test('services can be created updated and deleted', function () {
    $user = User::factory()->create();
    $responsible = User::factory()->create();
    Role::query()->create(['name' => 'admin', 'guard_name' => 'web']);
    Permission::query()->create(['name' => 'services.view', 'guard_name' => 'web']);
    Permission::query()->create(['name' => 'services.manage', 'guard_name' => 'web']);
    $user->givePermissionTo(['services.view', 'services.manage']);

    $this->actingAs($user);

    Livewire::test(Index::class)
        ->call('create')
        ->set('user_id', $responsible->id)
        ->set('code', 'SRV-100')
        ->set('address_type', AdressType::CALLE->value)
        ->set('address', 'Central')
        ->set('number', '123')
        ->set('complement', 'Piso 2')
        ->set('city', 'Madrid')
        ->set('state', 'Madrid')
        ->set('postal', '28001')
        ->set('description', 'Instalacao inicial')
        ->set('status', Status::ABIERTO->value)
        ->set('date_start', '2026-04-26')
        ->set('date_end', '2026-04-27')
        ->set('hour_start', '08:00')
        ->set('hour_end', '12:00')
        ->call('save')
        ->assertHasNoErrors();

    $service = Service::query()->where('code', 'SRV-100')->first();

    expect($service)->not->toBeNull();
    expect($service->user_id)->toBe($responsible->id);
    expect($service->address_type)->toBe(AdressType::CALLE);
    expect($service->number)->toBe('123');
    expect($service->complement)->toBe('Piso 2');
    expect($service->city)->toBe('Madrid');
    expect($service->state)->toBe('Madrid');

    Livewire::test(Index::class)
        ->call('edit', $service->id)
        ->set('status', Status::FINALIZADO->value)
        ->set('number', '456')
        ->set('description', 'Servico finalizado')
        ->call('save')
        ->assertHasNoErrors();

    expect($service->fresh()->status)->toBe(Status::FINALIZADO);
    expect($service->fresh()->number)->toBe('456');
    expect($service->fresh()->description)->toBe('Servico finalizado');

    Livewire::test(Index::class)
        ->call('confirmDelete', $service->id)
        ->call('destroy')
        ->assertHasNoErrors();

    $this->assertDatabaseMissing('services', [
        'id' => $service->id,
    ]);
});

test('services created from dashboard home persist address number', function () {
    $user = User::factory()->create();
    $responsible = User::factory()->create();
    Permission::query()->create(['name' => 'services.manage', 'guard_name' => 'web']);
    $user->givePermissionTo('services.manage');

    $this->actingAs($user);

    Livewire::test(HomeIndex::class)
        ->call('create')
        ->set('user_id', $responsible->id)
        ->set('code', 'SRV-HOME-100')
        ->set('address_type', AdressType::CALLE->value)
        ->set('address', 'Central')
        ->set('number', '789')
        ->set('complement', 'Piso 3')
        ->set('city', 'Madrid')
        ->set('state', 'Madrid')
        ->set('postal', '28001')
        ->set('status', Status::ABIERTO->value)
        ->set('date_start', '2026-05-13')
        ->set('date_end', '2026-05-13')
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('services', [
        'code' => 'SRV-HOME-100',
        'number' => '789',
        'complement' => 'Piso 3',
        'city' => 'Madrid',
        'state' => 'Madrid',
    ]);
});
