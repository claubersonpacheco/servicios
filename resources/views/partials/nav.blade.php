<nav class="mx-auto flex basis-full items-center px-4 sm:px-6">
    <div class="me-5 flex items-center lg:me-0 lg:hidden">
        <a class="inline-flex items-center gap-3 rounded-md font-semibold focus:outline-hidden focus:opacity-80" href="{{ route('dashboard') }}" aria-label="Dashboard">
            <span class="inline-flex size-10 items-center justify-center rounded-2xl bg-primary/10 text-primary">
                <svg class="size-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 12 12 4l9 8"/>
                    <path d="M5 10v10h14V10"/>
                    <path d="M10 20v-6h4v6"/>
                </svg>
            </span>
            <span class="hidden text-sm font-semibold text-foreground sm:block">Servicios App</span>
        </a>
    </div>

    <div class="ms-auto flex w-full items-center justify-end gap-x-3 md:justify-between">
        <div class="hidden md:block">
            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 start-0 z-20 flex items-center ps-3.5">
                    <svg class="size-4 shrink-0 text-muted-foreground" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"/>
                        <path d="m21 21-4.3-4.3"/>
                    </svg>
                </div>
                <input type="text" class="block w-full rounded-lg border border-layer-line bg-layer py-2 ps-10 pe-4 text-sm text-foreground placeholder:text-muted-foreground focus:border-primary-focus focus:outline-hidden focus:ring-primary-focus disabled:pointer-events-none disabled:opacity-50" placeholder="Buscar">
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <div class="hs-dropdown relative inline-flex [--placement:bottom-right]">
                <button
                    id="hs-dropdown-account"
                    type="button"
                    class="inline-flex items-center gap-3 rounded-2xl border border-layer-line bg-layer px-3 py-2 shadow-sm transition hover:bg-muted-hover focus:outline-hidden"
                    aria-haspopup="menu"
                    aria-expanded="false"
                    aria-label="User menu"
                >
                    <div class="inline-flex size-10 items-center justify-center rounded-full bg-primary/10 text-sm font-semibold text-primary">
                        {{ auth()->user()->initials() }}
                    </div>

                    <div class="hidden min-w-0 text-start sm:block">
                        <p class="truncate text-sm font-semibold text-foreground">{{ auth()->user()->name }}</p>
                        <p class="truncate text-xs text-muted-foreground">{{ auth()->user()->email }}</p>
                    </div>

                    <svg class="hidden size-4 text-muted-foreground sm:block" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m6 9 6 6 6-6"/>
                    </svg>
                </button>

                <div
                    class="hs-dropdown-menu duration hs-dropdown-open:opacity-100 mt-2 hidden min-w-64 rounded-2xl border border-dropdown-line bg-dropdown opacity-0 shadow-md transition-[opacity,margin]"
                    role="menu"
                    aria-orientation="vertical"
                    aria-labelledby="hs-dropdown-account"
                >
                    <div class="rounded-t-2xl border-b border-dropdown-line bg-surface px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="inline-flex size-11 items-center justify-center rounded-full bg-primary/10 text-sm font-semibold text-primary">
                                {{ auth()->user()->initials() }}
                            </div>
                            <div class="min-w-0">
                                <p class="truncate text-sm font-semibold text-foreground">{{ auth()->user()->name }}</p>
                                <p class="truncate text-xs text-muted-foreground">{{ auth()->user()->email }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-1 p-1.5">
                        <a
                            class="flex items-center gap-x-3.5 rounded-xl px-3 py-2 text-sm text-dropdown-item-foreground transition hover:bg-dropdown-item-hover focus:outline-hidden focus:bg-dropdown-item-focus"
                            href="{{ route('profile.edit') }}"
                        >
                            <svg class="size-4 shrink-0" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="8" r="4"/>
                                <path d="M6 20a6 6 0 0 1 12 0"/>
                            </svg>
                            Profile
                        </a>

                        <a
                            class="flex items-center gap-x-3.5 rounded-xl px-3 py-2 text-sm text-dropdown-item-foreground transition hover:bg-dropdown-item-hover focus:outline-hidden focus:bg-dropdown-item-focus"
                            href="{{ route('security.edit') }}"
                        >
                            <svg class="size-4 shrink-0" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="4" y="11" width="16" height="10" rx="2"/>
                                <path d="M8 11V8a4 4 0 1 1 8 0v3"/>
                                <circle cx="12" cy="16" r="1"/>
                            </svg>
                            Security
                        </a>

                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf

                            <button
                                type="submit"
                                class="flex w-full items-center gap-x-3.5 rounded-xl px-3 py-2 text-start text-sm text-dropdown-item-foreground transition hover:bg-dropdown-item-hover focus:outline-hidden focus:bg-dropdown-item-focus"
                            >
                                <svg class="size-4 shrink-0" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m16 17 5-5-5-5"/>
                                    <path d="M21 12H9"/>
                                    <path d="M13 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h8"/>
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
