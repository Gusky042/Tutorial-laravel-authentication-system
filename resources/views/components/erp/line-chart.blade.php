@props(['labels', 'fullLabels', 'series', 'max', 'ticks', 'caption'])
@use('App\Support\ErpDemoData')

@php
    $count = count($labels);
    // Points sit in the middle of equal-width bands, so each band is one hover target.
    $x = fn ($i) => round(($i + 0.5) / $count * 100, 3);
    $y = fn ($v) => round(100 - ($v / $max) * 100, 3);
    $fmt = fn ($v) => $v == 0 ? '0' : ErpDemoData::money($v);
@endphp

<figure data-chart class="relative">
    <div class="relative h-64">
        <div class="absolute bottom-7 left-12 right-24 top-2">
            {{-- Gridlines and y-axis labels --}}
            @foreach ($ticks as $tick)
                <div class="absolute inset-x-0 h-px {{ $tick == 0 ? 'bg-gray-300' : 'bg-gray-100' }}"
                     style="bottom: {{ $tick / $max * 100 }}%"></div>
                <span class="absolute right-full mr-2 translate-y-1/2 text-xs tabular-nums text-gray-500"
                      style="bottom: {{ $tick / $max * 100 }}%">{{ $fmt($tick) }}</span>
            @endforeach

            {{-- Lines --}}
            <svg viewBox="0 0 100 100" preserveAspectRatio="none" class="absolute inset-0 h-full w-full overflow-visible"
                 aria-hidden="true">
                @foreach ($series as $s)
                    <polyline fill="none" stroke="{{ $s['color'] }}" stroke-width="2"
                              stroke-linejoin="round" stroke-linecap="round" vector-effect="non-scaling-stroke"
                              points="{{ collect($s['values'])->map(fn ($v, $i) => $x($i).','.$y($v))->implode(' ') }}" />
                @endforeach
            </svg>

            {{-- End dots and direct labels --}}
            @foreach ($series as $s)
                @php $last = end($s['values']); @endphp
                <span class="absolute size-2.5 -translate-x-1/2 -translate-y-1/2 rounded-full ring-2 ring-white"
                      style="left: {{ $x($count - 1) }}%; top: {{ $y($last) }}%; background-color: {{ $s['color'] }}"
                      aria-hidden="true"></span>
                <span class="absolute left-full ml-3 -translate-y-1/2 whitespace-nowrap text-xs leading-tight"
                      style="top: {{ $y($last) }}%" aria-hidden="true">
                    <span class="flex items-center gap-1.5 text-gray-600">
                        <span class="inline-block h-0.5 w-3 rounded" style="background-color: {{ $s['color'] }}"></span>
                        {{ $s['name'] }}
                    </span>
                    <span class="block pl-4.5 font-medium text-gray-900">{{ ErpDemoData::money($last) }}</span>
                </span>
            @endforeach

            {{-- X-axis labels. On small screens every other month is hidden, keeping the latest. --}}
            @foreach ($labels as $i => $label)
                <span class="absolute top-full mt-2 -translate-x-1/2 text-xs text-gray-500 {{ ($count - 1 - $i) % 2 ? 'hidden sm:block' : '' }}"
                      style="left: {{ $x($i) }}%" aria-hidden="true">{{ $label }}</span>
            @endforeach

            {{-- Hover and keyboard targets: one band per month --}}
            <div class="absolute inset-0 flex">
                @foreach ($labels as $i => $label)
                    @php
                        $rows = collect($series)->map(fn ($s) => [
                            'label' => $s['name'],
                            'value' => ErpDemoData::money($s['values'][$i]),
                            'color' => $s['color'],
                        ]);
                    @endphp
                    <div tabindex="0"
                         class="group relative flex-1 focus:outline-none"
                         data-tip="{{ json_encode(['title' => $fullLabels[$i], 'rows' => $rows]) }}"
                         aria-label="{{ $fullLabels[$i] }}: {{ $rows->map(fn ($r) => $r['label'].' '.$r['value'])->implode(', ') }}">
                        <span class="pointer-events-none absolute inset-y-0 left-1/2 w-px bg-gray-400 opacity-0 group-hover:opacity-100 group-focus-visible:opacity-100"></span>
                        @foreach ($series as $s)
                            <span class="pointer-events-none absolute left-1/2 size-2.5 -translate-x-1/2 -translate-y-1/2 rounded-full opacity-0 ring-2 ring-white group-hover:opacity-100 group-focus-visible:opacity-100"
                                  style="top: {{ $y($s['values'][$i]) }}%; background-color: {{ $s['color'] }}"></span>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Legend --}}
    <figcaption class="mt-3 flex flex-wrap items-center gap-x-5 gap-y-1 text-sm text-gray-600">
        <span class="sr-only">{{ $caption }}.</span>
        @foreach ($series as $s)
            <span class="flex items-center gap-2">
                <span class="inline-block h-0.5 w-4 rounded" style="background-color: {{ $s['color'] }}"></span>
                {{ $s['name'] }}
            </span>
        @endforeach
    </figcaption>

    <x-erp.chart-tooltip />

    <details class="mt-3 text-sm">
        <summary class="cursor-pointer text-gray-600 hover:text-gray-900">View as table</summary>
        <div class="mt-2 overflow-x-auto">
            <table class="w-full text-left tabular-nums">
                <thead class="text-gray-500">
                    <tr>
                        <th scope="col" class="py-1 pr-4 font-medium">Month</th>
                        @foreach ($series as $s)
                            <th scope="col" class="py-1 pr-4 text-right font-medium">{{ $s['name'] }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($fullLabels as $i => $label)
                        <tr>
                            <th scope="row" class="py-1 pr-4 font-normal">{{ $label }}</th>
                            @foreach ($series as $s)
                                <td class="py-1 pr-4 text-right">{{ ErpDemoData::money($s['values'][$i]) }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </details>
</figure>
