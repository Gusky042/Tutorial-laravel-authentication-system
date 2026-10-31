{{--
    Browser tab and home-screen icons (the Corevia cube). Files live in public/.
    The ?v= value changes when a file changes, so browsers drop their cached copy.
--}}
@php
    $icon = fn (string $file) => asset($file).'?v='.(@filemtime(public_path($file)) ?: '1');
@endphp
<link rel="icon" href="{{ $icon('favicon.svg') }}" type="image/svg+xml">
<link rel="icon" href="{{ $icon('favicon.ico') }}" sizes="32x32">
<link rel="apple-touch-icon" href="{{ $icon('apple-touch-icon.png') }}">
<meta name="theme-color" content="#131f3b">
