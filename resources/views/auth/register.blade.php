@extends('layouts.guest')

@section('title', 'Create an account')

@section('content')
    <h1 class="text-xl font-semibold">Create an account</h1>

    <form method="POST" action="{{ route('register.store') }}" class="mt-6 space-y-5">
        @csrf

        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}"
                   required autofocus autocomplete="name" maxlength="255"
                   class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-300">
            <x-input-error for="name" />
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}"
                   required autocomplete="username" maxlength="255"
                   class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-300">
            <x-input-error for="email" />
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <div class="relative mt-1">
                <input id="password" name="password" type="password"
                       required autocomplete="new-password"
                       class="block w-full rounded-md border border-gray-300 px-3 py-2 pr-16 shadow-sm focus:border-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-300">
                {{-- Shown by resources/js/app.js; stays hidden if JavaScript is off. --}}
                <button type="button" data-toggle-password="password" aria-controls="password"
                        aria-pressed="false" aria-label="Show password"
                        class="absolute inset-y-0 right-0 hidden px-3 text-sm font-medium text-gray-600 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-400 rounded-r-md">
                    Show
                </button>
            </div>
            <p class="mt-1 text-xs text-gray-500">
                At least 8 characters with upper and lower case letters, a number and a symbol.
            </p>
            <x-input-error for="password" />
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm password</label>
            <div class="relative mt-1">
                <input id="password_confirmation" name="password_confirmation" type="password"
                       required autocomplete="new-password" aria-describedby="password-match"
                       class="block w-full rounded-md border border-gray-300 px-3 py-2 pr-16 shadow-sm focus:border-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-300">
                <button type="button" data-toggle-password="password_confirmation" aria-controls="password_confirmation"
                        aria-pressed="false" aria-label="Show confirm password"
                        class="absolute inset-y-0 right-0 hidden px-3 text-sm font-medium text-gray-600 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-400 rounded-r-md">
                    Show
                </button>
            </div>

            {{-- Live match indicator, driven by resources/js/app.js. The server still checks 'confirmed'. --}}
            <p id="password-match" class="mt-1 text-sm" aria-live="polite"
               data-password-match data-password="password" data-confirmation="password_confirmation">
                <span data-match class="hidden font-medium text-green-700">&#10003; Passwords match</span>
                <span data-mismatch class="hidden font-medium text-red-600">&#10007; Passwords do not match</span>
            </p>
        </div>

        <button type="submit"
                class="w-full rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
            Register
        </button>
    </form>

    <div class="mt-6 border-t border-gray-200 pt-6">
        <p class="text-center text-sm text-gray-600">Already have an account?</p>

        <a href="{{ route('login') }}"
           class="mt-3 block w-full rounded-md border border-gray-300 bg-white px-4 py-2 text-center text-sm font-medium text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
            Login
        </a>
    </div>
@endsection
