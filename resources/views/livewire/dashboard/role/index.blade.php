<div class="space-y-6">
    @php($rows = $this->rows)
    @php($permissions = $this->permissions)

    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-muted-foreground">Dashboard</p>
            <h1 class="mt-2 text-3xl font-semibold text-foreground">Roles</h1>
            <p class="mt-2 text-sm text-muted-foreground">
                Gestiona perfiles de acceso y vincula permisos para cada role.
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
            Nueva role
        </button>
    </div>

    @if (session('status'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
            {{ session('status') }}
        </div>
    @endif

    <div class="grid gap-4 xl:grid-cols-3">
        <div class="rounded-2xl border border-layer-line bg-layer p-5 shadow-sm">
            <p class="text-sm font-medium text-muted-foreground">Total de roles</p>
            <p class="mt-3 text-3xl font-semibold text-foreground">{{ $this->totalRoles }}</p>
        </div>

        <div class="rounded-2xl border border-layer-line bg-layer p-5 shadow-sm">
            <p class="text-sm font-medium text-muted-foreground">Permisos registrados</p>
            <p class="mt-3 text-3xl font-semibold text-primary">{{ $this->totalPermissions }}</p>
        </div>

        <div class="rounded-2xl border border-layer-line bg-layer p-5 shadow-sm">
            <div class="grid gap-4 md:grid-cols-[minmax(0,1fr)_160px]">
                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-foreground">Buscar role</span>
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
                            placeholder="Buscar por nombre"
                        >
                    </div>
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-foreground">Por página</span>
                    <select
                        wire:model.live="quantity"
                        class="block w-full rounded-lg border border-layer-line bg-surface px-3 py-3 text-sm text-foreground focus:border-primary-focus focus:outline-hidden focus:ring-0"
                    >
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </label>
            </div>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-layer-line bg-layer shadow-sm">
        <div class="flex items-center justify-between border-b border-layer-line px-5 py-4">
            <div>
                <h2 class="text-lg font-semibold text-foreground">Lista de roles</h2>
                <p class="mt-1 text-sm text-muted-foreground">{{ $rows->total() }} registro(s) encontrado(s)</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-layer-line">
                <thead class="bg-surface">
                    <tr>
                        <th class="px-5 py-3 text-start text-xs font-semibold uppercase tracking-[0.2em] text-muted-foreground">Nombre</th>
                        <th class="px-5 py-3 text-start text-xs font-semibold uppercase tracking-[0.2em] text-muted-foreground">Permisos</th>
                        <th class="px-5 py-3 text-start text-xs font-semibold uppercase tracking-[0.2em] text-muted-foreground">Creado en</th>
                        <th class="px-5 py-3 text-end text-xs font-semibold uppercase tracking-[0.2em] text-muted-foreground">Acciones</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-layer-line">
                    @forelse ($rows as $role)
                        <tr class="bg-layer">
                            <td class="px-5 py-4">
                                <p class="font-medium text-foreground">{{ $role->name }}</p>
                                <p class="text-sm text-muted-foreground">Guard: {{ $role->guard_name }}</p>
                            </td>

                            <td class="px-5 py-4">
                                <div class="flex flex-wrap gap-2">
                                    @forelse ($role->permissions as $permission)
                                        <span class="inline-flex rounded-full bg-primary/10 px-3 py-1 text-xs font-semibold text-primary">
                                            {{ $permission->name }}
                                        </span>
                                    @empty
                                        <span class="text-sm text-muted-foreground">Sin permisos vinculados</span>
                                    @endforelse
                                </div>
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-sm text-muted-foreground">
                                {{ $role->created_at?->format('d/m/Y H:i') }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button" wire:click="edit({{ $role->id }})"
                                        class="inline-flex items-center gap-x-2 rounded-lg border border-layer-line bg-surface px-3 py-2 text-sm font-medium text-foreground transition hover:bg-muted-hover">
                                        Editar
                                    </button>

                                    <button type="button" wire:click="confirmDelete({{ $role->id }})"
                                        class="inline-flex items-center gap-x-2 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm font-medium text-red-600 transition hover:bg-red-100">
                                        Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-14 text-center text-muted-foreground">
                                No se encontraron roles
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
</div>
