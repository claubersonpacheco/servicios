<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ __('Welcome') }} - {{ config('app.name', 'Laravel') }}</title>

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
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="px-5 py-2 text-sm border rounded">
                        Log In
                    </a>


                @endauth
            </nav>
        @endif
    </header>

    <!-- HERO STRIPE STYLE -->
    <!-- HERO SaaS Gestão de Serviços -->
<div class="relative overflow-hidden bg-white dark:bg-[#0a0a0a]">

    <!-- Background glow -->
    <div class="absolute inset-0 -z-10">
        <div class="absolute top-[-180px] left-1/2 -translate-x-1/2 w-[600px] h-[600px] bg-blue-500/20 blur-[120px] rounded-full"></div>
        <div class="absolute bottom-[-200px] right-[-120px] w-[500px] h-[500px] bg-indigo-500/20 blur-[120px] rounded-full"></div>
    </div>

    <div class="relative z-10 max-w-6xl mx-auto px-6 lg:px-8 py-20 lg:py-28">

        <!-- Badge -->
        <div class="flex justify-center mb-6">
            <span class="px-4 py-1 text-sm rounded-full border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 bg-white/60 dark:bg-white/5 backdrop-blur">
                ⚙️ Plataforma de Gestão de Serviços
            </span>
        </div>

        <!-- Title -->
        <h1 class="text-center text-4xl md:text-6xl font-semibold tracking-tight text-gray-900 dark:text-white">
            Organiza todos os teus serviços em
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-500">
                um único sistema
            </span>
        </h1>

        <!-- Subtitle -->
        <p class="mt-6 text-center text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
            Gere clientes, orçamentos, tarefas e equipas de forma simples, rápida e centralizada.
        </p>

        <!-- CTA -->
        <div class="mt-10 flex justify-center gap-4 flex-wrap">
            <a href="#"
               class="px-6 py-3 rounded-xl bg-blue-600 text-white font-medium hover:bg-blue-700 transition">
                Começar agora
            </a>

            <a href="#"
               class="px-6 py-3 rounded-xl border border-gray-300 dark:border-gray-700 text-gray-800 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5 transition">
                Ver demonstração
            </a>
        </div>

        <!-- Preview -->
        <div class="mt-14 flex justify-center">
            <div class="w-full max-w-4xl rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-white/5 shadow-xl overflow-hidden">

                <div class="p-4 border-b border-gray-200 dark:border-gray-800 flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-red-400"></span>
                    <span class="w-3 h-3 rounded-full bg-yellow-400"></span>
                    <span class="w-3 h-3 rounded-full bg-green-400"></span>
                </div>

                <div class="p-10 space-y-3">
                    <div class="h-4 w-48 bg-gray-200 dark:bg-gray-800 rounded"></div>
                    <div class="h-3 w-full bg-gray-100 dark:bg-gray-800 rounded"></div>
                    <div class="h-3 w-5/6 bg-gray-100 dark:bg-gray-800 rounded"></div>
                    <div class="h-3 w-3/4 bg-gray-100 dark:bg-gray-800 rounded"></div>
                </div>

            </div>
        </div>

    </div>
</div>

</main>

<!-- FOOTER -->
<footer class="mt-auto w-full max-w-6xl mx-auto py-10 px-6 text-center">
    <p class="text-gray-500">
        <a href="{{ route('privacy') }}" class="text-blue-600 hover:underline">Privacy</a>
    </p>
    <p class="text-gray-500 mt-2">© 2026 Organizarte.es</p>
</footer>

</body>
</html>
