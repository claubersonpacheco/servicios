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

    <x-services.list :rows="$rows" :sort-by="$sortBy" :sort-direction="$sortDirection" />

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

                            <div class="grid gap-5 md:col-span-2 md:grid-cols-[10rem_minmax(0,1fr)_8rem]">
                                <label class="block">
                                    <span class="mb-2 block text-sm font-medium text-foreground">Tipo de vía</span>
                                    <select
                                        wire:model.live="address_type"
                                        class="block w-full rounded-lg border border-layer-line bg-surface px-4 py-3 text-sm text-foreground focus:border-primary-focus focus:outline-hidden focus:ring-0"
                                    >
                                        @foreach (\App\Enums\AdressType::cases() as $addressTypeOption)
                                            <option value="{{ $addressTypeOption->value }}">{{ $addressTypeOption->label() }}</option>
                                        @endforeach
                                    </select>
                                    @error('address_type')
                                        <span class="mt-2 block text-sm text-red-600">{{ $message }}</span>
                                    @enderror
                                </label>

                                <label class="block">
                                    <span class="mb-2 block text-sm font-medium text-foreground">Dirección</span>
                                    <input
                                        type="text"
                                        wire:model.live="address"
                                        class="block w-full rounded-lg border border-layer-line bg-surface px-4 py-3 text-sm text-foreground focus:border-primary-focus focus:outline-hidden focus:ring-0"
                                        placeholder="Nombre de la vía"
                                    >
                                    @error('address')
                                        <span class="mt-2 block text-sm text-red-600">{{ $message }}</span>
                                    @enderror
                                </label>

                                <label class="block">
                                    <span class="mb-2 block text-sm font-medium text-foreground">Número</span>
                                    <input
                                        type="text"
                                        wire:model.live="number"
                                        class="block w-full rounded-lg border border-layer-line bg-surface px-4 py-3 text-sm text-foreground focus:border-primary-focus focus:outline-hidden focus:ring-0"
                                        placeholder="12"
                                    >
                                    @error('number')
                                        <span class="mt-2 block text-sm text-red-600">{{ $message }}</span>
                                    @enderror
                                </label>
                            </div>

                            <div class="grid gap-5 md:col-span-2 md:grid-cols-3">
                                <label class="block">
                                    <span class="mb-2 block text-sm font-medium text-foreground">Complemento</span>
                                    <input
                                        type="text"
                                        wire:model.live="complement"
                                        class="block w-full rounded-lg border border-layer-line bg-surface px-4 py-3 text-sm text-foreground focus:border-primary-focus focus:outline-hidden focus:ring-0"
                                        placeholder="Piso, puerta, bloque"
                                    >
                                    @error('complement')
                                        <span class="mt-2 block text-sm text-red-600">{{ $message }}</span>
                                    @enderror
                                </label>

                                <label class="block">
                                    <span class="mb-2 block text-sm font-medium text-foreground">Ciudad</span>
                                    <input
                                        type="text"
                                        wire:model.live="city"
                                        class="block w-full rounded-lg border border-layer-line bg-surface px-4 py-3 text-sm text-foreground focus:border-primary-focus focus:outline-hidden focus:ring-0"
                                        placeholder="Madrid"
                                    >
                                    @error('city')
                                        <span class="mt-2 block text-sm text-red-600">{{ $message }}</span>
                                    @enderror
                                </label>

                                <label class="block">
                                    <span class="mb-2 block text-sm font-medium text-foreground">Provincia</span>
                                    <input
                                        type="text"
                                        wire:model.live="state"
                                        class="block w-full rounded-lg border border-layer-line bg-surface px-4 py-3 text-sm text-foreground focus:border-primary-focus focus:outline-hidden focus:ring-0"
                                        placeholder="Madrid"
                                    >
                                    @error('state')
                                        <span class="mt-2 block text-sm text-red-600">{{ $message }}</span>
                                    @enderror
                                </label>
                            </div>

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
