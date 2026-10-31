<?php

namespace App\Support;

use Illuminate\Support\Carbon;

/**
 * Fixed sample figures for the ERP dashboard.
 *
 * Nothing here comes from the database. The numbers are made up but agree
 * with each other: product + service revenue equals monthly revenue, the
 * expense categories equal this month's expenses, and so on.
 */
class ErpDemoData
{
    /** Brand blue for buttons, the sidebar and the summary card. */
    public const BRAND = '#1f3bb3';

    /** Chart colours, checked for colour-blind safety on white. */
    public const BLUE = '#2a4bc9';

    public const SKY = '#3fb6e8';

    public const ORANGE = '#ef7d4f';

    public const TEAL = '#1fa9b5';

    public const GOLD = '#e8a600';

    /** Status colours. Always shown with a text label, never alone. */
    public const STATUS = [
        'good' => '#0ca30c',
        'warning' => '#fab219',
        'critical' => '#d03b3b',
        'info' => self::BLUE,
    ];

    /** Revenue per month in $K, oldest first. The last value is this month. */
    private const REVENUE = [1020, 1085, 940, 980, 1050, 1110, 1075, 1160, 1190, 1140, 1225, 1284];

    /** Service revenue per month in $K. Product revenue is the rest. */
    private const SERVICES = [290, 310, 268, 281, 300, 318, 309, 333, 345, 326, 351, 372];

    public function all(): array
    {
        return [
            'kpis' => $this->kpis(),
            'weeklyOrders' => $this->weeklyOrders(),
            'revenueMix' => $this->revenueMix(),
            'closedOrders' => [
                'value' => 357,
                'delta' => '+12% vs last month',
                'trend' => [212, 240, 228, 265, 251, 290, 276, 301, 318, 296, 330, 357],
            ],
            'serviceLevels' => [
                ['label' => 'On-time delivery', 'percent' => 94.2],
                ['label' => 'Order fill rate', 'percent' => 97.5],
            ],
            'tasks' => $this->tasks(),
            'expenses' => [
                ['label' => 'Payroll', 'value' => 412, 'color' => self::BLUE],
                ['label' => 'Materials', 'value' => 238, 'color' => self::ORANGE],
                ['label' => 'Logistics', 'value' => 86, 'color' => self::TEAL],
                ['label' => 'Overheads', 'value' => 53, 'color' => self::GOLD],
            ],
            'aging' => [
                ['label' => 'Current', 'value' => 512],
                ['label' => '1–30', 'value' => 198],
                ['label' => '31–60', 'value' => 92],
                ['label' => '61–90', 'value' => 38],
                ['label' => '90+', 'value' => 25],
            ],
            'purchaseOrders' => $this->purchaseOrders(),
            'deadlines' => $this->deadlines(),
            'activities' => $this->activities(),
            'topPerformers' => $this->topPerformers(),
            'alerts' => $this->alerts(),
        ];
    }

    private function kpis(): array
    {
        return [
            ['label' => 'Revenue this month', 'value' => self::money(1284), 'delta' => '+4.8%', 'up' => true, 'good' => true],
            ['label' => 'Gross margin', 'value' => '38.6%', 'delta' => '+1.2 pts', 'up' => true, 'good' => true],
            ['label' => 'Open sales orders', 'value' => '342', 'delta' => '−3.1%', 'up' => false, 'good' => null],
            ['label' => 'Inventory value', 'value' => '$4.2M', 'delta' => '+2.1%', 'up' => true, 'good' => null],
            ['label' => 'Accounts receivable', 'value' => self::money(865), 'delta' => '−1.5%', 'up' => false, 'good' => true],
            ['label' => 'Operating expenses', 'value' => self::money(789), 'delta' => '+1.0%', 'up' => true, 'good' => false],
        ];
    }

