<x-layouts::auth :title="__('Restablecer contraseña')">
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Restablecer contraseña')" :description="__('Por favor, introduce tu nueva contraseña a continuación')" />

        <!-- Estado de la sesión -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.update') }}" class="flex flex-col gap-6">
            @csrf
            <!-- Token -->
            <input type="hidden" name="token" value="{{ request()->route('token') }}">

            <!-- Correo electrónico -->
            <input
                name="email"
                value="{{ request('email') }}"
                type="email"
                required
                autocomplete="email"
                placeholder="Correo electrónico"
                class="block w-full rounded-lg border border-layer-line bg-surface px-4 py-3 text-sm text-foreground focus:border-primary-focus focus:outline-hidden focus:ring-0"
            />

            <!-- Contraseña -->
            <input
                name="password"
                type="password"
                required
                autocomplete="new-password"
                placeholder="Contraseña"
                class="block w-full rounded-lg border border-layer-line bg-surface px-4 py-3 text-sm text-foreground focus:border-primary-focus focus:outline-hidden focus:ring-0"
            />

            <!-- Confirmar contraseña -->
            <input
                name="password_confirmation"
                type="password"
                required
                autocomplete="new-password"
                placeholder="Confirmar contraseña"
                class="block w-full rounded-lg border border-layer-line bg-surface px-4 py-3 text-sm text-foreground focus:border-primary-focus focus:outline-hidden focus:ring-0"
            />

            <div class="flex items-center justify-end">
                <button
                    type="submit"
                    class="inline-flex w-full items-center justify-center rounded-lg bg-primary px-4 py-3 text-sm font-semibold text-white transition hover:bg-primary-hover"
                >
                    {{ __('Restablecer contraseña') }}
                </button>
            </div>
        </form>
    </div>
</x-layouts::auth>
