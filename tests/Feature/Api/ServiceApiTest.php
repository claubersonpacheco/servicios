<?php

use App\Enums\AdressType;
use App\Enums\Status;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

function apiTokenFor(User $user): string
{
    return test()->postJson('/api/v1/auth/login', [
        'email' => $user->email,
        'password' => 'password',
        'device_name' => 'tests',
    ])->assertOk()->json('access_token');
}

test('api users can authenticate and read their profile', function () {
    $user = User::factory()->create(['password' => 'password']);

    $token = apiTokenFor($user);

    $this->withToken($token)
        ->getJson('/api/v1/auth/me')
        ->assertOk()
        ->assertJsonPath('data.email', $user->email);
});

test('api services can be created listed updated and deleted', function () {
    $user = User::factory()->create(['password' => 'password']);
    $responsible = User::factory()->create();

    foreach (['services.view', 'services.view.all', 'services.manage'] as $permission) {
        Permission::query()->create(['name' => $permission, 'guard_name' => 'web']);
    }

    $user->givePermissionTo(['services.view', 'services.view.all', 'services.manage']);

    $token = apiTokenFor($user);

    $serviceId = $this->withToken($token)
        ->postJson('/api/v1/services', [
            'user_id' => $responsible->id,
            'code' => 'API-100',
            'address_type' => AdressType::CALLE->value,
            'address' => 'Central',
            'number' => '123',
            'city' => 'Madrid',
            'status' => Status::ABIERTO->value,
            'date_start' => '2026-05-14',
            'date_end' => '2026-05-15',
            'hour_start' => '08:00',
            'hour_end' => '12:00',
        ])
        ->assertCreated()
        ->assertJsonPath('data.code', 'API-100')
        ->json('data.id');

    $this->withToken($token)
        ->getJson('/api/v1/services?search=API-100')
        ->assertOk()
        ->assertJsonPath('data.0.id', $serviceId);

    $this->withToken($token)
        ->patchJson("/api/v1/services/{$serviceId}", [
            'status' => Status::FINALIZADO->value,
            'description' => 'Finalizado pelo app',
        ])
        ->assertOk()
        ->assertJsonPath('data.status.value', Status::FINALIZADO->value);

    $this->withToken($token)
        ->deleteJson("/api/v1/services/{$serviceId}")
        ->assertOk();

    expect(Service::query()->find($serviceId))->toBeNull();
});

test('api service images can be uploaded listed and deleted', function () {
    Storage::fake('public');

    $user = User::factory()->create(['password' => 'password']);
    $service = Service::factory()->create(['user_id' => $user->id]);

    foreach (['services.view', 'services.manage'] as $permission) {
        Permission::query()->create(['name' => $permission, 'guard_name' => 'web']);
    }

    $user->givePermissionTo(['services.view', 'services.manage']);

    $token = apiTokenFor($user);

    $image = $this->withToken($token)
        ->postJson("/api/v1/services/{$service->id}/images", [
            'image' => UploadedFile::fake()->image('service.jpg'),
        ])
        ->assertCreated()
        ->assertJsonPath('data.service_id', $service->id);

    $imageId = $image->json('data.id');
    $imagePath = $image->json('data.path');

    Storage::disk('public')->assertExists($imagePath);

    $this->withToken($token)
        ->getJson("/api/v1/services/{$service->id}/images")
        ->assertOk()
        ->assertJsonPath('data.0.id', $imageId);

    $this->withToken($token)
        ->deleteJson("/api/v1/services/{$service->id}/images/{$imageId}")
        ->assertOk();

    Storage::disk('public')->assertMissing($imagePath);
});
