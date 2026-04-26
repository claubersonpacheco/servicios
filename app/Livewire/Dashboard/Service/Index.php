<?php

namespace App\Livewire\Dashboard\Service;

use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Servicios')]
class Index extends Component
{
    use WithPagination;

    public int $quantity = 10;

    public string $search = '';

    public string $sortBy = 'created_at';

    public string $sortDirection = 'desc';

    public ?int $editingServiceId = null;

    public ?int $user_id = null;

    public string $code = '';

    public string $address = '';

    public string $postal = '';

    public string $description = '';

    public string $status = 'abierto';

    public string $date_start = '';

    public string $date_end = '';

    public string $hour_start = '';

    public string $hour_end = '';

    public bool $showFormModal = false;

    public bool $showDeleteModal = false;

    public ?int $deletingServiceId = null;

    public string $deletingServiceCode = '';

    public function mount(): void
    {
        abort_unless(Auth::user()?->can('services.view'), 403);
    }

    #[Computed]
    public function rows(): LengthAwarePaginator
    {
        return Service::query()
            ->with('user')
            ->when(
                filled(trim($this->search)),
                fn (Builder $query) => $query->where(function (Builder $builder): void {
                    $term = '%' . trim($this->search) . '%';

                    $builder
                        ->where('code', 'like', $term)
                        ->orWhere('address', 'like', $term)
                        ->orWhere('postal', 'like', $term)
                        ->orWhere('description', 'like', $term)
                        ->orWhereHas('user', fn (Builder $userQuery) => $userQuery->where('name', 'like', $term));
                })
            )
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->quantity)
            ->withQueryString();
    }

    #[Computed]
    public function users(): Collection
    {
        return User::query()
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    #[Computed]
    public function totalServices(): int
    {
        return Service::query()->count();
    }

    #[Computed]
    public function openServices(): int
    {
        return Service::query()
            ->where('status', 'abierto')
            ->count();
    }

    #[Computed]
    public function closedServices(): int
    {
        return Service::query()
            ->where('status', 'cerrado')
            ->count();
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
        abort_unless(in_array($column, ['code', 'status', 'date_start', 'created_at'], true), 404);

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

    public function edit(int $serviceId): void
    {
        $this->authorizeManage();
        $service = Service::query()->findOrFail($serviceId);

        $this->resetErrorBag();
        $this->resetValidation();

        $this->editingServiceId = $service->id;
        $this->user_id = $service->user_id;
        $this->code = $service->code;
        $this->address = $service->address ?? '';
        $this->postal = $service->postal ?? '';
        $this->description = $service->description ?? '';
        $this->status = $service->status;
        $this->date_start = $service->date_start?->format('Y-m-d') ?? '';
        $this->date_end = $service->date_end?->format('Y-m-d') ?? '';
        $this->hour_start = $service->hour_start?->format('H:i') ?? '';
        $this->hour_end = $service->hour_end?->format('H:i') ?? '';
        $this->showFormModal = true;
    }

    public function save(): void
    {
        $this->authorizeManage();
        $validated = $this->validate($this->rules(), [], [
            'user_id' => 'responsavel',
            'code' => 'codigo',
            'address' => 'endereco',
            'postal' => 'codigo postal',
            'description' => 'descricao',
            'status' => 'status',
            'date_start' => 'data inicial',
            'date_end' => 'data final',
            'hour_start' => 'hora inicial',
            'hour_end' => 'hora final',
        ]);

        $payload = [
            'user_id' => $validated['user_id'],
            'code' => $validated['code'],
            'address' => $validated['address'] ?: null,
            'postal' => $validated['postal'] ?: null,
            'description' => $validated['description'] ?: null,
            'status' => $validated['status'],
            'date_start' => $validated['date_start'] ?: null,
            'date_end' => $validated['date_end'] ?: null,
            'hour_start' => $validated['hour_start'] ?: null,
            'hour_end' => $validated['hour_end'] ?: null,
        ];

        if ($this->editingServiceId) {
            Service::query()->findOrFail($this->editingServiceId)->update($payload);
            session()->flash('status', 'Servicio actualizado com sucesso.');
        } else {
            Service::query()->create($payload);
            session()->flash('status', 'Servicio criado com sucesso.');
        }

        $this->closeFormModal();
        $this->resetPage();
    }

    public function confirmDelete(int $serviceId): void
    {
        $this->authorizeManage();
        $service = Service::query()->findOrFail($serviceId);

        $this->deletingServiceId = $service->id;
        $this->deletingServiceCode = $service->code;
        $this->showDeleteModal = true;
    }

    public function destroy(): void
    {
        $this->authorizeManage();
        Service::query()->findOrFail($this->deletingServiceId)->delete();

        session()->flash('status', 'Servicio excluido com sucesso.');

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
        $this->deletingServiceId = null;
        $this->deletingServiceCode = '';
    }

    public function render()
    {
        return view('livewire.dashboard.service.index');
    }

    protected function rules(): array
    {
        return [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'code' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Service::class, 'code')->ignore($this->editingServiceId),
            ],
            'address' => ['nullable', 'string', 'max:255'],
            'postal' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['abierto', 'en_andamiento', 'cerrado'])],
            'date_start' => ['nullable', 'date'],
            'date_end' => ['nullable', 'date', 'after_or_equal:date_start'],
            'hour_start' => ['nullable', 'date_format:H:i'],
            'hour_end' => ['nullable', 'date_format:H:i'],
        ];
    }

    protected function resetForm(): void
    {
        $this->resetErrorBag();
        $this->resetValidation();

        $this->editingServiceId = null;
        $this->user_id = null;
        $this->code = '';
        $this->address = '';
        $this->postal = '';
        $this->description = '';
        $this->status = 'abierto';
        $this->date_start = '';
        $this->date_end = '';
        $this->hour_start = '';
        $this->hour_end = '';
    }

    protected function authorizeManage(): void
    {
        abort_unless(Auth::user()?->can('services.manage'), 403);
    }
}
