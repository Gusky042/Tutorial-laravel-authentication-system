@props(['label', 'value', 'delta', 'tone' => 'neutral', 'comparison' => '', 'trend' => []])

@php
    $min = min($trend);
    $range = max(max($trend) - $min, 1);
    $count = count($trend);
    // Leave 10% padding top and bottom so the line never touches the edge.
    $y = fn ($v) => round(90 - (($v - $min) / $range) * 80, 2);
    $points = collect($trend)
        ->map(fn ($v, $i) => round($i / ($count - 1) * 100, 2).','.$y($v))
        ->implode(' ');
    $lastY = $y(end($trend));

    $deltaClass = match ($tone) {
        'good' => 'text-[#006300]',
        'bad' => 'text-[#d03b3b]',
        default => 'text-gray-600',
    };
@endphp

<div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
    <p class="text-sm text-gray-600">{{ $label }}</p>
    <p class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">{{ $value }}</p>

    <p class="mt-1 text-sm">
        <span class="font-medium {{ $deltaClass }}">{{ $delta }}</span>
        <span class="text-gray-500">{{ $comparison }}</span>
    </p>

    {{-- 12-month trend. Decorative: the tile's text already states the figure. --}}
    <div class="relative mt-4 h-8" aria-hidden="true">
        <svg viewBox="0 0 100 100" preserveAspectRatio="none" class="absolute inset-0 h-full w-full overflow-visible">
            <polyline points="{{ $points }}" fill="none" stroke="#a3a29b" stroke-width="2"
                      stroke-linejoin="round" stroke-linecap="round" vector-effect="non-scaling-stroke" />
        </svg>
        <span class="absolute right-0 size-2.5 -translate-y-1/2 translate-x-1/2 rounded-full ring-2 ring-white"
              style="top: {{ $lastY }}%; background-color: {{ \App\Support\ErpDemoData::BLUE }}"></span>
    </div>
</div>
