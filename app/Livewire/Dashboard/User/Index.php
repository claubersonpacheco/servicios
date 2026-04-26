<?php

namespace App\Livewire\Dashboard\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class Index extends Component
{
    use WithPagination;

    public int $quantity = 10;

    public string $search = '';

    public string $sortBy = 'created_at';

    public string $sortDirection = 'desc';

    public ?int $editingUserId = null;

    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public array $role_ids = [];

    public bool $showFormModal = false;

    public bool $showDeleteModal = false;

    public ?int $deletingUserId = null;

    public string $deletingUserName = '';

    public function mount(): void
    {
        abort_unless(Auth::user()?->can('users.view'), 403);
    }

    #[Computed]
    public function rows(): LengthAwarePaginator
    {
        return User::query()
            ->with('roles')
            ->where('id', '!=', Auth::id())
            ->when(
                filled(trim($this->search)),
                fn(Builder $query) => $query->where(function (Builder $builder): void {
                    $term = '%' . trim($this->search) . '%';

                    $builder
                        ->where('name', 'like', $term)
                        ->orWhere('email', 'like', $term);
                })
            )
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->quantity)
            ->withQueryString();
    }

    #[Computed]
    public function totalUsers(): int
    {
        return User::query()
            ->where('id', '!=', Auth::id())
            ->count();
    }

    #[Computed]
    public function roles(): Collection
    {
        return Role::query()
            ->orderBy('name')
            ->get(['id', 'name']);
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

    public function edit(int $userId): void
    {
        $this->authorizeManage();
        $user = User::query()
            ->where('id', '!=', Auth::id())
            ->findOrFail($userId);

        $this->resetErrorBag();
        $this->resetValidation();

        $this->editingUserId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->password = '';
        $this->password_confirmation = '';
        $this->role_ids = $user->roles->pluck('id')->map(fn($id) => (string) $id)->all();
        $this->showFormModal = true;
    }

    public function save(): void
    {
        $this->authorizeManage();
        $validated = $this->validate($this->rules(), [], [
            'name' => 'nome',
            'email' => 'e-mail',
            'password' => 'senha',
            'password_confirmation' => 'confirmação de senha',
            'role_ids' => 'roles',
        ]);

        $payload = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        if ($this->editingUserId) {
            $user = User::query()
                ->where('id', '!=', Auth::id())
                ->findOrFail($this->editingUserId);

            if (filled($validated['password'] ?? '')) {
                $payload['password'] = $validated['password'];
            }

            $user->update($payload);

            $roles = Role::whereIn(
                'id',
                array_map('intval', $validated['role_ids'] ?? [])
            )->get();

            $user->syncRoles($roles);
            session()->flash('status', 'Usuário atualizado com sucesso.');
        } else {
            $payload['password'] = $validated['password'];
            $user = User::query()->create($payload);


            $roles = Role::whereIn(
                'id',
                array_map('intval', $validated['role_ids'] ?? [])
            )->get();

            $user->syncRoles($roles);
            session()->flash('status', 'Usuário criado com sucesso.');
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->closeFormModal();
        $this->resetPage();
    }

    public function confirmDelete(int $userId): void
    {
        $this->authorizeManage();
        $user = User::query()
            ->where('id', '!=', Auth::id())
            ->findOrFail($userId);

        $this->deletingUserId = $user->id;
        $this->deletingUserName = $user->name;
        $this->showDeleteModal = true;
    }

    public function destroy(): void
    {
        $this->authorizeManage();
        $user = User::query()
            ->where('id', '!=', Auth::id())
            ->findOrFail($this->deletingUserId);

        $user->delete();

        session()->flash('status', 'Usuário excluído com sucesso.');

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
        $this->deletingUserId = null;
        $this->deletingUserName = '';
    }

    public function render()
    {
        return view('livewire.dashboard.user.index');
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class, 'email')->ignore($this->editingUserId),
            ],
            'password' => $this->editingUserId
                ? ['nullable', 'string', 'min:8', 'confirmed']
                : ['required', 'string', 'min:8', 'confirmed'],
            'role_ids' => ['array'],
            'role_ids.*' => ['integer', 'exists:roles,id'],
        ];
    }

    protected function resetForm(): void
    {
        $this->resetErrorBag();
        $this->resetValidation();

        $this->editingUserId = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->role_ids = [];
    }

    protected function authorizeManage(): void
    {
        abort_unless(Auth::user()?->can('users.manage'), 403);
    }
}