    private function weeklyOrders(): array
    {
        $thisWeek = [52, 118, 74, 288, 204, 146, 178];
        $lastWeek = [44, 156, 190, 238, 118, 96, 128];

        return [
            'labels' => ['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'],
            'fullLabels' => ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
            'max' => 300,
            'ticks' => [0, 100, 200, 300],
            'total' => array_sum($thisWeek),
            'change' => round((array_sum($thisWeek) / array_sum($lastWeek) - 1) * 100, 1),
            'series' => [
                ['name' => 'This week', 'color' => self::BLUE, 'values' => $thisWeek, 'emphasis' => true],
                ['name' => 'Last week', 'color' => self::SKY, 'values' => $lastWeek, 'emphasis' => false],
            ],
        ];
    }

    private function revenueMix(): array
    {
        $months = collect(range(11, 0))->map(fn (int $back) => Carbon::now()->startOfMonth()->subMonths($back));
        $products = array_map(fn ($total, $services) => $total - $services, self::REVENUE, self::SERVICES);

        return [
            'labels' => $months->map->format('M')->all(),
            'fullLabels' => $months->map->format('M Y')->all(),
            'max' => 1500,
            'ticks' => [0, 500, 1000, 1500],
            'total' => array_sum(self::REVENUE),
            'change' => '+8.4%',
            'series' => [
                ['name' => 'Products', 'color' => self::BLUE, 'values' => $products],
                ['name' => 'Services', 'color' => self::SKY, 'values' => self::SERVICES],
            ],
        ];
    }

    private function tasks(): array
    {
        $today = Carbon::today();

        return [
            ['title' => 'Approve purchase order PO-2291 for Steelcraft', 'date' => $today->copy()->addDay(), 'status' => 'Due tomorrow', 'tone' => 'warning', 'done' => false],
            ['title' => 'Reconcile October bank statements', 'date' => $today->copy()->subDay(), 'status' => 'Done', 'tone' => 'good', 'done' => true],
            ['title' => 'Review credit limit for Fabrikam Inc.', 'date' => $today->copy()->subDays(2), 'status' => 'Overdue', 'tone' => 'critical', 'done' => false],
            ['title' => 'Sign off November payroll run', 'date' => $today->copy()->addDays(5), 'status' => 'Scheduled', 'tone' => 'info', 'done' => false],
        ];
    }

    private function purchaseOrders(): array
    {
        return [
            ['name' => 'Brandon Washington', 'role' => 'Procurement lead', 'supplier' => 'Steelcraft Ltd.', 'po' => 'PO-2291', 'received' => 128, 'ordered' => 162, 'status' => 'In progress', 'tone' => 'info'],
            ['name' => 'Laura Brooks', 'role' => 'Plant manager', 'supplier' => 'Alu Metals GmbH', 'po' => 'PO-2288', 'received' => 105, 'ordered' => 162, 'status' => 'In progress', 'tone' => 'info'],
            ['name' => 'Wayne Murphy', 'role' => 'Warehouse supervisor', 'supplier' => 'PackRight Supplies', 'po' => 'PO-2284', 'received' => 24, 'ordered' => 160, 'status' => 'Delayed', 'tone' => 'critical'],
            ['name' => 'Amara Okafor', 'role' => 'Maintenance planner', 'supplier' => 'HydroFlex Inc.', 'po' => 'PO-2279', 'received' => 0, 'ordered' => 60, 'status' => 'Awaiting approval', 'tone' => 'warning'],
            ['name' => 'Katherine Butler', 'role' => 'Office manager', 'supplier' => 'Northwind Office', 'po' => 'PO-2275', 'received' => 40, 'ordered' => 40, 'status' => 'Received', 'tone' => 'good'],
        ];
    }

