<div
    id="hs-application-sidebar"
    class="hs-overlay [--auto-close:lg] hs-overlay-open:translate-x-0 fixed inset-y-0 start-0 z-60 hidden h-full w-65 -translate-x-full transform border-e border-sidebar-line bg-sidebar transition-all duration-300 lg:block lg:translate-x-0 lg:end-auto lg:bottom-0"
    role="dialog"
    tabindex="-1"
    aria-label="Sidebar"
>
    @php
        $user = auth()->user();
        $items = array_values(array_filter([
            [
                'label' => 'Dashboard',
                'route' => 'dashboard',
                'active' => request()->routeIs('dashboard'),
                'icon' => '<path d="M3 12 12 4l9 8"/><path d="M5 10v10h14V10"/><path d="M10 20v-6h4v6"/>',
                'visible' => true,
            ],
            [
                'label' => 'Users',
                'route' => 'users.index',
                'active' => request()->routeIs('users.*'),
                'icon' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
                'visible' => $user?->can('users.view'),
            ],
            [
                'label' => 'Servicios',
                'route' => 'services.index',
                'active' => request()->routeIs('services.*'),
                'icon' => '<rect x="3" y="4" width="18" height="16" rx="2"/><path d="M7 8h10"/><path d="M7 12h6"/><path d="M7 16h8"/>',
                'visible' => $user?->can('services.view'),
            ],
            [
                'label' => 'Roles',
                'route' => 'roles.index',
                'active' => request()->routeIs('roles.*'),
                'icon' => '<path d="M12 15l-3.5 2 1-4-3-2.5 4.2-.3L12 6l1.3 4.2 4.2.3-3 2.5 1 4z"/><path d="M19 21H5"/>',
                'visible' => $user?->can('roles.view'),
            ],
            [
                'label' => 'Permissions',
                'route' => 'permissions.index',
                'active' => request()->routeIs('permissions.*'),
                'icon' => '<path d="M9 12l2 2 4-4"/><path d="M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z"/>',
                'visible' => $user?->can('permissions.view'),
            ],

        ], fn ($item) => $item['visible']));
    @endphp

    <div class="relative flex h-full max-h-full flex-col bg-gray-25">
        <div class="flex items-center px-6 pt-4">
            <a class="inline-flex items-center gap-3 rounded-xl font-semibold text-sidebar-nav-foreground focus:outline-hidden focus:opacity-80" href="{{ route('dashboard') }}" aria-label="Dashboard">
                <span class="inline-flex size-10 items-center justify-center rounded-2xl bg-primary/10 text-primary">
                    <svg class="size-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 12 12 4l9 8"/>
                        <path d="M5 10v10h14V10"/>
                        <path d="M10 20v-6h4v6"/>
                    </svg>
                </span>
                <span>
                    <span class="block text-sm uppercase tracking-[0.24em] text-muted-foreground">Panel</span>
                    <span class="block text-base text-sidebar-nav-foreground">Servicios App</span>
                </span>
            </a>
        </div>

        <div class="h-full overflow-y-auto p-3 [&::-webkit-scrollbar-thumb]:bg-scrollbar-thumb [&::-webkit-scrollbar-thumb]:rounded-none [&::-webkit-scrollbar-track]:bg-scrollbar-track [&::-webkit-scrollbar]:w-2">
            <nav class="flex w-full flex-col gap-1">
                @foreach ($items as $item)
                    <a
                        href="{{ route($item['route']) }}"
                        @class([
                            'flex items-center gap-x-3.5 rounded-xl px-3 py-3 text-sm font-medium transition focus:outline-hidden',
                            'bg-sidebar-nav-active text-sidebar-nav-foreground shadow-sm' => $item['active'],
                            'text-sidebar-nav-foreground hover:bg-sidebar-nav-hover focus:bg-sidebar-nav-focus' => ! $item['active'],
                        ])
                    >
                        <span class="inline-flex size-9 items-center justify-center rounded-lg border border-sidebar-line/70 bg-white/5">
                            <svg class="size-4 shrink-0" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                {!! $item['icon'] !!}
                            </svg>
                        </span>
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </nav>
        </div>
    </div>
</div>
