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
        <x-services.form-modal :users="$users" :editing-service-id="$editingServiceId" />
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
