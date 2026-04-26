<?php

use App\Livewire\Dashboard\Permission\Index as PermissionIndex;
use App\Livewire\Dashboard\Role\Index as RoleIndex;
use Illuminate\Support\Facades\Route;
use App\Livewire\Dashboard\User\Index as UserIndex;
use App\Livewire\Dashboard\Service\Index as ServiceIndex;
use App\Livewire\Front\Pages\Cookie;
use App\Livewire\Front\Pages\Index;
use App\Livewire\Front\Pages\Privacy;

Route::livewire('/cookie', Cookie::class)->name('cookie');
Route::livewire('/privacy', Privacy::class)->name('privacy');
Route::livewire('/', Index::class)->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::livewire('/services', ServiceIndex::class)
        ->middleware('permission:services.view')
        ->name('services.index');

    Route::livewire('/users', UserIndex::class)
        ->middleware('permission:users.view')
        ->name('users.index');

    Route::livewire('/roles', RoleIndex::class)
        ->middleware('permission:roles.view')
        ->name('roles.index');

    Route::livewire('/permissions', PermissionIndex::class)
        ->middleware('permission:permissions.view')
        ->name('permissions.index');
});

require __DIR__ . '/settings.php';
