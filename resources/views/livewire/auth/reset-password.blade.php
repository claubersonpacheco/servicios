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
            <div class="relative">
                <input
                    name="password"
                    type="password"
                    required
                    autocomplete="new-password"
                    placeholder="Contraseña"
                    class="block w-full rounded-lg border border-layer-line bg-surface px-4 py-3 pr-12 text-sm text-foreground focus:border-primary-focus focus:outline-hidden focus:ring-0"
                />

                <button
                    type="button"
                    class="absolute inset-y-0 right-0 flex w-11 items-center justify-center text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-gray-400 dark:hover:text-gray-200"
                    aria-label="Mostrar contraseña"
                    aria-pressed="false"
                    onclick="
                        const input = this.parentElement.querySelector('input');
                        const isHidden = input.type === 'password';
                        input.type = isHidden ? 'text' : 'password';
                        this.setAttribute('aria-pressed', isHidden ? 'true' : 'false');
                        this.setAttribute('aria-label', isHidden ? 'Ocultar contraseña' : 'Mostrar contraseña');
                        this.querySelector('[data-icon-eye]').classList.toggle('hidden', isHidden);
                        this.querySelector('[data-icon-eye-off]').classList.toggle('hidden', !isHidden);
                    "
                >
                    <svg data-icon-eye class="size-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                    <svg data-icon-eye-off class="hidden size-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M10.733 5.076a10.744 10.744 0 0 1 11.205 6.575 1 1 0 0 1 0 .696 10.747 10.747 0 0 1-1.444 2.49"/>
                        <path d="M14.084 14.158a3 3 0 0 1-4.242-4.242"/>
                        <path d="M17.479 17.499a10.75 10.75 0 0 1-15.417-5.151 1 1 0 0 1 0-.696 10.75 10.75 0 0 1 4.446-5.143"/>
                        <path d="m2 2 20 20"/>
                    </svg>
                </button>
            </div>

            <!-- Confirmar contraseña -->
            <div class="relative">
                <input
                    name="password_confirmation"
                    type="password"
                    required
                    autocomplete="new-password"
                    placeholder="Confirmar contraseña"
                    class="block w-full rounded-lg border border-layer-line bg-surface px-4 py-3 pr-12 text-sm text-foreground focus:border-primary-focus focus:outline-hidden focus:ring-0"
                />

                <button
                    type="button"
                    class="absolute inset-y-0 right-0 flex w-11 items-center justify-center text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-gray-400 dark:hover:text-gray-200"
                    aria-label="Mostrar contraseña"
                    aria-pressed="false"
                    onclick="
                        const input = this.parentElement.querySelector('input');
                        const isHidden = input.type === 'password';
                        input.type = isHidden ? 'text' : 'password';
                        this.setAttribute('aria-pressed', isHidden ? 'true' : 'false');
                        this.setAttribute('aria-label', isHidden ? 'Ocultar contraseña' : 'Mostrar contraseña');
                        this.querySelector('[data-icon-eye]').classList.toggle('hidden', isHidden);
                        this.querySelector('[data-icon-eye-off]').classList.toggle('hidden', !isHidden);
                    "
                >
                    <svg data-icon-eye class="size-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                    <svg data-icon-eye-off class="hidden size-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M10.733 5.076a10.744 10.744 0 0 1 11.205 6.575 1 1 0 0 1 0 .696 10.747 10.747 0 0 1-1.444 2.49"/>
                        <path d="M14.084 14.158a3 3 0 0 1-4.242-4.242"/>
                        <path d="M17.479 17.499a10.75 10.75 0 0 1-15.417-5.151 1 1 0 0 1 0-.696 10.75 10.75 0 0 1 4.446-5.143"/>
                        <path d="m2 2 20 20"/>
                    </svg>
                </button>
            </div>

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
