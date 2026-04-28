<section class="w-full">
    @include('partials.settings-heading')

    <h2 class="sr-only">{{ __('Configuración del perfil') }}</h2>

    <x-settings.layout :heading="__('Perfil')" :subheading="__('Actualiza tu nombre y correo electrónico')">
        @if ($statusMessage)
            <div
                @class([
                    'mb-6 rounded-xl border px-4 py-3 text-sm font-medium',
                    'border-emerald-200 bg-emerald-50 text-emerald-700' => $statusType === 'success',
                    'border-amber-200 bg-amber-50 text-amber-700' => $statusType === 'warning',
                ])
            >
                {{ $statusMessage }}
            </div>
        @endif

        <form wire:submit="updateProfileInformation" class="space-y-6">
            <label class="block">
                <span class="mb-2 block text-sm font-medium text-foreground">{{ __('Nombre') }}</span>
                <input
                    wire:model="name"
                    type="text"
                    required
                    autofocus
                    autocomplete="name"
                    class="block w-full rounded-lg border border-layer-line bg-surface px-4 py-3 text-sm text-foreground focus:border-primary-focus focus:outline-hidden focus:ring-0"
                >
                @error('name')
                    <span class="mt-2 block text-sm text-red-600">{{ $message }}</span>
                @enderror
            </label>

            <div>
                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-foreground">{{ __('Correo electrónico') }}</span>
                    <input
                        wire:model="email"
                        type="email"
                        required
                        autocomplete="email"
                        class="block w-full rounded-lg border border-layer-line bg-surface px-4 py-3 text-sm text-foreground focus:border-primary-focus focus:outline-hidden focus:ring-0"
                    >
                    @error('email')
                        <span class="mt-2 block text-sm text-red-600">{{ $message }}</span>
                    @enderror
                </label>

                @if ($this->hasUnverifiedEmail)
                    <div class="mt-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                        <p>
                            {{ __('Tu dirección de correo electrónico no está verificada.') }}
                            <button
                                type="button"
                                wire:click.prevent="resendVerificationNotification"
                                class="ms-1 font-semibold underline decoration-amber-500 underline-offset-2 transition hover:text-amber-900"
                            >
                                {{ __('Haz clic aquí para reenviar el correo de verificación.') }}
                            </button>
                        </p>
                    </div>
                @endif
            </div>

            <div class="flex items-center gap-4">
                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded-lg bg-primary px-4 py-3 text-sm font-semibold text-white transition hover:bg-primary-hover"
                >
                    {{ __('Guardar') }}
                </button>
            </div>
        </form>

        @if ($this->showDeleteUser)
            <livewire:settings.delete-user-form />
        @endif
    </x-settings.layout>
</section>
