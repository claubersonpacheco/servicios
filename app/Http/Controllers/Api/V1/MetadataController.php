<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\AdressType;
use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\RoleResource;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class MetadataController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $user = Auth::user();

        return response()->json([
            'service_statuses' => Status::options(),
            'address_types' => AdressType::options(),
            'users' => $user?->can('users.view')
                ? UserResource::collection(User::query()->orderBy('name')->get(['id', 'name', 'email', 'created_at', 'updated_at']))
                : [],
            'roles' => $user?->can('roles.view')
                ? RoleResource::collection(Role::query()->where('name', '!=', 'master')->orderBy('name')->get())
                : [],
            'permissions' => $user?->can('permissions.view')
                ? Permission::query()->orderBy('name')->get(['id', 'name'])
                : [],
        ]);
    }
}
