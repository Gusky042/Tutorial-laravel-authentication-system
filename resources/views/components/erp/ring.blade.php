@props(['label', 'percent', 'color' => \App\Support\ErpDemoData::BLUE, 'track' => '#dbe3fb'])

@php
    $radius = 15;
    $circumference = 2 * M_PI * $radius;
@endphp

<div class="flex items-center gap-3">
    {{-- Decorative: the percentage is written next to it --}}
    <svg viewBox="0 0 40 40" class="size-14 shrink-0 -rotate-90" aria-hidden="true">
        <circle cx="20" cy="20" r="{{ $radius }}" fill="none" stroke="{{ $track }}" stroke-width="5" />
        <circle cx="20" cy="20" r="{{ $radius }}" fill="none" stroke="{{ $color }}" stroke-width="5" stroke-linecap="round"
                stroke-dasharray="{{ round($percent / 100 * $circumference, 3) }} {{ round($circumference, 3) }}" />
    </svg>
    <div>
        <p class="text-sm text-gray-600">{{ $label }}</p>
        <p class="text-xl font-bold tracking-tight">{{ $percent }}%</p>
    </div>
</div>
