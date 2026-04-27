<div class="flex flex-col gap-8 md:flex-row md:items-start">
    <aside class="w-full md:w-[240px]">
        <nav aria-label="{{ __('Settings') }}" class="rounded-2xl border border-layer-line bg-layer p-2 shadow-sm">
            @php($links = [
                ['route' => 'profile.edit', 'label' => __('Profile')],
                ['route' => 'security.edit', 'label' => __('Security')],
                ['route' => 'appearance.edit', 'label' => __('Appearance')],
            ])

            @foreach ($links as $link)
                <a
                    href="{{ route($link['route']) }}"
                    wire:navigate
                    @class([
                        'block rounded-xl px-4 py-3 text-sm font-medium transition',
                        'bg-primary text-white shadow-sm' => request()->routeIs($link['route']),
                        'text-foreground hover:bg-surface' => ! request()->routeIs($link['route']),
                    ])
                >
                    {{ $link['label'] }}
                </a>
            @endforeach
        </nav>
    </aside>

    <div class="min-w-0 flex-1 self-stretch">
        <div class="rounded-2xl border border-layer-line bg-layer p-6 shadow-sm">
            <h2 class="text-xl font-semibold text-foreground">{{ $heading ?? '' }}</h2>
            <p class="mt-2 text-sm text-muted-foreground">{{ $subheading ?? '' }}</p>

            <div class="mt-6 w-full max-w-2xl">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
