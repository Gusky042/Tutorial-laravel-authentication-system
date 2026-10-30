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
            <input id="password" name="password" type="password"
                   required autocomplete="new-password"
                   class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-300">
            <p class="mt-1 text-xs text-gray-500">
                At least 8 characters with upper and lower case letters, a number and a symbol.
            </p>
            <x-input-error for="password" />
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm password</label>
            <input id="password_confirmation" name="password_confirmation" type="password"
                   required autocomplete="new-password" aria-describedby="password-match"
                   class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-300">

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
