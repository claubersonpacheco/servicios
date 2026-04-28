<div class="space-y-6">
    @php($rows = $this->rows)
    @php($users = $this->users)

    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-muted-foreground">Dashboard</p>
            <h1 class="mt-2 text-3xl font-semibold text-foreground">Bienvenido</h1>
            <p class="mt-2 text-sm text-muted-foreground">
                Administra los servicios registrados, el control de responsables, el estado y las citas.
            </p>
        </div>

        <button
            type="button"
            wire:click="create"
            class="inline-flex items-center justify-center gap-x-2 rounded-lg bg-primary px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-hover focus:outline-hidden"
        >
            <svg class="size-4 shrink-0" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 12h14"/>
                <path d="M12 5v14"/>
            </svg>
            Nuevo servicio
        </button>
    </div>

    @if (session('status'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
            {{ session('status') }}
        </div>
    @endif

    <div class="rounded-2xl border border-layer-line bg-layer p-5 shadow-sm">
        <label class="block">
            <span class="mb-2 block text-sm font-medium text-foreground">Buscar servicio</span>
            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 start-0 flex items-center ps-3.5">
                    <svg class="size-4 text-muted-foreground" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"/>
                        <path d="m21 21-4.3-4.3"/>
                    </svg>
                </div>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    class="block w-full rounded-lg border border-layer-line bg-surface py-3 ps-10 pe-4 text-sm text-foreground placeholder:text-muted-foreground focus:border-primary-focus focus:outline-hidden focus:ring-0"
                    placeholder="Busque por código, dirección, descripción o responsable"
                >
            </div>
        </label>
    </div>

    <div class="overflow-hidden rounded-2xl border border-layer-line bg-layer shadow-sm">
        <div class="flex items-center justify-between border-b border-layer-line px-5 py-4">
            <div>
                <h2 class="text-lg font-semibold text-foreground">Lista de servicios</h2>
                <p class="mt-1 text-sm text-muted-foreground">
                    {{ $rows->total() }} registro(s) encontrado(s)
                </p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-layer-line">
                <thead class="bg-surface">
                    <tr>
                        <th class="px-5 py-3 text-start text-xs font-semibold uppercase tracking-[0.2em] text-muted-foreground">
                            <button type="button" wire:click="sort('code')" class="inline-flex items-center gap-x-2">
                                Servicio
                                @if ($sortBy === 'code')
                                    <span class="text-primary">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </button>
                        </th>
                         <th class="px-5 py-3 text-start text-xs font-semibold uppercase tracking-[0.2em] text-muted-foreground">
                            <button type="button" wire:click="sort('date_start')" class="inline-flex items-center gap-x-2">
                                Fecha
                                @if ($sortBy === 'date_start')
                                    <span class="text-primary">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </button>
                        </th>
                        <th class="px-5 py-3 text-start text-xs font-semibold uppercase tracking-[0.2em] text-muted-foreground">Hora</th>
                        <th class="px-5 py-3 text-start text-xs font-semibold uppercase tracking-[0.2em] text-muted-foreground">Responsable</th>
                        <th class="px-5 py-3 text-start text-xs font-semibold uppercase tracking-[0.2em] text-muted-foreground">
                            <button type="button" wire:click="sort('status')" class="inline-flex items-center gap-x-2">
                                Estado
                                @if ($sortBy === 'status')
                                    <span class="text-primary">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </button>
                        </th>

                        <th class="px-5 py-3 text-end text-xs font-semibold uppercase tracking-[0.2em] text-muted-foreground">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-layer-line">
                    @forelse ($rows as $service)
                        <tr class="bg-layer">
                            <td class="px-5 py-4">
                                <div>
                                    <p class="font-medium text-foreground">{{ $service->code }}</p>
                                    @if ($service->address)
                                        @php($mapsDestination = trim($service->address.' '.$service->postal))
                                        <a
                                            href="{{ 'https://www.google.com/maps/dir/?api=1&destination='.urlencode($mapsDestination) }}"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="text-sm text-primary transition hover:text-primary-hover hover:underline"
                                        >
                                            {{ $service->address }}
                                        </a>
                                    @else
                                        <p class="text-sm text-muted-foreground">Sin dirección informada</p>
                                    @endif
                                </div>
                            </td>
                            <td class="px-5 py-4 text-sm text-muted-foreground">
                                <div>{{ $service->date_start?->format('d/m') ?? '--' }}</div>
                            </td>
                            <td class="px-5 py-4 text-sm text-muted-foreground">
                                <div>{{ $service->hour_start?->format('H:i') ?? '--:--' }} - {{ $service->hour_end?->format('H:i') ?? '--:--' }}</div>
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-sm text-foreground">
                                {{ $service->user?->name ?? 'Sin responsable' }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4">
                                @php($statusClasses = [
                                    \App\Enums\Status::ABIERTO->value => 'bg-amber-100 text-amber-700',
                                    \App\Enums\Status::EN_PROCESO->value => 'bg-sky-100 text-sky-700',
                                    \App\Enums\Status::FINALIZADO->value => 'bg-emerald-100 text-emerald-700',
                                ])

                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold
                                    {{ $statusClasses[$service->status->value] ?? 'bg-slate-100 text-slate-700' }}">

                                    {{ $service->status->label() }}
                                </span>
                            </td>

                            <td class="whitespace-nowrap px-5 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <button
                                        type="button"
                                        wire:click="edit({{ $service->id }})"
                                        class="inline-flex items-center gap-x-2 rounded-lg border border-layer-line bg-surface px-3 py-2 text-sm font-medium text-foreground transition hover:bg-muted-hover focus:outline-hidden"
                                    >
                                        Editar
                                    </button>
                                    <button
                                        type="button"
                                        wire:click="confirmDelete({{ $service->id }})"
                                        class="inline-flex items-center gap-x-2 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm font-medium text-red-600 transition hover:bg-red-100 focus:outline-hidden"
                                    >
                                        Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-14 text-center">
                                <div class="mx-auto flex max-w-sm flex-col items-center">
                                    <div class="inline-flex size-16 items-center justify-center rounded-full bg-surface text-muted-foreground">
                                        <svg class="size-7" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="11" cy="11" r="8"/>
                                            <path d="m21 21-4.3-4.3"/>
                                        </svg>
                                    </div>
                                    <h3 class="mt-4 text-lg font-semibold text-foreground">Ningún servicio encontrado</h3>
                                    <p class="mt-2 text-sm text-muted-foreground">
                                        Ajuste los filtros o cree un nuevo servicio.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-layer-line px-5 py-4">
            {{ $rows->links() }}
        </div>
    </div>

    @if ($showFormModal)
        <div class="fixed inset-0 z-80 overflow-y-auto">
            <div class="fixed inset-0 bg-slate-900/50" wire:click="closeFormModal"></div>

            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative z-10 w-full max-w-4xl rounded-2xl border border-layer-line bg-layer shadow-xl">
                    <div class="flex items-center justify-between border-b border-layer-line px-6 py-4">
                        <div>
                            <h3 class="text-lg font-semibold text-foreground">
                                {{ $editingServiceId ? 'Editar servicio' : 'Nuevo servicio' }}
                            </h3>
                            <p class="mt-1 text-sm text-muted-foreground">
                                {{ $editingServiceId ? 'Actualiza los datos del servicio seleccionado.' : 'Completa los datos para crear un nuevo servicio.' }}
                            </p>
                        </div>

                        <button type="button" wire:click="closeFormModal" class="inline-flex size-9 items-center justify-center rounded-full text-muted-foreground transition hover:bg-muted-hover hover:text-foreground">
                            <svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 6 6 18"/>
                                <path d="m6 6 12 12"/>
                            </svg>
                        </button>
                    </div>

                    <form wire:submit="save" class="space-y-5 px-6 py-6">
                        <div class="grid gap-5 md:grid-cols-2">
                            <label class="block">
                                <span class="mb-2 block text-sm font-medium text-foreground">Responsable</span>
                                <select
                                    wire:model.live="user_id"
                                    class="block w-full rounded-lg border border-layer-line bg-surface px-4 py-3 text-sm text-foreground focus:border-primary-focus focus:outline-hidden focus:ring-0"
                                >
                                    <option value="">Seleccione</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <span class="mt-2 block text-sm text-red-600">{{ $message }}</span>
                                @enderror
                            </label>

                            <label class="block">
                                <span class="mb-2 block text-sm font-medium text-foreground">Código</span>
                                <input
                                    type="text"
                                    wire:model.live="code"
                                    class="block w-full rounded-lg border border-layer-line bg-surface px-4 py-3 text-sm text-foreground focus:border-primary-focus focus:outline-hidden focus:ring-0"
                                    placeholder="SRV-001"
                                >
                                @error('code')
                                    <span class="mt-2 block text-sm text-red-600">{{ $message }}</span>
                                @enderror
                            </label>

                            <label class="block md:col-span-2">
                                <span class="mb-2 block text-sm font-medium text-foreground">Dirección</span>
                                <input
                                    type="text"
                                    wire:model.live="address"
                                    class="block w-full rounded-lg border border-layer-line bg-surface px-4 py-3 text-sm text-foreground focus:border-primary-focus focus:outline-hidden focus:ring-0"
                                    placeholder="Ingrese la dirección del servicio"
                                >
                                @error('address')
                                    <span class="mt-2 block text-sm text-red-600">{{ $message }}</span>
                                @enderror
                            </label>

                            <label class="block">
                                <span class="mb-2 block text-sm font-medium text-foreground">Código postal</span>
                                <input
                                    type="text"
                                    wire:model.live="postal"
                                    class="block w-full rounded-lg border border-layer-line bg-surface px-4 py-3 text-sm text-foreground focus:border-primary-focus focus:outline-hidden focus:ring-0"
                                    placeholder="28001"
                                >
                                @error('postal')
                                    <span class="mt-2 block text-sm text-red-600">{{ $message }}</span>
                                @enderror
                            </label>

                            <label class="block">
                                <span class="mb-2 block text-sm font-medium text-foreground">Estado</span>
                                <select
                                    wire:model.live="status"
                                    class="block w-full rounded-lg border border-layer-line bg-surface px-4 py-3 text-sm text-foreground focus:border-primary-focus focus:outline-hidden focus:ring-0"
                                >
                                    @foreach (\App\Enums\Status::cases() as $statusOption)
                                        <option value="{{ $statusOption->value }}">{{ $statusOption->label() }}</option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <span class="mt-2 block text-sm text-red-600">{{ $message }}</span>
                                @enderror
                            </label>

                            <label class="block">
                                <span class="mb-2 block text-sm font-medium text-foreground">Fecha inicial</span>
                                <input
                                    type="date"
                                    wire:model.live="date_start"
                                    class="block w-full rounded-lg border border-layer-line bg-surface px-4 py-3 text-sm text-foreground focus:border-primary-focus focus:outline-hidden focus:ring-0"
                                >
                                @error('date_start')
                                    <span class="mt-2 block text-sm text-red-600">{{ $message }}</span>
                                @enderror
                            </label>

                            <label class="block">
                                <span class="mb-2 block text-sm font-medium text-foreground">Fecha final</span>
                                <input
                                    type="date"
                                    wire:model.live="date_end"
                                    class="block w-full rounded-lg border border-layer-line bg-surface px-4 py-3 text-sm text-foreground focus:border-primary-focus focus:outline-hidden focus:ring-0"
                                >
                                @error('date_end')
                                    <span class="mt-2 block text-sm text-red-600">{{ $message }}</span>
                                @enderror
                            </label>

                            <label class="block">
                                <span class="mb-2 block text-sm font-medium text-foreground">Hora inicial</span>
                                <input
                                    type="time"
                                    wire:model.live="hour_start"
                                    class="block w-full rounded-lg border border-layer-line bg-surface px-4 py-3 text-sm text-foreground focus:border-primary-focus focus:outline-hidden focus:ring-0"
                                >
                                @error('hour_start')
                                    <span class="mt-2 block text-sm text-red-600">{{ $message }}</span>
                                @enderror
                            </label>

                            <label class="block">
                                <span class="mb-2 block text-sm font-medium text-foreground">Hora final</span>
                                <input
                                    type="time"
                                    wire:model.live="hour_end"
                                    class="block w-full rounded-lg border border-layer-line bg-surface px-4 py-3 text-sm text-foreground focus:border-primary-focus focus:outline-hidden focus:ring-0"
                                >
                                @error('hour_end')
                                    <span class="mt-2 block text-sm text-red-600">{{ $message }}</span>
                                @enderror
                            </label>

                            <label class="block md:col-span-2">
                                <span class="mb-2 block text-sm font-medium text-foreground">Descripción</span>
                                <textarea
                                    wire:model.live="description"
                                    rows="4"
                                    class="block w-full rounded-lg border border-layer-line bg-surface px-4 py-3 text-sm text-foreground focus:border-primary-focus focus:outline-hidden focus:ring-0"
                                    placeholder="Describa el servicio"
                                ></textarea>
                                @error('description')
                                    <span class="mt-2 block text-sm text-red-600">{{ $message }}</span>
                                @enderror
                            </label>
                        </div>

                        <div class="flex flex-col-reverse gap-3 border-t border-layer-line pt-5 sm:flex-row sm:justify-end">
                            <button
                                type="button"
                                wire:click="closeFormModal"
                                class="inline-flex items-center justify-center rounded-lg border border-layer-line bg-surface px-4 py-3 text-sm font-semibold text-foreground transition hover:bg-muted-hover"
                            >
                                Cancelar
                            </button>
                            <button
                                type="submit"
                                class="inline-flex items-center justify-center rounded-lg bg-primary px-4 py-3 text-sm font-semibold text-white transition hover:bg-primary-hover"
                            >
                                {{ $editingServiceId ? 'Guardar cambios' : 'Crear servicio' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    @if ($showDeleteModal)
        <div class="fixed inset-0 z-80 overflow-y-auto">
            <div class="fixed inset-0 bg-slate-900/50" wire:click="closeDeleteModal"></div>

            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative z-10 w-full max-w-lg rounded-2xl border border-layer-line bg-layer shadow-xl">
                    <div class="border-b border-layer-line px-6 py-4">
                        <h3 class="text-lg font-semibold text-foreground">Eliminar servicio</h3>
                        <p class="mt-1 text-sm text-muted-foreground">
                            Esta acción no puede deshacerse.
                        </p>
                    </div>

                    <div class="px-6 py-6">
                        <p class="text-sm leading-6 text-foreground">
                            ¿Seguro que quieres eliminar este servicio?
                            <span class="font-semibold">{{ $deletingServiceCode }}</span>?
                        </p>

                        <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                            <button
                                type="button"
                                wire:click="closeDeleteModal"
                                class="inline-flex items-center justify-center rounded-lg border border-layer-line bg-surface px-4 py-3 text-sm font-semibold text-foreground transition hover:bg-muted-hover"
                            >
                                Cancelar
                            </button>
                            <button
                                type="button"
                                wire:click="destroy"
                                class="inline-flex items-center justify-center rounded-lg bg-red-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-red-700"
                            >
                                Confirmar eliminación
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
