@props(['items', 'caption'])

@php
    $total = array_sum(array_column($items, 'value'));
    $last = count($items) - 1;
@endphp

<figure data-chart class="relative">
    <figcaption class="sr-only">{{ $caption }}</figcaption>

    {{-- One bar split into stages. The 2px gaps separate the segments. --}}
    <div class="flex h-6 gap-0.5">
        @foreach ($items as $i => $item)
            @php $share = round($item['value'] / $total * 100, 1); @endphp
            <div tabindex="0"
                 class="h-full focus:outline-none focus-visible:ring-2 focus-visible:ring-gray-400 focus-visible:ring-offset-2 {{ $i === 0 ? 'rounded-l' : '' }} {{ $i === $last ? 'rounded-r' : '' }}"
                 style="flex: {{ $item['value'] }} 1 0%; background-color: {{ $item['color'] }}"
                 data-tip="{{ json_encode(['title' => $item['label'], 'rows' => [
                     ['label' => 'Orders', 'value' => (string) $item['value'], 'color' => $item['color']],
                     ['label' => 'Share of open orders', 'value' => $share.'%'],
                 ]]) }}"
                 aria-label="{{ $item['label'] }}: {{ $item['value'] }} orders, {{ $share }}%"></div>
        @endforeach
    </div>

    {{-- Legend with values, so no figure depends on colour alone --}}
    <ul class="mt-4 grid grid-cols-2 gap-x-6 gap-y-3 text-sm">
        @foreach ($items as $item)
            <li class="flex items-start gap-2">
                <span class="mt-1.5 size-2.5 shrink-0 rounded-sm" style="background-color: {{ $item['color'] }}"></span>
                <span>
                    <span class="block text-gray-600">{{ $item['label'] }}</span>
                    <span class="font-medium tabular-nums text-gray-900">{{ $item['value'] }}</span>
                    <span class="tabular-nums text-gray-500">· {{ round($item['value'] / $total * 100) }}%</span>
                </span>
            </li>
        @endforeach
    </ul>

    <x-erp.chart-tooltip />
</figure>
