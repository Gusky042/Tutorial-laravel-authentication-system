@props(['labels', 'fullLabels', 'series', 'max', 'ticks', 'caption'])
@use('App\Support\ErpDemoData')

@php
    $count = count($labels);
    $top = count($series) - 1;
@endphp

<figure data-chart class="relative">
    <figcaption class="sr-only">{{ $caption }}</figcaption>

    <div class="relative h-64">
        <div class="absolute bottom-7 left-12 right-0 top-2">
            @foreach ($ticks as $tick)
                <div class="absolute inset-x-0 h-px {{ $tick == 0 ? 'bg-gray-300' : 'bg-gray-100' }}"
                     style="bottom: {{ $tick / $max * 100 }}%"></div>
                <span class="absolute right-full mr-3 translate-y-1/2 text-xs tabular-nums text-gray-500"
                      style="bottom: {{ $tick / $max * 100 }}%">{{ ErpDemoData::money($tick) }}</span>
            @endforeach

            <div class="absolute inset-0 flex">
                @foreach ($labels as $i => $label)
                    @php
                        $total = collect($series)->sum(fn ($s) => $s['values'][$i]);
                        $rows = collect($series)->reverse()->map(fn ($s) => [
                            'label' => $s['name'],
                            'value' => ErpDemoData::money($s['values'][$i]),
                            'color' => $s['color'],
                        ])->values()->push(['label' => 'Total', 'value' => ErpDemoData::money($total)]);
                    @endphp
                    <div tabindex="0"
                         class="group relative flex flex-1 flex-col items-center justify-end rounded focus:outline-none focus-visible:bg-gray-50"
                         data-tip="{{ json_encode(['title' => $fullLabels[$i], 'rows' => $rows]) }}"
                         aria-label="{{ $fullLabels[$i] }}: {{ $rows->map(fn ($r) => $r['label'].' '.$r['value'])->implode(', ') }}">
                        <span class="pointer-events-none absolute inset-0 rounded bg-gray-100 opacity-0 group-hover:opacity-60"></span>
                        {{-- Segments stack from the baseline up, with a 2px gap between them --}}
                        <div class="relative flex w-3 flex-col-reverse gap-0.5 sm:w-5" style="height: {{ $total / $max * 100 }}%">
                            @foreach ($series as $k => $s)
                                <span class="{{ $k === $top ? 'rounded-t' : '' }}"
                                      style="flex: {{ $s['values'][$i] }} 1 0%; background-color: {{ $s['color'] }}"></span>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            @foreach ($labels as $i => $label)
                <span class="absolute top-full mt-2 -translate-x-1/2 text-xs font-medium uppercase tracking-wide text-gray-500 {{ ($count - 1 - $i) % 2 ? 'hidden sm:block' : '' }}"
                      style="left: {{ ($i + 0.5) / $count * 100 }}%" aria-hidden="true">{{ $label }}</span>
            @endforeach
        </div>
    </div>

    <x-erp.chart-tooltip />

    <details class="mt-2 text-sm">
        <summary class="cursor-pointer text-gray-600 hover:text-gray-900">View as table</summary>
        <div class="mt-2 overflow-x-auto">
            <table class="w-full text-left tabular-nums">
                <thead class="text-gray-500">
                    <tr>
                        <th scope="col" class="py-1 pr-4 font-medium">Month</th>
                        @foreach ($series as $s)
                            <th scope="col" class="py-1 pr-4 text-right font-medium">{{ $s['name'] }}</th>
                        @endforeach
                        <th scope="col" class="py-1 pr-4 text-right font-medium">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($fullLabels as $i => $label)
                        <tr>
                            <th scope="row" class="py-1 pr-4 font-normal">{{ $label }}</th>
                            @foreach ($series as $s)
                                <td class="py-1 pr-4 text-right">{{ ErpDemoData::money($s['values'][$i]) }}</td>
                            @endforeach
                            <td class="py-1 pr-4 text-right">{{ ErpDemoData::money(collect($series)->sum(fn ($s) => $s['values'][$i])) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </details>
</figure>
