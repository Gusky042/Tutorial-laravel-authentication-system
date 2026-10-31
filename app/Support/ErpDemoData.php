<?php

namespace App\Support;

use Illuminate\Support\Carbon;

/**
 * Fixed sample figures for the ERP dashboard.
 *
 * Nothing here comes from the database. The numbers are made up but add up:
 * the region totals equal the 12-month revenue, the order statuses equal the
 * open orders, and the aging buckets equal accounts receivable.
 */
class ErpDemoData
{
    /** Chart colours, checked for colour-blind safety on a white surface. */
    public const BLUE = '#2a78d6';

    public const ORANGE = '#eb6834';

    public const AQUA = '#1baf7a';

    public const YELLOW = '#eda100';

    /** Revenue per month in $K, oldest first. The last value is this month. */
    private const REVENUE = [1020, 1085, 940, 980, 1050, 1110, 1075, 1160, 1190, 1140, 1225, 1284];

    /** Operating expenses per month in $K, oldest first. */
    private const EXPENSES = [690, 720, 655, 670, 705, 730, 715, 760, 772, 748, 781, 789];

    public function all(): array
    {
        return [
            'period' => now()->format('F Y'),
            'kpis' => $this->kpis(),
            'cashFlow' => $this->cashFlow(),
            'regions' => $this->regions(),
            'orderStatus' => $this->orderStatus(),
            'fulfilment' => [
                ['label' => 'On-time delivery', 'value' => '94.2%'],
                ['label' => 'Avg. days to ship', 'value' => '2.4'],
                ['label' => 'Backorders', 'value' => '17'],
            ],
            'aging' => $this->aging(),
            'recentOrders' => $this->recentOrders(),
            'lowStock' => $this->lowStock(),
        ];
    }

    private function kpis(): array
    {
        return [
            [
                'label' => 'Revenue this month',
                'value' => self::money(1284),
                'delta' => '+4.8%',
                'tone' => 'good',
                'comparison' => 'vs last month',
                'trend' => self::REVENUE,
            ],
            [
                'label' => 'Gross margin',
                'value' => '38.6%',
                'delta' => '+1.2 pts',
                'tone' => 'good',
                'comparison' => 'vs last month',
                'trend' => [36.1, 36.8, 35.2, 35.9, 36.5, 37.0, 36.7, 37.4, 37.9, 37.1, 37.4, 38.6],
            ],
            [
                'label' => 'Open sales orders',
                'value' => '342',
                'delta' => '−3.1%',
                'tone' => 'neutral',
                'comparison' => 'vs last month',
                'trend' => [310, 325, 298, 305, 331, 347, 339, 352, 360, 349, 353, 342],
            ],
            [
                'label' => 'Accounts receivable',
                'value' => self::money(865),
                'delta' => '−2 days DSO',
                'tone' => 'good',
                'comparison' => '41 days to collect',
                'trend' => [910, 935, 880, 890, 905, 920, 898, 912, 925, 889, 878, 865],
            ],
        ];
    }

    private function cashFlow(): array
    {
        $months = collect(range(11, 0))
            ->map(fn (int $back) => Carbon::now()->startOfMonth()->subMonths($back));

        return [
            'labels' => $months->map->format('M')->all(),
            'fullLabels' => $months->map->format('M Y')->all(),
            'max' => 1500,
            'ticks' => [0, 500, 1000, 1500],
            'series' => [
                ['name' => 'Revenue', 'color' => self::BLUE, 'values' => self::REVENUE],
                ['name' => 'Expenses', 'color' => self::ORANGE, 'values' => self::EXPENSES],
            ],
        ];
    }

    /** Revenue by region over the last 12 months, in $K, largest first. */
    private function regions(): array
    {
        return [
            ['label' => 'North America', 'value' => 4900],
            ['label' => 'Europe', 'value' => 3640],
            ['label' => 'Asia Pacific', 'value' => 2430],
            ['label' => 'Latin America', 'value' => 1215],
            ['label' => 'Middle East & Africa', 'value' => 1074],
        ];
    }

    /** The 342 open sales orders, split by stage. */
    private function orderStatus(): array
    {
        return [
            ['label' => 'Pending approval', 'value' => 38, 'color' => self::BLUE],
            ['label' => 'Processing', 'value' => 126, 'color' => self::ORANGE],
            ['label' => 'Ready to ship', 'value' => 94, 'color' => self::AQUA],
            ['label' => 'Shipped', 'value' => 84, 'color' => self::YELLOW],
        ];
    }

    /** Unpaid invoices by age, in $K. */
    private function aging(): array
    {
        return [
            ['label' => 'Current', 'value' => 512],
            ['label' => '1–30 days', 'value' => 198],
            ['label' => '31–60 days', 'value' => 92],
            ['label' => '61–90 days', 'value' => 38],
            ['label' => '90+ days', 'value' => 25],
        ];
    }

    private function recentOrders(): array
    {
        $colors = collect($this->orderStatus())->pluck('color', 'label');

        $orders = [
            ['SO-10482', 'Northwind Traders', 0, 24560, 'Shipped'],
            ['SO-10481', 'Contoso Ltd.', 0, 8920, 'Processing'],
            ['SO-10480', 'Fabrikam Inc.', 1, 61300, 'Pending approval'],
            ['SO-10479', 'Tailspin Toys', 1, 3475, 'Ready to ship'],
            ['SO-10478', 'Adventure Works', 2, 17840, 'Shipped'],
            ['SO-10477', 'Wide World Importers', 3, 12050, 'Processing'],
        ];

        return array_map(fn (array $o) => [
            'number' => $o[0],
            'customer' => $o[1],
            'date' => Carbon::today()->subDays($o[2]),
            'amount' => '$'.number_format($o[3]),
            'status' => $o[4],
            'color' => $colors[$o[4]],
        ], $orders);
    }

    private function lowStock(): array
    {
        return [
            ['sku' => 'FG-2210', 'item' => 'Steel bracket 40 mm', 'onHand' => 0, 'reorderPoint' => 150, 'level' => 'critical'],
            ['sku' => 'FG-3307', 'item' => 'Hydraulic hose 1 m', 'onHand' => 12, 'reorderPoint' => 60, 'level' => 'critical'],
            ['sku' => 'RM-0815', 'item' => 'Aluminium sheet 2 mm', 'onHand' => 42, 'reorderPoint' => 120, 'level' => 'warning'],
            ['sku' => 'PK-0102', 'item' => 'Shipping carton, large', 'onHand' => 180, 'reorderPoint' => 400, 'level' => 'warning'],
        ];
    }

    /** Format a value in $K as $865K or $1.28M. */
    public static function money(float $thousands): string
    {
        return $thousands >= 1000
            ? '$'.rtrim(rtrim(number_format($thousands / 1000, 2), '0'), '.').'M'
            : '$'.number_format($thousands).'K';
    }
}