    private function deadlines(): array
    {
        $today = Carbon::today();

        return [
            ['title' => 'Payroll run', 'detail' => '214 employees', 'date' => $today->copy()->addDays(5)],
            ['title' => 'VAT return due', 'detail' => 'Q3 filing', 'date' => $today->copy()->addDays(9)],
            ['title' => 'Quarterly stock count', 'detail' => 'Main warehouse', 'date' => $today->copy()->addDays(14)],
            ['title' => 'Board meeting', 'detail' => 'Q4 budget review', 'date' => $today->copy()->addDays(21)],
        ];
    }

    private function activities(): array
    {
        return [
            ['name' => 'Ben Tossell', 'action' => 'approved purchase order PO-2288', 'time' => 'Just now'],
            ['name' => 'Oliver Noah', 'action' => 'created invoice INV-8841 for Contoso Ltd.', 'time' => '12m'],
            ['name' => 'Jack William', 'action' => 'shipped sales order SO-10482', 'time' => '45m'],
            ['name' => 'Leo Lucas', 'action' => 'received 40 units into stock', 'time' => '1h'],
            ['name' => 'Thomas Henry', 'action' => 'added supplier HydroFlex Inc.', 'time' => '2h'],
            ['name' => 'Amara Okafor', 'action' => 'requested 60 hydraulic hoses', 'time' => '3h'],
        ];
    }

    private function topPerformers(): array
    {
        return [
            ['name' => 'Brandon Washington', 'region' => 'North America', 'revenue' => 486, 'deals' => 38],
            ['name' => 'Laura Brooks', 'region' => 'Europe', 'revenue' => 412, 'deals' => 31],
            ['name' => 'Rafell John', 'region' => 'Asia Pacific', 'revenue' => 357, 'deals' => 29],
            ['name' => 'Matthew Bailey', 'region' => 'Latin America', 'revenue' => 298, 'deals' => 24],
        ];
    }

    private function alerts(): array
    {
        return [
            ['title' => 'Steel bracket 40 mm is out of stock', 'tone' => 'critical'],
            ['title' => 'Hydraulic hose 1 m is below safety stock', 'tone' => 'critical'],
            ['title' => 'Credit review for Fabrikam Inc. is overdue', 'tone' => 'warning'],
        ];
    }

    /** Format a value in $K as $865K or $1.28M. */
    public static function money(float $thousands): string
    {
        if ($thousands == 0) {
            return '$0';
        }

        return $thousands >= 1000
            ? '$'.rtrim(rtrim(number_format($thousands / 1000, 2), '0'), '.').'M'
            : '$'.number_format($thousands).'K';
    }

    /**
     * A smooth SVG path through points (x, y in 0–100) that never overshoots
     * the data (monotone cubic interpolation).
     */
    public static function smoothPath(array $points): string
    {
        $n = count($points);
        $d = [];
        $m = [];

        for ($i = 0; $i < $n - 1; $i++) {
            $d[$i] = ($points[$i + 1][1] - $points[$i][1]) / ($points[$i + 1][0] - $points[$i][0]);
        }

        for ($i = 0; $i < $n; $i++) {
            if ($i === 0) {
                $m[$i] = $d[0];
            } elseif ($i === $n - 1) {
                $m[$i] = $d[$n - 2];
            } elseif ($d[$i - 1] * $d[$i] <= 0) {
                $m[$i] = 0;
            } else {
                // Harmonic mean of the neighbouring slopes keeps the curve between the points.
                $m[$i] = 2 * $d[$i - 1] * $d[$i] / ($d[$i - 1] + $d[$i]);
            }
        }

        $path = sprintf('M%.2f,%.2f', $points[0][0], $points[0][1]);

        for ($i = 0; $i < $n - 1; $i++) {
            [$x0, $y0] = $points[$i];
            [$x1, $y1] = $points[$i + 1];
            $h = ($x1 - $x0) / 3;
            $path .= sprintf(' C%.2f,%.2f %.2f,%.2f %.2f,%.2f', $x0 + $h, $y0 + $m[$i] * $h, $x1 - $h, $y1 - $m[$i + 1] * $h, $x1, $y1);
        }

        return $path;
    }
}
