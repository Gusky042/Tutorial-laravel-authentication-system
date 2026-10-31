@props(['items', 'color', 'caption'])
@use('App\Support\ErpDemoData')

@php
    $max = max(array_column($items, 'value'));
    $total = array_sum(array_column($items, 'value'));
@endphp

<figure data-chart class="relative">
    <figcaption class="sr-only">{{ $caption }}</figcaption>

    <ul class="space-y-3">
        @foreach ($items as $item)
            @php
                $share = round($item['value'] / $total * 100, 1);
                $value = ErpDemoData::money($item['value']);
            @endphp
            <li tabindex="0"
                class="grid grid-cols-[7.5rem_1fr] items-center gap-3 rounded focus:outline-none focus-visible:ring-2 focus-visible:ring-gray-400 sm:grid-cols-[9.5rem_1fr]"
                data-tip="{{ json_encode(['title' => $item['label'], 'rows' => [
                    ['label' => 'Revenue', 'value' => $value, 'color' => $color],
                    ['label' => 'Share of total', 'value' => $share.'%'],
                ]]) }}"
                aria-label="{{ $item['label'] }}: {{ $value }}, {{ $share }}% of total">
                <span class="truncate text-sm text-gray-700">{{ $item['label'] }}</span>
                <span class="flex items-center gap-2" aria-hidden="true">
                    <span class="h-5 rounded-r"
                          style="width: calc((100% - 3.5rem) * {{ $item['value'] / $max }}); background-color: {{ $color }}"></span>
                    <span class="text-sm font-medium tabular-nums text-gray-900">{{ $value }}</span>
                </span>
            </li>
        @endforeach
    </ul>

    <x-erp.chart-tooltip />
</figure>
