<section class="mt-10 space-y-6">
    <div class="relative mb-5">
        <h3 class="text-lg font-semibold text-foreground">{{ __('Eliminar cuenta') }}</h3>
        <p class="mt-2 text-sm text-muted-foreground">{{ __('Elimina tu cuenta y todos sus recursos') }}</p>
    </div>

    <button
        type="button"
        wire:click="confirmUserDeletion"
        class="inline-flex items-center justify-center rounded-lg bg-red-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-red-700"
    >
        {{ __('Eliminar cuenta') }}
    </button>

    @if ($confirmingUserDeletion)
        <div class="fixed inset-0 z-80 overflow-y-auto">
            <div class="fixed inset-0 bg-slate-900/50" wire:click="closeDeleteModal"></div>

            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative z-10 w-full max-w-lg rounded-2xl border border-layer-line bg-layer shadow-xl">
                    <form wire:submit="deleteUser" class="space-y-6 px-6 py-6">
                        <div>
                            <h4 class="text-lg font-semibold text-foreground">{{ __('¿Estás seguro de que quieres eliminar tu cuenta?') }}</h4>
                            <p class="mt-2 text-sm leading-6 text-muted-foreground">
                                {{ __('Una vez que tu cuenta sea eliminada, todos sus recursos y datos serán eliminados permanentemente. Por favor, introduce tu contraseña para confirmar que deseas eliminar tu cuenta de forma permanente.') }}
                            </p>
                        </div>

                        <label class="block">
                            <span class="mb-2 block text-sm font-medium text-foreground">{{ __('Contraseña') }}</span>
                            <input
                                wire:model="password"
                                type="password"
                                autocomplete="current-password"
                                class="block w-full rounded-lg border border-layer-line bg-surface px-4 py-3 text-sm text-foreground focus:border-primary-focus focus:outline-hidden focus:ring-0"
                            >
                            @error('password')
                                <span class="mt-2 block text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </label>

                        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                            <button
                                type="button"
                                wire:click="closeDeleteModal"
                                class="inline-flex items-center justify-center rounded-lg border border-layer-line bg-surface px-4 py-3 text-sm font-semibold text-foreground transition hover:bg-muted-hover"
                            >
                                {{ __('Cancelar') }}
                            </button>

                            <button
                                type="submit"
                                class="inline-flex items-center justify-center rounded-lg bg-red-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-red-700"
                            >
                                {{ __('Eliminar cuenta') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</section>
