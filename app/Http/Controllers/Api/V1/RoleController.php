<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\RoleRequest;
use App\Http\Resources\Api\V1\RoleResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        abort_unless(Auth::user()?->can('roles.view'), 403);

        $sortBy = $request->string('sort_by', 'created_at')->toString();
        abort_unless(in_array($sortBy, ['name', 'created_at'], true), 422);

        $roles = Role::query()
            ->where('name', '!=', 'master')
            ->with('permissions')
            ->withCount('permissions')
            ->when($request->filled('search'), fn (Builder $query) => $query->where('name', 'like', '%'.trim($request->string('search')->toString()).'%'))
            ->orderBy($sortBy, $request->string('sort_direction')->toString() === 'asc' ? 'asc' : 'desc')
            ->paginate(min(max((int) $request->integer('per_page', 15), 1), 100));

        return RoleResource::collection($roles);
    }

    public function store(RoleRequest $request): JsonResponse
    {
        abort_unless(Auth::user()?->can('roles.manage'), 403);

        $role = Role::query()->create([
            'name' => $request->validated('name'),
            'guard_name' => 'web',
        ]);

        $this->syncPermissions($role, $request->validated('permission_ids', []));

        return (new RoleResource($role->load('permissions')))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Role $role): RoleResource
    {
        abort_unless(Auth::user()?->can('roles.view'), 403);
        abort_if($role->name === 'master', 403);

        return new RoleResource($role->load('permissions'));
    }

    public function update(RoleRequest $request, Role $role): RoleResource
    {
        abort_unless(Auth::user()?->can('roles.manage'), 403);
        abort_if($role->name === 'master', 403);

        if ($request->has('name')) {
            $role->update([
                'name' => $request->validated('name'),
                'guard_name' => 'web',
            ]);
        }

        if ($request->has('permission_ids')) {
            $this->syncPermissions($role, $request->validated('permission_ids', []));
        }

        return new RoleResource($role->refresh()->load('permissions'));
    }

    public function destroy(Role $role): JsonResponse
    {
        abort_unless(Auth::user()?->can('roles.manage'), 403);
        abort_if($role->name === 'master', 403);

        $role->delete();
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return response()->json(['message' => 'Role excluida com sucesso.']);
    }

    private function syncPermissions(Role $role, array $permissionIds): void
    {
        $permissions = Permission::query()
            ->whereIn('id', array_map('intval', $permissionIds))
            ->get();

        $role->syncPermissions($permissions);
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
