@props([
    'rows',
    'sortBy',
    'sortDirection',
])

@php($statusClasses = [
    \App\Enums\Status::ABIERTO->value => 'bg-amber-100 text-amber-700',
    \App\Enums\Status::EN_PROCESO->value => 'bg-sky-100 text-sky-700',
    \App\Enums\Status::FINALIZADO->value => 'bg-emerald-100 text-emerald-700',
])

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
                    <th class="px-5 py-3 text-start text-xs font-semibold uppercase tracking-[0.2em] text-muted-foreground">Responsable</th>
                    <th class="px-5 py-3 text-start text-xs font-semibold uppercase tracking-[0.2em] text-muted-foreground">Hora</th>
                    <th class="px-5 py-3 text-start text-xs font-semibold uppercase tracking-[0.2em] text-muted-foreground">
                        <button type="button" wire:click="sort('status')" class="inline-flex items-center gap-x-2">
                            Estado
                            @if ($sortBy === 'status')
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
                    <th class="px-5 py-3 text-end text-xs font-semibold uppercase tracking-[0.2em] text-muted-foreground">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-layer-line">
                @forelse ($rows as $service)
                    <tr class="bg-layer">
                        <td class="px-5 py-4">
                            <div>
                                <div class="flex items-center gap-2">
                                    <p class="font-medium text-foreground">{{ $service->code }}</p>
                                    <button
                                        type="button"
                                        class="inline-flex size-7 items-center justify-center rounded-lg text-muted-foreground transition hover:bg-muted-hover hover:text-foreground focus:outline-hidden"
                                        title="Copiar código"
                                        aria-label="Copiar código {{ $service->code }}"
                                        onclick="navigator.clipboard?.writeText(@js($service->code))"
                                    >
                                        <svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <rect width="14" height="14" x="8" y="8" rx="2" ry="2"/>
                                            <path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/>
                                        </svg>
                                    </button>
                                </div>
                                @if ($service->address)
                                    @php($streetLine = trim(implode(' ', array_filter([
                                        $service->address_type?->label(),
                                        $service->address,
                                    ]))))
                                    @php($addressParts = array_filter([
                                        $streetLine,
                                        $service->number,
                                        $service->complement,
                                        $service->city,
                                        $service->state,
                                        $service->postal,
                                    ]))
                                    @php($mapsAddressParts = array_filter([
                                        $streetLine,
                                        $service->number,
                                        $service->city,
                                        $service->state,
                                        $service->postal,
                                    ]))
                                    @php($fullAddress = implode(', ', $addressParts))
                                    @php($mapsAddress = implode(', ', $mapsAddressParts))
                                    <a
                                        href="{{ 'https://www.google.com/maps/dir/?api=1&destination='.urlencode($mapsAddress) }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="text-sm text-primary transition hover:text-primary-hover hover:underline"
                                    >
                                        {{ $fullAddress }}
                                    </a>
                                @else
                                    <p class="text-sm text-muted-foreground">Sin dirección informada</p>
                                @endif
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-5 py-4 text-sm text-foreground">
                            {{ $service->user?->name ?? 'Sin responsable' }}
                        </td>
                        <td class="px-5 py-4 text-sm text-muted-foreground">
                            <div>{{ $service->hour_start?->format('H:i') ?? '--:--' }} - {{ $service->hour_end?->format('H:i') ?? '--:--' }}</div>
                        </td>
                        <td class="whitespace-nowrap px-5 py-4">
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses[$service->status->value] ?? 'bg-slate-100 text-slate-700' }}">
                                {{ $service->status->label() }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-sm text-muted-foreground">
                            <div>{{ $service->date_start?->format('d/m') ?? '--' }}</div>
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
