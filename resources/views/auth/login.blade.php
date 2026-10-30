@extends('layouts.guest')

@section('title', 'Log in')

@section('content')
    <h1 class="text-xl font-semibold">Log in</h1>

    <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}"
                   required autofocus autocomplete="username"
                   class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-300">
            <x-input-error for="email" />
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input id="password" name="password" type="password"
                   required autocomplete="current-password"
                   class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-300">
            <x-input-error for="password" />
        </div>

        <div class="flex items-center justify-between">
            <label for="remember" class="flex items-center gap-2 text-sm text-gray-700">
                <input id="remember" name="remember" type="checkbox" value="1" @checked(old('remember'))
                       class="h-4 w-4 rounded border-gray-300">
                Remember me
            </label>

            {{-- Phase 4: point this at route('password.request') --}}
            <a href="#" class="text-sm text-gray-600 underline hover:text-gray-900">
                Forgot your password?
            </a>
        </div>

        <button type="submit"
                class="w-full rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
            Log in
        </button>
    </form>
@endsection
