<x-layouts::auth :title="__('Autenticación de dos factores')">
    <div class="flex flex-col gap-6">
        <div
            class="relative w-full h-auto"
            x-cloak
            x-data="{
                showRecoveryInput: @js($errors->has('recovery_code')),
                code: '',
                recovery_code: '',
                toggleInput() {
                    this.showRecoveryInput = !this.showRecoveryInput;

                    this.code = '';
                    this.recovery_code = '';

                    $dispatch('clear-2fa-auth-code');

                    $nextTick(() => {
                        this.showRecoveryInput
                            ? this.$refs.recovery_code?.focus()
                            : $dispatch('focus-2fa-auth-code');
                    });
                },
            }"
        >
            <div x-show="!showRecoveryInput">
                <x-auth-header
                    :title="__('Código de autenticación')"
                    :description="__('Introduce el código de autenticación proporcionado por tu aplicación autenticadora.')"
                />
            </div>

            <div x-show="showRecoveryInput">
                <x-auth-header
                    :title="__('Código de recuperación')"
                    :description="__('Confirma el acceso a tu cuenta introduciendo uno de tus códigos de recuperación de emergencia.')"
                />
            </div>

            <form method="POST" action="{{ route('two-factor.login.store') }}">
                @csrf

                <div class="space-y-5 text-center">
                    <div x-show="!showRecoveryInput">
                        <div class="flex items-center justify-center my-5">
                            <input
                                type="text"
                                inputmode="numeric"
                                name="code"
                                x-model="code"
                                maxlength="6"
                                autocomplete="one-time-code"
                                class="block mx-auto w-64 rounded-lg border border-layer-line bg-surface px-4 py-3 text-center text-sm text-foreground focus:border-primary-focus focus:outline-hidden focus:ring-0"
                            />
                        </div>
                    </div>

                    <div x-show="showRecoveryInput">
                        <div class="my-5">
                            <input
                                type="text"
                                name="recovery_code"
                                x-ref="recovery_code"
                                x-bind:required="showRecoveryInput"
                                autocomplete="one-time-code"
                                x-model="recovery_code"
                                class="block w-full rounded-lg border border-layer-line bg-surface px-4 py-3 text-sm text-foreground focus:border-primary-focus focus:outline-hidden focus:ring-0"
                            />
                        </div>

                        @error('recovery_code')
                            <p class="text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <button
                        type="submit"
                        class="inline-flex w-full items-center justify-center rounded-lg bg-primary px-4 py-3 text-sm font-semibold text-white transition hover:bg-primary-hover"
                    >
                        {{ __('Continuar') }}
                    </button>
                </div>

                <div class="mt-5 space-x-0.5 text-sm leading-5 text-center">
                    <span class="opacity-50">{{ __('o puedes') }}</span>
                    <div class="inline font-medium underline cursor-pointer opacity-80">
                        <span x-show="!showRecoveryInput" @click="toggleInput()">
                            {{ __('iniciar sesión usando un código de recuperación') }}
                        </span>
                        <span x-show="showRecoveryInput" @click="toggleInput()">
                            {{ __('iniciar sesión usando un código de autenticación') }}
                        </span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-layouts::auth>
