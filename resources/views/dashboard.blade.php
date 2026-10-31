@extends('layouts.app')
@use('App\Support\ErpDemoData')

@section('title', 'Dashboard')
@section('width', 'max-w-[1600px]')

@section('header')
    <h1 class="text-2xl font-light tracking-tight text-gray-500 sm:text-3xl">
        <span data-greeting>Welcome</span>, <span class="font-bold text-gray-900">{{ $user->name }}</span>
    </h1>
    <p class="mt-1 text-sm text-gray-500 sm:text-base">Your operations summary this week</p>
@endsection

@section('notifications')
    <ul class="pb-1">
        @foreach ($erp['alerts'] as $alert)
            <li class="flex items-start gap-2 rounded-lg px-3 py-2 text-sm text-gray-700">
                <svg class="mt-0.5 size-4 shrink-0" viewBox="0 0 20 20" fill="{{ $alert['tone'] === 'critical' ? '#d03b3b' : '#c98500' }}" aria-hidden="true">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-8-5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-4.5A.75.75 0 0 1 10 5Zm0 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd" />
                </svg>
                <span><span class="sr-only">{{ $alert['tone'] === 'critical' ? 'Critical:' : 'Warning:' }}</span> {{ $alert['title'] }}</span>
            </li>
        @endforeach
    </ul>
@endsection

