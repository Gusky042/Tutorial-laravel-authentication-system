@props(['labels', 'fullLabels', 'series', 'max', 'ticks', 'caption', 'format' => null])
@use('App\Support\ErpDemoData')

@php
    $count = count($labels);
    // Points sit in the middle of equal-width bands, so each band is one hover target.
    $x = fn ($i) => round(($i + 0.5) / $count * 100, 3);
    $y = fn ($v) => round(100 - ($v / $max) * 100, 3);
    $fmt = $format ?? fn ($v) => number_format($v);

    // Draw the de-emphasised series first so the current one sits on top.
    $drawOrder = collect($series)->sortBy(fn ($s) => $s['emphasis'] ? 1 : 0);
@endphp

<figure data-chart class="relative">
    <figcaption class="sr-only">{{ $caption }}</figcaption>

    <div class="relative h-64 sm:h-72">
        <div class="absolute bottom-7 left-10 right-2 top-2">
            {{-- Gridlines and y-axis labels --}}
            @foreach ($ticks as $tick)
                <div class="absolute inset-x-0 h-px {{ $tick == 0 ? 'bg-gray-300' : 'bg-gray-100' }}"
                     style="bottom: {{ $tick / $max * 100 }}%"></div>
                <span class="absolute right-full mr-3 translate-y-1/2 text-xs tabular-nums text-gray-500"
                      style="bottom: {{ $tick / $max * 100 }}%">{{ $fmt($tick) }}</span>
            @endforeach

            {{-- Area washes and lines --}}
            <svg viewBox="0 0 100 100" preserveAspectRatio="none" class="absolute inset-0 h-full w-full overflow-visible"
                 aria-hidden="true">
                @foreach ($drawOrder as $s)
                    @php
                        $points = collect($s['values'])->map(fn ($v, $i) => [$x($i), $y($v)])->all();
                        $path = ErpDemoData::smoothPath($points);
                    @endphp
                    <path d="{{ $path }} L{{ $x($count - 1) }},100 L{{ $x(0) }},100 Z"
                          fill="{{ $s['color'] }}" fill-opacity="{{ $s['emphasis'] ? 0.1 : 0.08 }}" />
                    <path d="{{ $path }}" fill="none" stroke="{{ $s['color'] }}" stroke-width="2"
                          stroke-linejoin="round" stroke-linecap="round" vector-effect="non-scaling-stroke" />
                @endforeach
            </svg>

            {{-- Markers on the current period only --}}
            @foreach ($series as $s)
                @if ($s['emphasis'])
                    @foreach ($s['values'] as $i => $v)
                        <span class="absolute size-2.5 -translate-x-1/2 -translate-y-1/2 rounded-full ring-2 ring-white"
                              style="left: {{ $x($i) }}%; top: {{ $y($v) }}%; background-color: {{ $s['color'] }}"
                              aria-hidden="true"></span>
                    @endforeach
                @endif
            @endforeach

            {{-- X-axis labels --}}
            @foreach ($labels as $i => $label)
                <span class="absolute top-full mt-2 -translate-x-1/2 text-xs font-medium tracking-wide text-gray-500"
                      style="left: {{ $x($i) }}%" aria-hidden="true">{{ $label }}</span>
            @endforeach

            {{-- Hover and keyboard targets --}}
            <div class="absolute inset-0 flex">
                @foreach ($labels as $i => $label)
                    @php
                        $rows = collect($series)->map(fn ($s) => [
                            'label' => $s['name'],
                            'value' => $fmt($s['values'][$i]),
                            'color' => $s['color'],
                        ]);
                    @endphp
                    <div tabindex="0"
                         class="group relative flex-1 focus:outline-none"
                         data-tip="{{ json_encode(['title' => $fullLabels[$i], 'rows' => $rows]) }}"
                         aria-label="{{ $fullLabels[$i] }}: {{ $rows->map(fn ($r) => $r['label'].' '.$r['value'])->implode(', ') }}">
                        <span class="pointer-events-none absolute inset-y-0 left-1/2 w-px bg-gray-400 opacity-0 group-hover:opacity-100 group-focus-visible:opacity-100"></span>
                        @foreach ($series as $s)
                            <span class="pointer-events-none absolute left-1/2 size-3 -translate-x-1/2 -translate-y-1/2 rounded-full opacity-0 ring-2 ring-white group-hover:opacity-100 group-focus-visible:opacity-100"
                                  style="top: {{ $y($s['values'][$i]) }}%; background-color: {{ $s['color'] }}"></span>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <x-erp.chart-tooltip />

    <details class="mt-2 text-sm">
        <summary class="cursor-pointer text-gray-600 hover:text-gray-900">View as table</summary>
        <div class="mt-2 overflow-x-auto">
            <table class="w-full text-left tabular-nums">
                <thead class="text-gray-500">
                    <tr>
                        <th scope="col" class="py-1 pr-4 font-medium">Day</th>
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
                                <td class="py-1 pr-4 text-right">{{ $fmt($s['values'][$i]) }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </details>
</figure>
