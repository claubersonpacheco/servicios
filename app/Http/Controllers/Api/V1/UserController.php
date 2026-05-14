<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\UserRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class UserController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        abort_unless(Auth::user()?->can('users.view'), 403);

        $sortBy = $request->string('sort_by', 'created_at')->toString();
        abort_unless(in_array($sortBy, ['name', 'email', 'created_at'], true), 422);

        $users = User::query()
            ->with('roles')
            ->where('id', '!=', Auth::id())
            ->when(! Auth::user()?->isMaster(), fn (Builder $query) => $query->whereDoesntHave('roles', fn (Builder $q) => $q->where('name', 'master')))
            ->when($request->filled('search'), function (Builder $query) use ($request): void {
                $term = '%'.trim($request->string('search')->toString()).'%';

                $query->where(fn (Builder $builder) => $builder->where('name', 'like', $term)->orWhere('email', 'like', $term));
            })
            ->orderBy($sortBy, $request->string('sort_direction')->toString() === 'asc' ? 'asc' : 'desc')
            ->paginate(min(max((int) $request->integer('per_page', 15), 1), 100));

        return UserResource::collection($users);
    }

    public function store(UserRequest $request): JsonResponse
    {
        abort_unless(Auth::user()?->can('users.manage'), 403);

        $payload = $request->safe()->except(['role_ids', 'password_confirmation']);
        $user = User::query()->create($payload);

        $this->syncRoles($user, $request->validated('role_ids', []));

        return (new UserResource($user->load('roles')))
            ->response()
            ->setStatusCode(201);
    }

    public function show(User $user): UserResource
    {
        abort_unless(Auth::user()?->can('users.view'), 403);
        abort_if($user->isMaster() && ! Auth::user()?->isMaster(), 403);

        return new UserResource($user->load('roles'));
    }

    public function update(UserRequest $request, User $user): UserResource
    {
        abort_unless(Auth::user()?->can('users.manage'), 403);
        abort_if($user->id === Auth::id(), 403);
        abort_if($user->isMaster() && ! Auth::user()?->isMaster(), 403);

        $payload = $request->safe()->except(['role_ids', 'password_confirmation']);

        if (blank($payload['password'] ?? null)) {
            unset($payload['password']);
        }

        $user->update($payload);

        if ($request->has('role_ids')) {
            $this->syncRoles($user, $request->validated('role_ids', []));
        }

        return new UserResource($user->refresh()->load('roles'));
    }

    public function destroy(User $user): JsonResponse
    {
        abort_unless(Auth::user()?->can('users.manage'), 403);
        abort_if($user->id === Auth::id(), 403);
        abort_if($user->isMaster() && ! Auth::user()?->isMaster(), 403);

        $user->delete();

        return response()->json(['message' => 'Usuario excluido com sucesso.']);
    }

    private function syncRoles(User $user, array $roleIds): void
    {
        $roles = Role::query()
            ->whereIn('id', array_map('intval', $roleIds))
            ->when(! Auth::user()?->isMaster(), fn (Builder $query) => $query->where('name', '!=', 'master'))
            ->get();

        $user->syncRoles($roles);
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
