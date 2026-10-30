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
    <div class="flex min-h-screen flex-col items-center justify-center px-4 py-10">
        <a href="{{ url('/') }}" class="mb-6 text-2xl font-semibold tracking-tight text-gray-900">
            {{ config('app.name') }}
        </a>

        <main class="w-full max-w-md rounded-xl border border-gray-200 bg-white p-6 shadow-sm sm:p-8">
            <x-flash />

            @yield('content')
        </main>
    </div>
</body>
</html>
