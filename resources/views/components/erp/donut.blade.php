@props(['items', 'caption', 'centerLabel' => 'Total'])
@use('App\Support\ErpDemoData')

@php
    $total = array_sum(array_column($items, 'value'));
    $radius = 48;
    $circumference = 2 * M_PI * $radius;
    $gap = 1.2; // about 2px at the rendered size
    $offset = 0;
@endphp

<figure data-chart class="relative">
    <figcaption class="sr-only">{{ $caption }}</figcaption>

    <div class="relative mx-auto aspect-square w-full max-w-60">
        <svg viewBox="0 0 120 120" class="h-full w-full -rotate-90">
            @foreach ($items as $item)
                @php
                    $share = $item['value'] / $total;
                    $length = max($share * $circumference - $gap, 0);
                    $percent = round($share * 100, 1);
                @endphp
                <circle cx="60" cy="60" r="{{ $radius }}" fill="none" stroke="{{ $item['color'] }}" stroke-width="18"
                        stroke-dasharray="{{ round($length, 3) }} {{ round($circumference, 3) }}"
                        stroke-dashoffset="{{ round(-$offset, 3) }}"
                        tabindex="0" class="cursor-default outline-none transition-opacity hover:opacity-80 focus-visible:opacity-80"
                        data-tip="{{ json_encode(['title' => $item['label'], 'rows' => [
                            ['label' => 'Amount', 'value' => ErpDemoData::money($item['value']), 'color' => $item['color']],
                            ['label' => 'Share', 'value' => $percent.'%'],
                        ]]) }}"
                        aria-label="{{ $item['label'] }}: {{ ErpDemoData::money($item['value']) }}, {{ $percent }}%" />
                @php $offset += $share * $circumference; @endphp
            @endforeach
        </svg>

        <div class="pointer-events-none absolute inset-0 flex flex-col items-center justify-center">
            <span class="text-2xl font-bold tracking-tight">{{ ErpDemoData::money($total) }}</span>
            <span class="text-xs text-gray-500">{{ $centerLabel }}</span>
        </div>
    </div>

    <ul class="mt-5 grid grid-cols-2 gap-x-4 gap-y-3 text-sm">
        @foreach ($items as $item)
            <li class="flex items-start gap-2">
                <span class="mt-1.5 size-2.5 shrink-0 rounded-full" style="background-color: {{ $item['color'] }}"></span>
                <span>
                    <span class="block text-gray-600">{{ $item['label'] }}</span>
                    <span class="font-semibold tabular-nums text-gray-900">{{ ErpDemoData::money($item['value']) }}</span>
                    <span class="tabular-nums text-gray-500">· {{ round($item['value'] / $total * 100) }}%</span>
                </span>
            </li>
        @endforeach
    </ul>

    <x-erp.chart-tooltip />
</figure>
