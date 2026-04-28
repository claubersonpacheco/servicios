<?php

namespace App\Livewire\Dashboard\Home;

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
use App\Enums\Status;

#[Title('Portada')]
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
    public int $status = Status::ABIERTO->value;

    public string $date_start = '';
    public string $date_end = '';
    public string $hour_start = '';
    public string $hour_end = '';

    public bool $showFormModal = false;
    public bool $showDeleteModal = false;

    public ?int $deletingServiceId = null;
    public string $deletingServiceCode = '';


    /* =========================
        LISTAGEM
    ========================= */
    #[Computed]
    public function rows(): LengthAwarePaginator
    {
        $date = now()->toDateString();

        return Service::query()
            ->with('user')
            ->visibleFor(Auth::user())
            ->whereDate('date_start', $date)
            ->when(
                filled($this->search),
                fn($query) => $query->where(function (Builder $builder) {
                    $term = "%{$this->search}%";

                    $builder
                        ->where('code', 'like', $term)
                        ->orWhere('address', 'like', $term)
                        ->orWhere('postal', 'like', $term)
                        ->orWhere('description', 'like', $term)
                        ->orWhereHas(
                            'user',
                            fn($q) => $q->where('name', 'like', $term)
                        );
                })
            )

            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->quantity)
            ->withQueryString();
    }

    /* =========================
        USERS
    ========================= */
    #[Computed]
    public function users(): Collection
    {
        return User::query()
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    /* =========================
        STATS (SCOPED)
    ========================= */
    #[Computed]
    public function totalServices(): int
    {
        return Service::visibleFor(Auth::user())->count();
    }

    #[Computed]
    public function openServices(): int
    {
        return Service::visibleFor(Auth::user())
            ->where('status', Status::ABIERTO->value) // ✅ FIX
            ->count();
    }

    #[Computed]
    public function closedServices(): int
    {
        return Service::visibleFor(Auth::user())
            ->where('status', Status::FINALIZADO->value) // ✅ FIX
            ->count();
    }

    /* =========================
        SORT / SEARCH
    ========================= */
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
        abort_unless(
            in_array($column, ['code', 'status', 'date_start', 'created_at'], true),
            404
        );

        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
            return;
        }

        $this->sortBy = $column;
        $this->sortDirection = 'asc';
    }

    /* =========================
        CREATE
    ========================= */
    public function create(): void
    {
        $this->authorize('create', Service::class);

        $this->resetForm();

        $this->date_start = now()->format('Y-m-d');
        $this->date_end = now()->format('Y-m-d');

        $this->showFormModal = true;
    }

    /* =========================
        EDIT
    ========================= */
    public function edit(int $serviceId): void
    {
        $service = Service::findOrFail($serviceId);

        $this->authorize('update', $service);

        $this->resetErrorBag();
        $this->resetValidation();

        $this->editingServiceId = $service->id;
        $this->user_id = $service->user_id;
        $this->code = $service->code;
        $this->address = $service->address ?? '';
        $this->postal = $service->postal ?? '';
        $this->description = $service->description ?? '';
        $this->status = $service->status->value; // ✅ correcto
        $this->date_start = $service->date_start?->format('Y-m-d') ?? '';
        $this->date_end = $service->date_end?->format('Y-m-d') ?? '';
        $this->hour_start = $service->hour_start?->format('H:i') ?? '';
        $this->hour_end = $service->hour_end?->format('H:i') ?? '';

        $this->showFormModal = true;
    }

    /* =========================
        SAVE
    ========================= */
    public function save(): void
    {
        $this->authorize('create', Service::class);

        $validated = $this->validate($this->rules());

        $payload = [
            'user_id' => $validated['user_id'],
            'code' => $validated['code'],
            'address' => $validated['address'] ?: null,
            'postal' => $validated['postal'] ?: null,
            'description' => $validated['description'] ?: null,
            'status' => $validated['status'], // ✅ int correcto
            'date_start' => $validated['date_start'] ?: null,
            'date_end' => $validated['date_end'] ?: null,
            'hour_start' => $validated['hour_start'] ?: null,
            'hour_end' => $validated['hour_end'] ?: null,
        ];

        if ($this->editingServiceId) {
            $service = Service::findOrFail($this->editingServiceId);

            $this->authorize('update', $service);

            $service->update($payload);

            session()->flash('status', 'Servicio actualizado con éxito.');
        } else {
            Service::create($payload);

            session()->flash('status', 'Servicio creado con éxito.');
        }

        $this->closeFormModal();
        $this->resetPage();
    }

    /* =========================
        DELETE
    ========================= */
    public function confirmDelete(int $serviceId): void
    {
        $service = Service::findOrFail($serviceId);

        $this->authorize('delete', $service);

        $this->deletingServiceId = $service->id;
        $this->deletingServiceCode = $service->code;

        $this->showDeleteModal = true;
    }

    public function destroy(): void
    {
        $service = Service::findOrFail($this->deletingServiceId);

        $this->authorize('delete', $service);

        $service->delete();

        session()->flash('status', 'Servicio eliminado con éxito.');

        $this->closeDeleteModal();
        $this->resetPage();
    }

    /* =========================
        MODALS
    ========================= */
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

    /* =========================
        RESET
    ========================= */
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
        $this->status = Status::ABIERTO->value;
        $this->date_start = '';
        $this->date_end = '';
        $this->hour_start = '';
        $this->hour_end = '';
    }

    /* =========================
        RULES
    ========================= */
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
            'status' => ['required', Rule::in(array_column(Status::cases(), 'value'))],
            'date_start' => ['nullable', 'date'],
            'date_end' => ['nullable', 'date', 'after_or_equal:date_start'],
            'hour_start' => ['nullable', 'date_format:H:i'],
            'hour_end' => ['nullable', 'date_format:H:i'],
        ];
    }

    public function render()
    {
        return view('livewire.dashboard.home.index');
    }
}
