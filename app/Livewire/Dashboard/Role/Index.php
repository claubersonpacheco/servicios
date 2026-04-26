<?php

namespace App\Livewire\Dashboard\Role;

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

#[Title('Roles')]
class Index extends Component
{
    use WithPagination;

    public int $quantity = 10;

    public string $search = '';

    public string $sortBy = 'created_at';

    public string $sortDirection = 'desc';

    public ?int $editingRoleId = null;

    public string $name = '';

    public array $permission_ids = [];

    public bool $showFormModal = false;

    public bool $showDeleteModal = false;

    public ?int $deletingRoleId = null;

    public string $deletingRoleName = '';

    public function mount(): void
    {
        abort_unless(Auth::user()?->can('roles.view'), 403);
    }

    #[Computed]
    public function rows(): LengthAwarePaginator
    {
        return Role::query()
            ->with('permissions')
            ->withCount('permissions')
            ->when(
                filled(trim($this->search)),
                fn(Builder $query) => $query->where('name', 'like', '%' . trim($this->search) . '%')
            )
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->quantity)
            ->withQueryString();
    }

    #[Computed]
    public function permissions(): Collection
    {
        return Permission::query()
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    #[Computed]
    public function totalRoles(): int
    {
        return Role::query()->count();
    }

    #[Computed]
    public function totalPermissions(): int
    {
        return Permission::query()->count();
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

    public function edit(int $roleId): void
    {
        $this->authorizeManage();
        $role = Role::query()
            ->with('permissions:id')
            ->findOrFail($roleId);

        $this->resetErrorBag();
        $this->resetValidation();

        $this->editingRoleId = $role->id;
        $this->name = $role->name;
        $this->permission_ids = $role->permissions->pluck('id')->map(fn($id) => (string) $id)->all();
        $this->showFormModal = true;
    }

    public function save(): void
    {
        $this->authorizeManage();

        $validated = $this->validate($this->rules(), [], [
            'name' => 'nome',
            'permission_ids' => 'permissoes',
        ]);

        if ($this->editingRoleId) {
            $role = Role::query()->findOrFail($this->editingRoleId);
            $role->update([
                'name' => $validated['name'],
                'guard_name' => 'web',
            ]);
            $message = 'Role atualizada com sucesso.';
        } else {
            $role = Role::query()->create([
                'name' => $validated['name'],
                'guard_name' => 'web',
            ]);
            $message = 'Role criada com sucesso.';
        }

        $permissions = Permission::whereIn(
            'id',
            array_map('intval', $validated['permission_ids'] ?? [])
        )->get();

        $role->syncPermissions($permissions);
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        session()->flash('status', $message);

        $this->closeFormModal();
        $this->resetPage();
    }

    public function confirmDelete(int $roleId): void
    {
        $this->authorizeManage();
        $role = Role::query()->findOrFail($roleId);

        $this->deletingRoleId = $role->id;
        $this->deletingRoleName = $role->name;
        $this->showDeleteModal = true;
    }

    public function destroy(): void
    {
        $this->authorizeManage();
        Role::query()->findOrFail($this->deletingRoleId)->delete();
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        session()->flash('status', 'Role excluida com sucesso.');

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
        $this->deletingRoleId = null;
        $this->deletingRoleName = '';
    }

    public function render()
    {
        return view('livewire.dashboard.role.index');
    }

    protected function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:2',
                'max:255',
                Rule::unique('roles', 'name')
                    ->where(fn($query) => $query->where('guard_name', 'web'))
                    ->ignore($this->editingRoleId),
            ],
            'permission_ids' => ['array'],
            'permission_ids.*' => ['integer', 'exists:permissions,id'],
        ];
    }

    protected function resetForm(): void
    {
        $this->resetErrorBag();
        $this->resetValidation();

        $this->editingRoleId = null;
        $this->name = '';
        $this->permission_ids = [];
    }

    protected function authorizeManage(): void
    {
        abort_unless(Auth::user()?->can('roles.manage'), 403);
    }
}
