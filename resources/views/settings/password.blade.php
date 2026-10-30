@extends('layouts.app')

@section('title', 'Change password')

@section('content')
    <div class="max-w-xl">
        <h1 class="text-2xl font-semibold">Change password</h1>
        <p class="mt-2 text-sm text-gray-600">
            Use at least 8 characters with upper and lower case letters, a number and a symbol.
            Changing your password logs you out on your other devices.
        </p>

        <form method="POST" action="{{ route('password.update') }}"
              class="mt-6 space-y-5 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            @csrf
            @method('PUT')

            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700">Current password</label>
                <input id="current_password" name="current_password" type="password"
                       required autocomplete="current-password"
                       class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-300">
                <x-input-error for="current_password" />
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">New password</label>
                <input id="password" name="password" type="password"
                       required autocomplete="new-password"
                       class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-300">
                <x-input-error for="password" />
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm new password</label>
                <input id="password_confirmation" name="password_confirmation" type="password"
                       required autocomplete="new-password"
                       class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-300">
            </div>

            <button type="submit"
                    class="rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
                Change password
            </button>
        </form>
    </div>
@endsection
