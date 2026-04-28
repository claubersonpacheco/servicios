<x-layouts::auth :title="__('Verificación de correo electrónico')">
    <div class="mt-4 flex flex-col gap-6">
        <p class="text-center text-sm text-muted-foreground">
            {{ __('Por favor, verifica tu dirección de correo electrónico haciendo clic en el enlace que te acabamos de enviar.') }}
        </p>

        @if (session('status') == 'verification-link-sent')
            <p class="text-center text-sm font-medium text-green-600">
                {{ __('Se ha enviado un nuevo enlace de verificación a la dirección de correo electrónico que proporcionaste durante el registro.') }}
            </p>
        @endif

        <div class="flex flex-col items-center justify-between space-y-3">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button
                    type="submit"
                    class="inline-flex w-full items-center justify-center rounded-lg bg-primary px-4 py-3 text-sm font-semibold text-white transition hover:bg-primary-hover"
                >
                    {{ __('Reenviar correo de verificación') }}
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    type="submit"
                    class="text-sm font-medium text-muted-foreground transition hover:text-foreground"
                >
                    {{ __('Cerrar sesión') }}
                </button>
            </form>
        </div>
    </div>
</x-layouts::auth>
