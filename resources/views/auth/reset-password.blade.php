@extends('layouts.guest')

@section('title', 'Reset password')

@section('content')
    <h1 class="text-xl font-semibold">Choose a new password</h1>
    <p class="mt-2 text-sm text-gray-600">
        Use at least 8 characters with upper and lower case letters, a number and a symbol.
    </p>

    <form method="POST" action="{{ route('password.store') }}" class="mt-6 space-y-5">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email', $email) }}"
                   required autocomplete="username"
                   class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-300">
            <x-input-error for="email" />
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">New password</label>
            <div class="relative mt-1">
                <input id="password" name="password" type="password"
                       required autofocus autocomplete="new-password"
                       class="block w-full rounded-md border border-gray-300 px-3 py-2 pr-16 shadow-sm focus:border-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-300">
                <x-password-toggle for="password" label="new password" />
            </div>
            <x-input-error for="password" />
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm new password</label>
            <div class="relative mt-1">
                <input id="password_confirmation" name="password_confirmation" type="password"
                       required autocomplete="new-password"
                       class="block w-full rounded-md border border-gray-300 px-3 py-2 pr-16 shadow-sm focus:border-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-300">
                <x-password-toggle for="password_confirmation" label="confirm new password" />
            </div>
        </div>

        <button type="submit"
                class="w-full rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
            Reset password
        </button>
    </form>
@endsection
