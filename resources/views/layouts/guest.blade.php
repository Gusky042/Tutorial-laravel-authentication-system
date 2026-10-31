<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@hasSection('title')@yield('title') - @endif{{ config('app.name') }}</title>

    <x-favicons />
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 font-sans text-gray-900 antialiased">
    <div class="flex min-h-screen flex-col items-center justify-center px-4 py-10">
        <a href="{{ url('/') }}" class="mb-8 rounded-lg focus:outline-none focus-visible:ring-2 focus-visible:ring-gray-400">
            <x-logo size="lg" tagline gap="#f9fafb" />
        </a>

        <main class="w-full max-w-md rounded-xl border border-gray-200 bg-white p-6 shadow-sm sm:p-8">
            <x-flash />

            @yield('content')
        </main>
    </div>
</body>
</html>
