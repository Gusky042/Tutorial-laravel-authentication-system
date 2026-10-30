<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@hasSection('title')@yield('title') - @endif{{ config('app.name') }}</title>

    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 font-sans text-gray-900 antialiased">
    <header class="border-b border-gray-200 bg-white">
        <div class="mx-auto flex max-w-5xl flex-wrap items-center justify-between gap-x-6 gap-y-3 px-4 py-3">
            <a href="{{ url('/') }}" class="text-lg font-semibold tracking-tight text-gray-900">
                {{ config('app.name') }}
            </a>

            @auth
                <nav class="flex flex-wrap items-center gap-x-5 gap-y-2 text-sm">
                    <a href="{{ route('dashboard') }}"
                       class="{{ request()->routeIs('dashboard') ? 'font-semibold text-gray-900' : 'text-gray-600 hover:text-gray-900' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('password.edit') }}"
                       class="{{ request()->routeIs('password.edit') ? 'font-semibold text-gray-900' : 'text-gray-600 hover:text-gray-900' }}">
                        Change password
                    </a>

                    <span class="text-gray-500">{{ Auth::user()->name }}</span>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="rounded-md border border-gray-300 px-3 py-1.5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-400">
                            Logout
                        </button>
                    </form>
                </nav>
            @endauth
        </div>
    </header>

    <main class="mx-auto max-w-5xl px-4 py-8">
        <x-flash />

        @yield('content')
    </main>
</body>
</html>
