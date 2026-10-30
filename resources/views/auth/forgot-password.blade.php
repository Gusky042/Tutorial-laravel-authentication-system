@extends('layouts.guest')

@section('title', 'Forgot password')

@section('content')
    <h1 class="text-xl font-semibold">Forgot your password?</h1>
    <p class="mt-2 text-sm text-gray-600">
        Enter your email address and we will send you a link to choose a new password.
    </p>

    <form method="POST" action="{{ route('password.email') }}" class="mt-6 space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}"
                   required autofocus autocomplete="username"
                   class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-300">
            <x-input-error for="email" />
        </div>

        <button type="submit"
                class="w-full rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
            Email password reset link
        </button>
    </form>

    <p class="mt-6 text-center text-sm">
        <a href="{{ route('login') }}" class="text-gray-600 underline hover:text-gray-900">Back to log in</a>
    </p>
@endsection
