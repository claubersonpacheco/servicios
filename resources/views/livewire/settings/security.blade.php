<section class="w-full">
    @include('partials.settings-heading')

    <h2 class="sr-only">{{ __('Configuración de seguridad') }}</h2>

    <x-settings.layout :heading="__('Actualizar contraseña')" :subheading="__('Asegúrate de que tu cuenta use una contraseña larga y aleatoria para mayor seguridad')">
        <form method="POST" wire:submit="updatePassword" class="mt-6 space-y-6">

            <label class="block">
                <span class="mb-2 block text-sm font-medium text-foreground">{{ __('Contraseña actual') }}</span>
                <input
                    wire:model="current_password"
                    type="password"
                    required
                    autocomplete="current-password"
                    class="block w-full rounded-lg border border-layer-line bg-surface px-4 py-3 text-sm text-foreground focus:border-primary-focus focus:outline-hidden focus:ring-0"
                >
                @error('current_password')
                    <span class="mt-2 block text-sm text-red-600">{{ $message }}</span>
                @enderror
            </label>

            <label class="block">
                <span class="mb-2 block text-sm font-medium text-foreground">{{ __('Nueva contraseña') }}</span>
                <input
                    wire:model="password"
                    type="password"
                    required
                    autocomplete="new-password"
                    class="block w-full rounded-lg border border-layer-line bg-surface px-4 py-3 text-sm text-foreground focus:border-primary-focus focus:outline-hidden focus:ring-0"
                >
                @error('password')
                    <span class="mt-2 block text-sm text-red-600">{{ $message }}</span>
                @enderror
            </label>

            <label class="block">
                <span class="mb-2 block text-sm font-medium text-foreground">{{ __('Confirmar contraseña') }}</span>
                <input
                    wire:model="password_confirmation"
                    type="password"
                    required
                    autocomplete="new-password"
                    class="block w-full rounded-lg border border-layer-line bg-surface px-4 py-3 text-sm text-foreground focus:border-primary-focus focus:outline-hidden focus:ring-0"
                >
                @error('password_confirmation')
                    <span class="mt-2 block text-sm text-red-600">{{ $message }}</span>
                @enderror
            </label>

            <div class="flex items-center gap-4">
                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded-lg bg-primary px-4 py-3 text-sm font-semibold text-white transition hover:bg-primary-hover"
                >
                    {{ __('Guardar') }}
                </button>
            </div>
        </form>

        @if ($canManageTwoFactor)
            <section class="mt-12">
                <h2 class="text-lg font-semibold text-foreground">{{ __('Autenticación de dos factores') }}</h2>
                <p class="text-sm text-muted-foreground">{{ __('Gestiona la configuración de tu autenticación de dos factores') }}</p>

                <div class="flex flex-col w-full mx-auto space-y-6 text-sm">
                    @if ($twoFactorEnabled)
                        <div class="space-y-4">
                            <p class="text-sm text-muted-foreground">
                                {{ __('Se te pedirá un PIN seguro y aleatorio durante el inicio de sesión, que puedes obtener desde una aplicación compatible con TOTP en tu teléfono.') }}
                            </p>

                            <div class="flex justify-start">
                                <button
                                    type="button"
                                    class="inline-flex items-center justify-center rounded-lg bg-red-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-red-700"
                                    wire:click="disable"
                                >
                                    {{ __('Desactivar 2FA') }}
                                </button>
                            </div>

                            <livewire:settings.two-factor.recovery-codes :$requiresConfirmation />
                        </div>
                    @else
                        <div class="space-y-4">
                            <p class="text-sm text-muted-foreground">
                                {{ __('Cuando actives la autenticación de dos factores, se te pedirá un PIN seguro durante el inicio de sesión. Este PIN puede obtenerse desde una aplicación compatible con TOTP en tu teléfono.') }}
                            </p>

                            <button
                                type="button"
                                class="inline-flex items-center justify-center rounded-lg bg-primary px-4 py-3 text-sm font-semibold text-white transition hover:bg-primary-hover"
                                wire:click="enable"
                            >
                                {{ __('Activar 2FA') }}
                            </button>
                        </div>
                    @endif
                </div>
            </section>

            <!-- MODAL -->
            @if ($showModal)
                <div class="fixed inset-0 z-50 overflow-y-auto">
                    <div class="fixed inset-0 bg-slate-900/50" wire:click="closeModal"></div>

                    <div class="flex min-h-full items-center justify-center p-4">
                        <div class="relative z-10 w-full max-w-md rounded-2xl border border-layer-line bg-layer shadow-xl p-6 space-y-6">

                            <div class="text-center space-y-2">
                                <h3 class="text-lg font-semibold text-foreground">{{ $this->modalConfig['title'] }}</h3>
                                <p class="text-sm text-muted-foreground">{{ $this->modalConfig['description'] }}</p>
                            </div>

                            @if ($showVerificationStep)
                                <div class="space-y-4">
                                    <input
                                        type="text"
                                        wire:model="code"
                                        maxlength="6"
                                        class="block w-full text-center tracking-widest rounded-lg border border-layer-line bg-surface px-4 py-3 text-sm"
                                        placeholder="Código OTP"
                                    >

                                    <div class="flex gap-3">
                                        <button
                                            type="button"
                                            wire:click="resetVerification"
                                            class="flex-1 rounded-lg border border-layer-line bg-surface px-4 py-3 text-sm font-semibold"
                                        >
                                            {{ __('Volver') }}
                                        </button>

                                        <button
                                            type="button"
                                            wire:click="confirmTwoFactor"
                                            class="flex-1 rounded-lg bg-primary px-4 py-3 text-sm font-semibold text-white"
                                        >
                                            {{ __('Confirmar') }}
                                        </button>
                                    </div>
                                </div>
                            @else
                                <div class="space-y-4 text-center">
                                    @if ($qrCodeSvg)
                                        <div class="flex justify-center">
                                            {!! $qrCodeSvg !!}
                                        </div>
                                    @endif

                                    <button
                                        type="button"
                                        wire:click="showVerificationIfNecessary"
                                        class="w-full rounded-lg bg-primary px-4 py-3 text-sm font-semibold text-white"
                                    >
                                        {{ $this->modalConfig['buttonText'] }}
                                    </button>

                                    <p class="text-sm text-muted-foreground">
                                        {{ __('o, introduce el código manualmente') }}
                                    </p>

                                    <input
                                        type="text"
                                        readonly
                                        value="{{ $manualSetupKey }}"
                                        class="w-full rounded-lg border border-layer-line bg-surface px-4 py-3 text-sm text-center"
                                    >
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            @endif
        @endif
    </x-settings.layout>
</section>
