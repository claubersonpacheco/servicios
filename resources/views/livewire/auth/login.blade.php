<x-layouts::auth :title="__('Acceder')">
     <!-- CARD -->
        <div class="bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-800 rounded-2xl shadow-sm p-8">

            <!-- HEADER -->
            <div class="mb-6 text-center">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
                    Accede su cuenta
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                    Introduce tu correo electrónico y contraseña abajo para iniciar sesión.
                </p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger text-center p-2 border rounded bg-red-200 mb-3">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif



            <!-- FORM -->
            <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
                @csrf

                <!-- EMAIL -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Correo eletrónico
                    </label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        placeholder="email@example.com"
                        class="mt-1 w-full rounded-lg border border-gray-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 text-gray-900 dark:text-white px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />
                </div>

                <!-- PASSWORD -->
                <div>
                    <div class="flex justify-between items-center">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Clave
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                               class="text-sm text-blue-600 hover:underline">
                                ¿Olvidaste tu contraseña?
                            </a>
                        @endif
                    </div>

                    <input
                        type="password"
                        name="password"
                        required
                        placeholder="••••••••"
                        class="mt-1 w-full rounded-lg border border-gray-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 text-gray-900 dark:text-white px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />
                </div>

                <!-- REMEMBER -->
                <div class="flex items-center">
                    <input
                        type="checkbox"
                        name="remember"
                        class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                    />
                    <label class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                        Recuérdame
                    </label>
                </div>

                <!-- BUTTON -->
                <button
                    type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-lg transition"
                >
                    Iniciar sesión
                </button>
            </form>

        </div>
</x-layouts::auth>