@section('content')
    @php
        $card = 'rounded-2xl bg-white p-5 shadow-sm sm:p-7';
        $tabs = ['overview' => 'Overview', 'sales' => 'Sales', 'operations' => 'Operations', 'finance' => 'Finance'];
    @endphp

    <div class="mb-5 flex flex-wrap items-center gap-x-4 gap-y-2 text-xs text-gray-600">
        <span class="inline-flex items-center gap-1.5 rounded-full bg-white px-3 py-1 font-medium text-gray-700 shadow-sm">
            <svg class="size-3.5 text-gray-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-7-4a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM9 9a.75.75 0 0 0 0 1.5h.253a.25.25 0 0 1 .244.304l-.459 2.066A1.75 1.75 0 0 0 10.747 15H11a.75.75 0 0 0 0-1.5h-.253a.25.25 0 0 1-.244-.304l.459-2.066A1.75 1.75 0 0 0 9.253 9H9Z" clip-rule="evenodd" />
            </svg>
            Demo data: sample figures, not from your database
        </span>
        <span>
            Last login:
            @if ($previousLoginAt)
                <time datetime="{{ $previousLoginAt->toIso8601String() }}">{{ $previousLoginAt->format('j M Y, H:i') }} {{ config('app.timezone') }}</time>
            @else
                This is your first login
            @endif
        </span>
    </div>

    {{-- Tabs and page actions --}}
    <div class="flex flex-wrap items-end justify-between gap-4 border-b border-gray-200 print:hidden">
        <div role="tablist" aria-label="Dashboard views" data-tabs class="-mb-px flex overflow-x-auto">
            @foreach ($tabs as $key => $label)
                <button type="button" role="tab" id="tab-{{ $key }}" data-tab="{{ $key }}"
                        aria-controls="dashboard-panels" aria-selected="{{ $loop->first ? 'true' : 'false' }}" tabindex="{{ $loop->first ? '0' : '-1' }}"
                        class="whitespace-nowrap border-b-2 px-4 py-3 text-sm font-medium transition-colors focus:outline-none focus-visible:bg-white aria-selected:border-[#1f3bb3] aria-selected:text-[#1f3bb3] aria-[selected=false]:border-transparent aria-[selected=false]:text-gray-700 aria-[selected=false]:hover:text-gray-900 sm:px-6 sm:text-base {{ $loop->first ? 'pl-0 sm:pl-0' : '' }}">
                    {{ $label }}
                </button>
            @endforeach
        </div>

        <div class="flex gap-3 pb-3">
            <button type="button" data-print
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-400">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z" />
                </svg>
                Print
            </button>
            <button type="button" data-export
                    class="inline-flex items-center gap-2 rounded-lg bg-[#1f3bb3] px-4 py-2 text-sm font-medium text-white hover:bg-[#182f8f] focus:outline-none focus:ring-2 focus:ring-[#1f3bb3] focus:ring-offset-2">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                Export CSV
            </button>
        </div>
    </div>

    <div id="dashboard-panels" role="tabpanel" aria-labelledby="tab-overview" data-tab-panels>
        {{-- Headline figures --}}
        <section aria-label="Key figures" class="grid grid-cols-2 gap-x-6 gap-y-6 py-7 sm:grid-cols-3 xl:grid-cols-6">
            @foreach ($erp['kpis'] as $kpi)
                @php
                    $tone = match ($kpi['good']) {
                        true => 'text-[#006300]',
                        false => 'text-[#d03b3b]',
                        default => 'text-gray-600',
                    };
                @endphp
                <div>
                    <p class="text-sm text-gray-500">{{ $kpi['label'] }}</p>
                    <p class="mt-1 text-2xl font-bold tracking-tight sm:text-3xl">{{ $kpi['value'] }}</p>
                    <p class="mt-1 flex items-center gap-1 text-sm font-medium {{ $tone }}">
                        <svg class="size-3" viewBox="0 0 12 12" fill="currentColor" aria-hidden="true">
                            <path d="{{ $kpi['up'] ? 'M6 2 11 10H1Z' : 'M6 10 1 2h10Z' }}" />
                        </svg>
                        <span class="sr-only">{{ $kpi['up'] ? 'Up' : 'Down' }}</span>
                        {{ $kpi['delta'] }}
                        <span class="font-normal text-gray-500">vs last month</span>
                    </p>
                </div>
            @endforeach
        </section>

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
            {{-- Left column --}}
            <div class="space-y-6 xl:col-span-2">
                <section data-panel="sales operations" aria-labelledby="orders-heading" class="{{ $card }}">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <h2 id="orders-heading" class="text-lg font-bold">Order performance</h2>
                            <p class="mt-1 text-sm text-gray-500">
                                {{ number_format($erp['weeklyOrders']['total']) }} sales orders this week,
                                <span class="font-medium text-[#006300]">+{{ $erp['weeklyOrders']['change'] }}%</span> on last week
                            </p>
                        </div>
                        <ul class="flex gap-5 text-sm text-gray-600" aria-label="Legend">
                            @foreach ($erp['weeklyOrders']['series'] as $s)
                                <li class="flex items-center gap-2">
                                    <span class="size-2.5 rounded-full" style="background-color: {{ $s['color'] }}"></span>{{ $s['name'] }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="mt-6">
                        <x-erp.area-chart :labels="$erp['weeklyOrders']['labels']" :full-labels="$erp['weeklyOrders']['fullLabels']"
                                          :series="$erp['weeklyOrders']['series']" :max="$erp['weeklyOrders']['max']"
                                          :ticks="$erp['weeklyOrders']['ticks']"
                                          caption="Sales orders per day, this week compared with last week" />
                    </div>
                </section>

                <section data-panel="sales finance" aria-labelledby="mix-heading" class="{{ $card }}">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <h2 id="mix-heading" class="text-lg font-bold">Revenue overview</h2>
                            <p class="mt-1 text-sm text-gray-500">Products and services, last 12 months</p>
                        </div>
                        <ul class="flex gap-5 text-sm text-gray-600" aria-label="Legend">
                            @foreach ($erp['revenueMix']['series'] as $s)
                                <li class="flex items-center gap-2">
                                    <span class="size-2.5 rounded-full" style="background-color: {{ $s['color'] }}"></span>{{ $s['name'] }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <p class="mt-5 flex flex-wrap items-baseline gap-x-3">
                        <span class="text-3xl font-bold tracking-tight sm:text-4xl">${{ number_format($erp['revenueMix']['total'] * 1000) }}</span>
                        <span class="text-lg font-medium text-gray-700">USD</span>
                        <span class="text-lg font-medium text-[#006300]">({{ $erp['revenueMix']['change'] }})</span>
                    </p>
                    <div class="mt-6">
                        <x-erp.stacked-columns :labels="$erp['revenueMix']['labels']" :full-labels="$erp['revenueMix']['fullLabels']"
                                               :series="$erp['revenueMix']['series']" :max="$erp['revenueMix']['max']"
                                               :ticks="$erp['revenueMix']['ticks']"
                                               caption="Monthly revenue split into products and services for the last 12 months" />
                    </div>
                </section>

                <section data-panel="operations" aria-labelledby="po-heading" class="{{ $card }}">
                    <div>
                        <h2 id="po-heading" class="text-lg font-bold">Open purchase orders</h2>
                        <p class="mt-1 text-sm text-gray-500">{{ count($erp['purchaseOrders']) }} orders with suppliers, by goods received</p>
                    </div>

                    <div class="mt-5 overflow-x-auto">
                        <table class="w-full min-w-[42rem] text-left text-sm">
                            <thead class="border-b border-gray-200 text-xs font-semibold uppercase tracking-wide text-gray-500">
                                <tr>
                                    <th scope="col" class="py-3 pr-4">Requested by</th>
                                    <th scope="col" class="py-3 pr-4">Supplier</th>
                                    <th scope="col" class="py-3 pr-4">Received</th>
                                    <th scope="col" class="py-3">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($erp['purchaseOrders'] as $po)
                                    @php
                                        $percent = (int) round($po['received'] / $po['ordered'] * 100);
                                        $barColor = ErpDemoData::STATUS[$po['tone']];
                                    @endphp
                                    <tr>
                                        <td class="py-4 pr-4">
                                            <div class="flex items-center gap-3">
                                                <x-avatar :name="$po['name']" />
                                                <div>
                                                    <p class="font-semibold text-gray-900">{{ $po['name'] }}</p>
                                                    <p class="text-gray-500">{{ $po['role'] }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 pr-4">
                                            <p class="font-medium text-gray-900">{{ $po['supplier'] }}</p>
                                            <p class="text-gray-500">{{ $po['po'] }}</p>
                                        </td>
                                        <td class="w-48 py-4 pr-4">
                                            <div class="flex justify-between text-xs tabular-nums">
                                                <span class="font-semibold text-gray-900">{{ $percent }}%</span>
                                                <span class="text-gray-500">{{ $po['received'] }}/{{ $po['ordered'] }}</span>
                                            </div>
                                            <div class="mt-1.5 h-1.5 rounded-full bg-gray-100" aria-hidden="true">
                                                <div class="h-full rounded-full" style="width: {{ $percent }}%; background-color: {{ $barColor }}"></div>
                                            </div>
                                        </td>
                                        <td class="py-4">
                                            <x-erp.status-pill :tone="$po['tone']">{{ $po['status'] }}</x-erp.status-pill>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>

            {{-- Right column --}}
            <div class="space-y-6">
                <section data-panel="sales" aria-labelledby="summary-heading" class="rounded-2xl bg-[#1f3bb3] p-5 text-white shadow-sm sm:p-7">
                    @php
                        $trend = $erp['closedOrders']['trend'];
                        $min = min($trend);
                        $range = max($trend) - $min;
                        $points = collect($trend)->map(fn ($v, $i) => [round($i / (count($trend) - 1) * 100, 2), round(90 - ($v - $min) / $range * 80, 2)])->all();
                    @endphp
                    <h2 id="summary-heading" class="text-lg font-bold">Status summary</h2>
                    <div class="mt-5 flex items-end justify-between gap-6">
                        <div>
                            <p class="text-sm text-blue-100">Closed orders this month</p>
                            <p class="mt-1 text-4xl font-bold tracking-tight">{{ $erp['closedOrders']['value'] }}</p>
                            <p class="mt-1 text-sm text-blue-100">{{ $erp['closedOrders']['delta'] }}</p>
                        </div>
                        {{-- Decorative 12-month trend --}}
                        <svg viewBox="0 0 100 100" preserveAspectRatio="none" class="h-20 w-1/2 overflow-visible" aria-hidden="true">
                            <path d="{{ ErpDemoData::smoothPath($points) }}" fill="none" stroke="#7fdcff" stroke-width="2"
                                  stroke-linecap="round" vector-effect="non-scaling-stroke" />
                        </svg>
                    </div>
                </section>

                <section data-panel="operations" aria-labelledby="service-heading" class="{{ $card }}">
                    <h2 id="service-heading" class="text-lg font-bold">Service levels</h2>
                    <div class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2">
                        @foreach ($erp['serviceLevels'] as $i => $level)
                            <x-erp.ring :label="$level['label']" :percent="$level['percent']"
                                        :color="$i === 0 ? ErpDemoData::BLUE : ErpDemoData::TEAL"
                                        :track="$i === 0 ? '#dbe3fb' : '#d2f0f2'" />
                        @endforeach
                    </div>
                </section>

                <section data-panel="operations finance" aria-labelledby="tasks-heading" class="{{ $card }}">
                    <h2 id="tasks-heading" class="text-lg font-bold">Tasks</h2>
                    <ul class="mt-3 divide-y divide-gray-100">
                        @foreach ($erp['tasks'] as $task)
                            <li class="flex gap-3 py-4">
                                @if ($task['done'])
                                    <svg class="mt-0.5 size-5 shrink-0 text-[#0ca30c]" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" />
                                    </svg>
                                @else
                                    <span class="mt-0.5 size-5 shrink-0 rounded-full border-2 border-gray-300" aria-hidden="true"></span>
                                @endif
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium {{ $task['done'] ? 'text-gray-500 line-through' : 'text-gray-900' }}">{{ $task['title'] }}</p>
                                    <div class="mt-1.5 flex flex-wrap items-center gap-3 text-xs text-gray-500">
                                        <time datetime="{{ $task['date']->toDateString() }}">{{ $task['date']->format('j M Y') }}</time>
                                        <x-erp.status-pill :tone="$task['tone']">{{ $task['status'] }}</x-erp.status-pill>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </section>

                <section data-panel="finance" aria-labelledby="expenses-heading" class="{{ $card }}">
                    <h2 id="expenses-heading" class="text-lg font-bold">Expenses by category</h2>
                    <p class="mt-1 text-sm text-gray-500">This month</p>
                    <div class="mt-6">
                        <x-erp.donut :items="$erp['expenses']" caption="Operating expenses this month split by category" />
                    </div>
                </section>

                <section data-panel="finance" aria-labelledby="aging-heading" class="{{ $card }}">
                    <h2 id="aging-heading" class="text-lg font-bold">Receivables aging</h2>
                    <p class="mt-1 text-sm text-gray-500">Unpaid invoices by days overdue</p>
                    <div class="mt-6">
                        <x-erp.column-chart :items="$erp['aging']" :color="ErpDemoData::SKY"
                                            caption="Outstanding customer invoices grouped by days overdue" />
                    </div>
                </section>
            </div>
        </div>

        {{-- Bottom row --}}
        <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
            <section data-panel="operations finance" aria-labelledby="deadlines-heading" class="{{ $card }}">
                <h2 id="deadlines-heading" class="text-lg font-bold">Upcoming deadlines</h2>
                <ul class="mt-3 divide-y divide-gray-100">
                    @foreach ($erp['deadlines'] as $deadline)
                        <li class="py-4">
                            <p class="font-medium text-gray-900">{{ $deadline['title'] }}</p>
                            <p class="mt-1 flex items-center gap-2 text-sm text-gray-500">
                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                                </svg>
                                <time datetime="{{ $deadline['date']->toDateString() }}">{{ $deadline['date']->format('j M Y') }}</time>
                                <span aria-hidden="true">·</span>
                                {{ $deadline['detail'] }}
                            </p>
                        </li>
                    @endforeach
                </ul>
            </section>

            <section data-panel="operations" aria-labelledby="activity-heading" class="{{ $card }}">
                <div class="flex items-baseline justify-between gap-4">
                    <h2 id="activity-heading" class="text-lg font-bold">Activity</h2>
                    <p class="text-sm text-gray-500">Today</p>
                </div>
                <ol class="relative mt-5 space-y-5 border-l border-gray-200 pl-5">
                    @foreach ($erp['activities'] as $activity)
                        <li class="relative">
                            <span class="absolute -left-[25px] top-1.5 size-2.5 rounded-full bg-[#1f3bb3] ring-4 ring-white" aria-hidden="true"></span>
                            <div class="flex justify-between gap-3 text-sm">
                                <p class="text-gray-700"><span class="font-semibold text-gray-900">{{ $activity['name'] }}</span> {{ $activity['action'] }}</p>
                                <span class="shrink-0 text-gray-500">{{ $activity['time'] }}</span>
                            </div>
                        </li>
                    @endforeach
                </ol>
            </section>

            <section data-panel="sales" aria-labelledby="top-heading" class="{{ $card }} md:col-span-2 xl:col-span-1">
                <h2 id="top-heading" class="text-lg font-bold">Top performers</h2>
                <p class="mt-1 text-sm text-gray-500">Sales reps by revenue this quarter</p>
                <ul class="mt-3 divide-y divide-gray-100">
                    @foreach ($erp['topPerformers'] as $rep)
                        <li class="flex items-center gap-3 py-4">
                            <x-avatar :name="$rep['name']" size="size-11" />
                            <div class="min-w-0 flex-1">
                                <p class="truncate font-semibold text-gray-900">{{ $rep['name'] }}</p>
                                <p class="truncate text-sm text-gray-500">{{ $rep['region'] }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold tabular-nums text-gray-900">{{ ErpDemoData::money($rep['revenue']) }}</p>
                                <p class="text-sm text-gray-500">{{ $rep['deals'] }} deals</p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </section>
        </div>
    </div>

    {{-- Figures for the Export CSV button --}}
    @php
        $mix = $erp['revenueMix'];
        $export = [
            'filename' => 'dashboard-'.now()->toDateString().'.csv',
            'rows' => array_merge(
                [['Section', 'Item', 'Value']],
                array_map(fn ($k) => ['Key figures', $k['label'], $k['value']], $erp['kpis']),
                array_map(fn ($label, $p, $s) => ['Product revenue (USD K)', $label, $p], $mix['fullLabels'], $mix['series'][0]['values'], $mix['series'][1]['values']),
                array_map(fn ($label, $s) => ['Service revenue (USD K)', $label, $s], $mix['fullLabels'], $mix['series'][1]['values']),
                array_map(fn ($e) => ['Expenses this month (USD K)', $e['label'], $e['value']], $erp['expenses']),
                array_map(fn ($a) => ['Receivables aging (USD K)', $a['label'] === 'Current' ? 'Not yet due' : $a['label'].' days', $a['value']], $erp['aging']),
            ),
        ];
    @endphp
    <script type="application/json" id="dashboard-export">@json($export)</script>
@endsection
