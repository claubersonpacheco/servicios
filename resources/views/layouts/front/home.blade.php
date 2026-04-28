<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ __('Bienvenido') }} - {{ config('app.name', 'Laravel') }}</title>

    <link rel="icon" href="/favicon.ico">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @livewireStyles
</head>

<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex flex-col min-h-screen">

<main class="flex-1">

    <!-- HEADER -->
    <header class="w-full max-w-4xl mx-auto px-6 py-6 flex justify-end">
        @if (Route::has('login'))
            <nav class="flex items-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}"
                       class="px-5 py-2 border rounded text-sm dark:text-white">
                        Panel
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="px-5 py-2 text-sm border rounded">
                        Iniciar sesión
                    </a>
                @endauth
            </nav>
        @endif
    </header>

    <!-- HERO -->
   {{ $slot }}

</main>

<!-- FOOTER -->
<footer class="mt-auto w-full max-w-6xl mx-auto py-10 px-6 text-center">
    <p class="text-gray-500">
        <a href="{{ route('privacy') }}" class="text-blue-600 hover:underline">Privacidad</a>
    </p>
    <p class="text-gray-500 mt-2">© 2026 Organizarte.es</p>
</footer>

</body>
</html>
