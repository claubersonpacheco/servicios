<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-neutral-950 antialiased">

<div class="min-h-screen flex items-center justify-center px-6 py-12">

    <div class="w-full max-w-md">

        <!-- LOGO -->
        <div class="flex flex-col items-center mb-8">
            <a href="{{ route('home') }}" class="flex flex-col items-center gap-2">
                <span class="h-10 w-10 flex items-center justify-center rounded-lg">
                    <x-app-logo-icon class="h-10 w-10 text-black dark:text-white" />
                </span>
                <span class="text-sm text-gray-500 dark:text-gray-400">
                    {{ config('app.name', 'Laravel') }}
                </span>
            </a>
        </div>

        {{ $slot }}

    </div>

</div>

</body>
</html>
