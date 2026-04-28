<x-layouts::auth :title="__('Confirmar contraseña')">
    <div class="flex flex-col gap-6">
        <x-auth-header
            :title="__('Confirmar contraseña')"
            :description="__('Esta es una área segura de la aplicación. Por favor confirma tu contraseña antes de continuar.')"
        />

        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.confirm.store') }}" class="flex flex-col gap-6">
            @csrf

            <label class="block">
                <span class="mb-2 block text-sm font-medium text-foreground">{{ __('Contraseña') }}</span>
                <input
                    name="password"
                    type="password"
                    required
                    autocomplete="current-password"
                    placeholder="{{ __('Contraseña') }}"
                    class="block w-full rounded-lg border border-layer-line bg-surface px-4 py-3 text-sm text-foreground focus:border-primary-focus focus:outline-hidden focus:ring-0"
                >
                @error('password')
                    <span class="mt-2 block text-sm text-red-600">{{ $message }}</span>
                @enderror
            </label>

            <button
                type="submit"
                class="inline-flex w-full items-center justify-center rounded-lg bg-primary px-4 py-3 text-sm font-semibold text-white transition hover:bg-primary-hover"
                data-test="confirm-password-button"
            >
                {{ __('Confirmar') }}
            </button>
        </form>
    </div>
</x-layouts::auth>
