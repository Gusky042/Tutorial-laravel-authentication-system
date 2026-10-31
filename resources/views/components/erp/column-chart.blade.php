@props(['items', 'color', 'caption'])
@use('App\Support\ErpDemoData')

@php
    $max = max(array_column($items, 'value'));
    $total = array_sum(array_column($items, 'value'));
@endphp

<figure data-chart class="relative">
    <figcaption class="sr-only">{{ $caption }}</figcaption>

    <div class="grid border-b border-gray-300" style="grid-template-columns: repeat({{ count($items) }}, minmax(0, 1fr))">
        @foreach ($items as $item)
            @php
                $share = round($item['value'] / $total * 100, 1);
                $value = ErpDemoData::money($item['value']);
            @endphp
            <div tabindex="0"
                 class="flex h-44 flex-col items-center justify-end gap-1 rounded-t focus:outline-none focus-visible:bg-gray-50 focus-visible:ring-2 focus-visible:ring-gray-400"
                 data-tip="{{ json_encode(['title' => $item['label'], 'rows' => [
                     ['label' => 'Outstanding', 'value' => $value, 'color' => $color],
                     ['label' => 'Share of total', 'value' => $share.'%'],
                 ]]) }}"
                 aria-label="{{ $item['label'] }}: {{ $value }}, {{ $share }}% of total">
                <span class="text-xs font-medium tabular-nums text-gray-900" aria-hidden="true">{{ $value }}</span>
                {{-- 85% leaves room for the value label above the tallest column --}}
                <span class="w-6 rounded-t" aria-hidden="true"
                      style="height: {{ round($item['value'] / $max * 85, 2) }}%; background-color: {{ $color }}"></span>
            </div>
        @endforeach
    </div>

    <div class="mt-2 grid text-center text-xs text-gray-500" aria-hidden="true"
         style="grid-template-columns: repeat({{ count($items) }}, minmax(0, 1fr))">
        @foreach ($items as $item)
            <span>{{ $item['label'] }}</span>
        @endforeach
    </div>

    <x-erp.chart-tooltip />
</figure>
