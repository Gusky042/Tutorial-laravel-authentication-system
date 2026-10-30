{{--
    Layout for pages built with Bootstrap 5. It loads only Bootstrap, not Tailwind.
    Use it in a new view with: @extends('layouts.bootstrap')
--}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@hasSection('title')@yield('title') - @endif{{ config('app.name') }}</title>

    @vite(['resources/js/bootstrap-ui.js'])
</head>
<body class="bg-light">
    <main class="container py-4">
        @if (request()->hasSession() && session('status'))
            <div class="alert alert-success" role="status">{{ session('status') }}</div>
        @endif

        @if (request()->hasSession() && session('error'))
            <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
        @endif

        @yield('content')
    </main>
</body>
</html>
