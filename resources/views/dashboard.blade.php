@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h1 class="text-2xl font-semibold">Welcome, {{ $user->name }}</h1>
    <p class="mt-1 text-sm text-gray-600">Here is a summary of your account.</p>

    <section class="mt-6 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-500">Your account</h2>

        <dl class="mt-4 grid gap-4 sm:grid-cols-3">
            <div>
                <dt class="text-sm text-gray-500">Email</dt>
                <dd class="mt-1 break-all font-medium">{{ $user->email }}</dd>
            </div>

            <div>
                <dt class="text-sm text-gray-500">Last login</dt>
                <dd class="mt-1 font-medium">
                    @if ($previousLoginAt)
                        <time datetime="{{ $previousLoginAt->toIso8601String() }}">
                            {{ $previousLoginAt->format('j M Y, H:i') }} {{ config('app.timezone') }}
                        </time>
                    @else
                        This is your first login
                    @endif
                </dd>
            </div>

            <div>
                <dt class="text-sm text-gray-500">Account created</dt>
                <dd class="mt-1 font-medium">
                    <time datetime="{{ $user->created_at?->toIso8601String() }}">
                        {{ $user->created_at?->format('j M Y') }}
                    </time>
                </dd>
            </div>
        </dl>
    </section>

    <section class="mt-6">
        <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-500">Quick actions</h2>

        <div class="mt-3 grid gap-4 sm:grid-cols-2">
            <a href="{{ route('password.edit') }}"
               class="block rounded-xl border border-gray-200 bg-white p-5 shadow-sm hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-400">
                <span class="font-medium">Change password</span>
                <span class="mt-1 block text-sm text-gray-600">Choose a new password for your account.</span>
            </a>

            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <span class="font-medium">Log out</span>
                <span class="mt-1 block text-sm text-gray-600">End your session on this device.</span>

                <form method="POST" action="{{ route('logout') }}" class="mt-3">
                    @csrf
                    <button type="submit"
                            class="rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </section>
@endsection
