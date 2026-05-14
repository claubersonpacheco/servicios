<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\PermissionRequest;
use App\Http\Resources\Api\V1\PermissionResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        abort_unless(Auth::user()?->can('permissions.view'), 403);

        $sortBy = $request->string('sort_by', 'created_at')->toString();
        abort_unless(in_array($sortBy, ['name', 'created_at'], true), 422);

        $permissions = Permission::query()
            ->with('roles')
            ->withCount('roles')
            ->when($request->filled('search'), fn (Builder $query) => $query->where('name', 'like', '%'.trim($request->string('search')->toString()).'%'))
            ->orderBy($sortBy, $request->string('sort_direction')->toString() === 'asc' ? 'asc' : 'desc')
            ->paginate(min(max((int) $request->integer('per_page', 15), 1), 100));

        return PermissionResource::collection($permissions);
    }

    public function store(PermissionRequest $request): JsonResponse
    {
        abort_unless(Auth::user()?->can('permissions.manage'), 403);

        $permission = Permission::query()->create([
            'name' => $request->validated('name'),
            'guard_name' => 'web',
        ]);

        $this->syncRoles($permission, $request->validated('role_ids', []));

        return (new PermissionResource($permission->load('roles')))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Permission $permission): PermissionResource
    {
        abort_unless(Auth::user()?->can('permissions.view'), 403);

        return new PermissionResource($permission->load('roles'));
    }

    public function update(PermissionRequest $request, Permission $permission): PermissionResource
    {
        abort_unless(Auth::user()?->can('permissions.manage'), 403);

        if ($request->has('name')) {
            $permission->update([
                'name' => $request->validated('name'),
                'guard_name' => 'web',
            ]);
        }

        if ($request->has('role_ids')) {
            $this->syncRoles($permission, $request->validated('role_ids', []));
        }

        return new PermissionResource($permission->refresh()->load('roles'));
    }

    public function destroy(Permission $permission): JsonResponse
    {
        abort_unless(Auth::user()?->can('permissions.manage'), 403);

        $permission->delete();
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return response()->json(['message' => 'Permission excluida com sucesso.']);
    }

    private function syncRoles(Permission $permission, array $roleIds): void
    {
        $roles = Role::query()
            ->whereIn('id', array_map('intval', $roleIds))
            ->get();

        $permission->syncRoles($roles);
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
