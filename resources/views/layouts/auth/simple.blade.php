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

        <!-- CARD -->
        <div class="bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-800 rounded-2xl shadow-sm p-8">

            <!-- HEADER -->
            <div class="mb-6 text-center">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
                    Log in to your account
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                    Enter your email and password below to log in
                </p>
            </div>

            <!-- STATUS -->
            @if (session('status'))
                <div class="mb-4 text-sm text-center text-green-600">
                    {{ session('status') }}
                </div>
            @endif

            <!-- FORM -->
            <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
                @csrf

                <!-- EMAIL -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Email address
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
                            Password
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                               class="text-sm text-blue-600 hover:underline">
                                Forgot password?
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
                        Remember me
                    </label>
                </div>

                <!-- BUTTON -->
                <button
                    type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-lg transition"
                >
                    Log in
                </button>
            </form>

        </div>



    </div>

</div>

</body>
</html>
