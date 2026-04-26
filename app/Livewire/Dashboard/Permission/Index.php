<?php

namespace App\Livewire\Dashboard\Permission;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

#[Title('Permissions')]
class Index extends Component
{
    use WithPagination;

    public int $quantity = 10;

    public string $search = '';

    public string $sortBy = 'created_at';

    public string $sortDirection = 'desc';

    public ?int $editingPermissionId = null;

    public string $name = '';

    public array $role_ids = [];

    public bool $showFormModal = false;

    public bool $showDeleteModal = false;

    public ?int $deletingPermissionId = null;

    public string $deletingPermissionName = '';

    public function mount(): void
    {
        abort_unless(Auth::user()?->can('permissions.view'), 403);
    }

    #[Computed]
    public function rows(): LengthAwarePaginator
    {
        return Permission::query()
            ->with('roles')
            ->withCount('roles')
            ->when(
                filled(trim($this->search)),
                fn(Builder $query) => $query->where('name', 'like', '%' . trim($this->search) . '%')
            )
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->quantity)
            ->withQueryString();
    }

    #[Computed]
    public function roles(): Collection
    {
        return Role::query()
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    #[Computed]
    public function totalPermissions(): int
    {
        return Permission::query()->count();
    }

    #[Computed]
    public function totalRoles(): int
    {
        return Role::query()->count();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedQuantity(): void
    {
        $this->resetPage();
    }

    public function sort(string $column): void
    {
        abort_unless(in_array($column, ['name', 'created_at'], true), 404);

        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';

            return;
        }

        $this->sortBy = $column;
        $this->sortDirection = 'asc';
    }

    public function create(): void
    {
        $this->authorizeManage();
        $this->resetForm();
        $this->showFormModal = true;
    }

    public function edit(int $permissionId): void
    {
        $this->authorizeManage();
        $permission = Permission::query()
            ->with('roles:id')
            ->findOrFail($permissionId);

        $this->resetErrorBag();
        $this->resetValidation();

        $this->editingPermissionId = $permission->id;
        $this->name = $permission->name;
        $this->role_ids = $permission->roles->pluck('id')->map(fn($id) => (string) $id)->all();
        $this->showFormModal = true;
    }

    public function save(): void
    {
        $this->authorizeManage();
        $validated = $this->validate($this->rules(), [], [
            'name' => 'nome',
            'role_ids' => 'roles',
        ]);

        if ($this->editingPermissionId) {
            $permission = Permission::query()->findOrFail($this->editingPermissionId);
            $permission->update([
                'name' => $validated['name'],
                'guard_name' => 'web',
            ]);
            $message = 'Permission atualizada com sucesso.';
        } else {
            $permission = Permission::query()->create([
                'name' => $validated['name'],
                'guard_name' => 'web',
            ]);
            $message = 'Permission criada com sucesso.';
        }

        $roles = Role::whereIn(
            'id',
            array_map('intval', $validated['role_ids'] ?? [])
        )->get();



        $permission->syncRoles($roles);
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        session()->flash('status', $message);

        $this->closeFormModal();
        $this->resetPage();
    }

    public function confirmDelete(int $permissionId): void
    {
        $this->authorizeManage();
        $permission = Permission::query()->findOrFail($permissionId);

        $this->deletingPermissionId = $permission->id;
        $this->deletingPermissionName = $permission->name;
        $this->showDeleteModal = true;
    }

    public function destroy(): void
    {
        $this->authorizeManage();
        Permission::query()->findOrFail($this->deletingPermissionId)->delete();
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        session()->flash('status', 'Permission excluida com sucesso.');

        $this->closeDeleteModal();
        $this->resetPage();
    }

    public function closeFormModal(): void
    {
        $this->showFormModal = false;
        $this->resetForm();
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->deletingPermissionId = null;
        $this->deletingPermissionName = '';
    }

    public function render()
    {
        return view('livewire.dashboard.permission.index');
    }

    protected function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:2',
                'max:255',
                Rule::unique('permissions', 'name')
                    ->where(fn($query) => $query->where('guard_name', 'web'))
                    ->ignore($this->editingPermissionId),
            ],
            'role_ids' => ['array'],
            'role_ids.*' => ['integer', 'exists:roles,id'],
        ];
    }

    protected function resetForm(): void
    {
        $this->resetErrorBag();
        $this->resetValidation();

        $this->editingPermissionId = null;
        $this->name = '';
        $this->role_ids = [];
    }

    protected function authorizeManage(): void
    {
        abort_unless(Auth::user()?->can('permissions.manage'), 403);
    }
}
