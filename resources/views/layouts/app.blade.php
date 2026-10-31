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
<body class="group/body min-h-screen bg-[#f4f5f7] font-sans text-gray-900 antialiased" data-sidebar="closed">
    @php
        $navItem = 'flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium';
    @endphp

    {{-- Dark backdrop behind the sidebar on small screens --}}
    <div data-sidebar-close class="fixed inset-0 z-30 hidden bg-gray-900/40 group-data-[sidebar=open]/body:block lg:hidden!"></div>

    <aside id="sidebar"
           class="fixed inset-y-0 left-0 z-40 flex w-64 -translate-x-full flex-col overflow-y-auto bg-[#f4f5f7] px-4 pb-6 transition-transform group-data-[sidebar=open]/body:translate-x-0 lg:translate-x-0 print:hidden">
        <a href="{{ route('dashboard') }}" class="flex h-24 shrink-0 items-center rounded-lg px-3 focus:outline-none focus-visible:ring-2 focus-visible:ring-gray-400">
            <x-logo tagline gap="#f4f5f7" />
        </a>

        <nav aria-label="Main" class="mt-2 flex-1 space-y-6">
            <div class="rounded-2xl bg-white p-2 shadow-sm">
                <a href="{{ route('dashboard') }}"
                   @if (request()->routeIs('dashboard')) aria-current="page" @endif
                   class="{{ $navItem }} {{ request()->routeIs('dashboard') ? 'bg-[#1f3bb3]/10 text-[#1f3bb3]' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.750A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                    </svg>
                    Dashboard
                </a>
            </div>

            <div>
                <p class="px-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Modules</p>
                <ul class="mt-2 space-y-1">
                    @foreach ([
                        'Sales' => 'M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z',
                        'Purchasing' => 'M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25Z',
                        'Inventory' => 'm21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9',
                        'Finance' => 'M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z',
                        'Human resources' => 'M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z',
                    ] as $module => $icon)
                        <li>
                            <span class="{{ $navItem }} cursor-default text-gray-500" aria-disabled="true">
                                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}" />
                                </svg>
                                {{ $module }}
                                <span class="ml-auto rounded-full border border-[#1f3bb3]/40 px-2 py-0.5 text-[11px] font-semibold text-[#1f3bb3]">Soon</span>
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div>
                <p class="px-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Account</p>
                <ul class="mt-2 space-y-1">
                    <li>
                        <a href="{{ route('password.edit') }}"
                           @if (request()->routeIs('password.edit')) aria-current="page" @endif
                           class="{{ $navItem }} {{ request()->routeIs('password.edit') ? 'bg-white text-[#1f3bb3] shadow-sm' : 'text-gray-700 hover:bg-white' }}">
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                            </svg>
                            Change password
                        </a>
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="{{ $navItem }} w-full text-gray-700 hover:bg-white">
                                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                                </svg>
                                Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </nav>
    </aside>

    <div class="lg:pl-64 print:pl-0">
        <header class="px-4 pt-5 sm:px-8">
            <div class="flex flex-wrap items-center gap-x-6 gap-y-4">
                <button type="button" data-sidebar-toggle aria-controls="sidebar" aria-expanded="false"
                        class="rounded-md p-2 text-gray-700 hover:bg-white focus:outline-none focus:ring-2 focus:ring-gray-400 lg:hidden">
                    <span class="sr-only">Open menu</span>
                    <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>

                <div class="min-w-0 flex-1">
                    @yield('header')
                </div>

                <div class="flex items-center gap-2 sm:gap-4 print:hidden">
                    <span class="hidden items-center gap-2 rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-700 md:inline-flex">
                        <svg class="size-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                        </svg>
                        <time datetime="{{ now()->toDateString() }}">{{ now()->format('j M Y') }}</time>
                    </span>

                    {{-- Notifications --}}
                    <details data-dropdown class="relative">
                        <summary class="relative flex cursor-pointer list-none rounded-full p-2 text-gray-700 hover:bg-white focus:outline-none focus-visible:ring-2 focus-visible:ring-gray-400 [&::-webkit-details-marker]:hidden">
                            <span class="sr-only">Notifications</span>
                            <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                            </svg>
                            @hasSection('notifications')
                                <span class="absolute right-2 top-2 size-2 rounded-full bg-[#d03b3b] ring-2 ring-[#f4f5f7]"></span>
                            @endif
                        </summary>
                        <div class="absolute right-0 z-20 mt-2 w-72 rounded-xl border border-gray-200 bg-white p-2 shadow-lg">
                            <p class="px-3 py-2 text-sm font-semibold">Notifications</p>
                            @hasSection('notifications')
                                @yield('notifications')
                            @else
                                <p class="px-3 pb-3 text-sm text-gray-500">You're all caught up.</p>
                            @endif
                        </div>
                    </details>

                    {{-- Account menu --}}
                    <details data-dropdown class="relative">
                        <summary class="flex cursor-pointer list-none rounded-full focus:outline-none focus-visible:ring-2 focus-visible:ring-gray-400 [&::-webkit-details-marker]:hidden">
                            <span class="sr-only">Account menu</span>
                            <x-avatar :name="Auth::user()->name" size="size-11" class="bg-[#1f3bb3]! text-white!" />
                        </summary>
                        <div class="absolute right-0 z-20 mt-2 w-64 rounded-xl border border-gray-200 bg-white p-2 shadow-lg">
                            <div class="px-3 py-2">
                                <p class="truncate text-sm font-semibold">{{ Auth::user()->name }}</p>
                                <p class="truncate text-sm text-gray-500">{{ Auth::user()->email }}</p>
                            </div>
                            <hr class="my-1 border-gray-100">
                            <a href="{{ route('password.edit') }}" class="block rounded-lg px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">Change password</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full rounded-lg px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-100">Logout</button>
                            </form>
                        </div>
                    </details>
                </div>
            </div>
        </header>

        <main class="px-4 py-6 sm:px-8">
            <div class="@yield('width', 'max-w-5xl')">
                <x-flash />

                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
