<x-layouts::auth :title="__('Recuperar contraseña')">
    <div class="flex flex-col gap-6">
        <x-auth-header
            :title="__('Recuperar contraseña')"
            :description="__('Introduce tu correo electrónico para recibir un enlace de restablecimiento de contraseña')"
        />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Email Address -->
            <label class="block">
                <span class="mb-2 block text-sm font-medium text-foreground">{{ __('Correo electrónico') }}</span>
                <input
                    name="email"
                    type="email"
                    required
                    autofocus
                    placeholder="email@example.com"
                    class="block w-full rounded-lg border border-layer-line bg-surface px-4 py-3 text-sm text-foreground focus:border-primary-focus focus:outline-hidden focus:ring-0"
                >
                @error('email')
                    <span class="mt-2 block text-sm text-red-600">{{ $message }}</span>
                @enderror
            </label>

            <button
                type="submit"
                class="inline-flex w-full items-center justify-center rounded-lg bg-primary px-4 py-3 text-sm font-semibold text-white transition hover:bg-primary-hover"
                data-test="email-password-reset-link-button"
            >
                {{ __('Enviar enlace de recuperación') }}
            </button>
        </form>

        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-400">
            <span>{{ __('O, volver a') }}</span>
            <a href="{{ route('login') }}" wire:navigate class="text-primary hover:underline">
                {{ __('iniciar sesión') }}
            </a>
        </div>
    </div>
</x-layouts::auth>
