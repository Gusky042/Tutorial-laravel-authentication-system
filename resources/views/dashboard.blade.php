@extends('layouts.app')
@use('App\Support\ErpDemoData')

@section('title', 'Dashboard')
@section('width', 'max-w-7xl')

@section('content')
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold">Welcome, {{ $user->name }}</h1>
            <p class="mt-1 text-sm text-gray-600">Operations overview for {{ $erp['period'] }}</p>
        </div>

        <p class="inline-flex items-center gap-2 rounded-full border border-gray-300 bg-white px-3 py-1 text-xs font-medium text-gray-700">
            <svg class="size-3.5 text-gray-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-7-4a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM9 9a.75.75 0 0 0 0 1.5h.253a.25.25 0 0 1 .244.304l-.459 2.066A1.75 1.75 0 0 0 10.747 15H11a.75.75 0 0 0 0-1.5h-.253a.25.25 0 0 1-.244-.304l.459-2.066A1.75 1.75 0 0 0 9.253 9H9Z" clip-rule="evenodd" />
            </svg>
            Demo data: these figures are samples, not from your database
        </p>
    </div>

    {{-- Key figures --}}
    <section aria-labelledby="kpi-heading" class="mt-6">
        <h2 id="kpi-heading" class="sr-only">Key figures</h2>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($erp['kpis'] as $kpi)
                <x-erp.kpi-tile :label="$kpi['label']" :value="$kpi['value']" :delta="$kpi['delta']"
                                :tone="$kpi['tone']" :comparison="$kpi['comparison']" :trend="$kpi['trend']" />
            @endforeach
        </div>
    </section>

    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
        <section aria-labelledby="cashflow-heading" class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm lg:col-span-2">
            <h2 id="cashflow-heading" class="text-base font-semibold">Revenue and expenses</h2>
            <p class="mt-0.5 text-sm text-gray-500">Last 12 months, USD</p>
            <div class="mt-5">
                <x-erp.line-chart :labels="$erp['cashFlow']['labels']" :full-labels="$erp['cashFlow']['fullLabels']"
                                  :series="$erp['cashFlow']['series']" :max="$erp['cashFlow']['max']"
                                  :ticks="$erp['cashFlow']['ticks']"
                                  caption="Monthly revenue and operating expenses for the last 12 months" />
            </div>
        </section>

        <section aria-labelledby="orders-heading" class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <h2 id="orders-heading" class="text-base font-semibold">Open orders by stage</h2>
            <p class="mt-0.5 text-sm text-gray-500">{{ array_sum(array_column($erp['orderStatus'], 'value')) }} sales orders</p>
            <div class="mt-5">
                <x-erp.stacked-bar :items="$erp['orderStatus']" caption="Open sales orders split by stage" />
            </div>

            <h3 class="mt-6 border-t border-gray-100 pt-4 text-sm font-semibold text-gray-900">Fulfilment, last 30 days</h3>
            <dl class="mt-3 grid grid-cols-3 gap-3">
                @foreach ($erp['fulfilment'] as $stat)
                    <div>
                        <dt class="text-xs text-gray-500">{{ $stat['label'] }}</dt>
                        <dd class="mt-0.5 text-lg font-semibold text-gray-900">{{ $stat['value'] }}</dd>
                    </div>
                @endforeach
            </dl>
        </section>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
        <section aria-labelledby="region-heading" class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <h2 id="region-heading" class="text-base font-semibold">Revenue by region</h2>
            <p class="mt-0.5 text-sm text-gray-500">Last 12 months, USD</p>
            <div class="mt-5">
                <x-erp.bar-list :items="$erp['regions']" :color="ErpDemoData::BLUE"
                                caption="Revenue by sales region for the last 12 months" />
            </div>
            <p class="mt-6 flex justify-between border-t border-gray-100 pt-4 text-sm">
                <span class="text-gray-600">Total revenue</span>
                <span class="font-semibold tabular-nums text-gray-900">{{ ErpDemoData::money(array_sum(array_column($erp['regions'], 'value'))) }}</span>
            </p>
        </section>

        <section aria-labelledby="aging-heading" class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <h2 id="aging-heading" class="text-base font-semibold">Receivables aging</h2>
            <p class="mt-0.5 text-sm text-gray-500">Unpaid invoices by days overdue</p>
            <div class="mt-5">
                <x-erp.column-chart :items="$erp['aging']" :color="ErpDemoData::BLUE"
                                    caption="Outstanding customer invoices grouped by days overdue" />
            </div>
            @php
                $agingTotal = array_sum(array_column($erp['aging'], 'value'));
                $overdue30 = array_sum(array_column(array_slice($erp['aging'], 2), 'value'));
            @endphp
            <dl class="mt-6 space-y-2 border-t border-gray-100 pt-4 text-sm">
                <div class="flex justify-between">
                    <dt class="text-gray-600">Total outstanding</dt>
                    <dd class="font-semibold tabular-nums text-gray-900">{{ ErpDemoData::money($agingTotal) }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-600">More than 30 days overdue</dt>
                    <dd class="font-semibold tabular-nums text-gray-900">
                        {{ ErpDemoData::money($overdue30) }}
                        <span class="font-normal text-gray-500">· {{ round($overdue30 / $agingTotal * 100) }}%</span>
                    </dd>
                </div>
            </dl>
        </section>

        <section aria-labelledby="stock-heading" class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <h2 id="stock-heading" class="text-base font-semibold">Low stock</h2>
            <p class="mt-0.5 text-sm text-gray-500">Items below their reorder point</p>

            <ul class="mt-4 divide-y divide-gray-100">
                @foreach ($erp['lowStock'] as $item)
                    @php
                        $critical = $item['level'] === 'critical';
                        $statusLabel = $item['onHand'] === 0 ? 'Out of stock' : ($critical ? 'Critical' : 'Low');
                        $fill = $critical ? '#d03b3b' : '#fab219';
                        $track = $critical ? '#f6d5d5' : '#feeccb';
                        $percent = round($item['onHand'] / $item['reorderPoint'] * 100);
                    @endphp
                    <li class="py-3">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <p class="truncate text-sm font-medium text-gray-900">{{ $item['item'] }}</p>
                                <p class="text-xs text-gray-500">{{ $item['sku'] }}</p>
                            </div>
                            <span class="inline-flex shrink-0 items-center gap-1 text-xs font-medium text-gray-900">
                                @if ($critical)
                                    <svg class="size-4" viewBox="0 0 20 20" fill="{{ $fill }}" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-8-5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-4.5A.75.75 0 0 1 10 5Zm0 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd" />
                                    </svg>
                                @else
                                    <svg class="size-4" viewBox="0 0 20 20" fill="#c98500" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495ZM10 5a.75.75 0 0 1 .75.75v3.5a.75.75 0 0 1-1.5 0v-3.5A.75.75 0 0 1 10 5Zm0 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd" />
                                    </svg>
                                @endif
                                {{ $statusLabel }}
                            </span>
                        </div>

                        <div class="mt-2 h-1.5 rounded-full" style="background-color: {{ $track }}" aria-hidden="true">
                            <div class="h-full rounded-full" style="width: {{ max($percent, 0) }}%; background-color: {{ $fill }}"></div>
                        </div>
                        <p class="mt-1 text-xs tabular-nums text-gray-600">
                            {{ number_format($item['onHand']) }} on hand · reorder at {{ number_format($item['reorderPoint']) }}
                        </p>
                    </li>
                @endforeach
            </ul>
        </section>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
        <section aria-labelledby="recent-heading" class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm lg:col-span-2">
            <h2 id="recent-heading" class="text-base font-semibold">Recent sales orders</h2>

            <div class="mt-4 overflow-x-auto">
                <table class="w-full min-w-[36rem] text-left text-sm">
                    <thead class="border-b border-gray-200 text-xs text-gray-500">
                        <tr>
                            <th scope="col" class="py-2 pr-4 font-medium">Order</th>
                            <th scope="col" class="py-2 pr-4 font-medium">Customer</th>
                            <th scope="col" class="py-2 pr-4 font-medium">Date</th>
                            <th scope="col" class="py-2 pr-4 text-right font-medium">Amount</th>
                            <th scope="col" class="py-2 font-medium">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($erp['recentOrders'] as $order)
                            <tr>
                                <td class="py-2.5 pr-4 font-medium text-gray-900">{{ $order['number'] }}</td>
                                <td class="py-2.5 pr-4 text-gray-700">{{ $order['customer'] }}</td>
                                <td class="py-2.5 pr-4 text-gray-600">
                                    <time datetime="{{ $order['date']->toDateString() }}">{{ $order['date']->format('j M') }}</time>
                                </td>
                                <td class="py-2.5 pr-4 text-right tabular-nums text-gray-900">{{ $order['amount'] }}</td>
                                <td class="py-2.5">
                                    <span class="inline-flex items-center gap-2 whitespace-nowrap text-gray-700">
                                        <span class="size-2 rounded-full" style="background-color: {{ $order['color'] }}" aria-hidden="true"></span>
                                        {{ $order['status'] }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <section aria-labelledby="account-heading" class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <h2 id="account-heading" class="text-base font-semibold">Your account</h2>

            <dl class="mt-4 space-y-3 text-sm">
                <div>
                    <dt class="text-gray-500">Email</dt>
                    <dd class="mt-0.5 break-all font-medium">{{ $user->email }}</dd>
                </div>

                <div>
                    <dt class="text-gray-500">Last login</dt>
                    <dd class="mt-0.5 font-medium">
                        @if ($previousLoginAt)
                            <time datetime="{{ $previousLoginAt->toIso8601String() }}">
                                {{ $previousLoginAt->format('j M Y, H:i') }} {{ config('app.timezone') }}
                            </time>
                        @else
                            This is your first login
                        @endif
                    </dd>
                </div>

                <div>
                    <dt class="text-gray-500">Account created</dt>
                    <dd class="mt-0.5 font-medium">
                        <time datetime="{{ $user->created_at?->toIso8601String() }}">
                            {{ $user->created_at?->format('j M Y') }}
                        </time>
                    </dd>
                </div>
            </dl>

            <a href="{{ route('password.edit') }}"
               class="mt-5 inline-block rounded-md border border-gray-300 px-3 py-1.5 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-400">
                Change password
            </a>
        </section>
    </div>
@endsection
