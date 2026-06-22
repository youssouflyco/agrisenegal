<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\SalesRecord;
use App\Models\User;
use App\Models\UserLocation;
use Illuminate\Support\Facades\DB;

class DashboardStatsService
{
    public function kpis(): array
    {
        return [
            'clients' => User::where('role', UserRole::Client)->count(),
            'producers' => User::where('role', UserRole::Producer)->count(),
            'distributors' => User::where('role', UserRole::Distributor)->count(),
            'admins' => User::where('role', UserRole::Admin)->count(),
            'orders' => (int) DB::table('platform_stats')->where('key', 'orders')->value('value') ?? 0,
            'sales' => (int) DB::table('platform_stats')->where('key', 'sales')->value('value') ?? 0,
            'claims' => (int) DB::table('platform_stats')->where('key', 'claims')->value('value') ?? 0,
            'withdrawals' => (int) DB::table('platform_stats')->where('key', 'withdrawals')->value('value') ?? 0,
            'revenue' => (float) DB::table('platform_stats')->where('key', 'revenue')->value('amount') ?? 0,
        ];
    }

    public function chartData(string $period): array
    {
        $query = SalesRecord::query()->orderBy('sale_date');

        return match ($period) {
            'weekly' => $this->aggregateLastDays($query, 7),
            'monthly' => $this->aggregateLastDays($query, 30),
            'yearly' => $this->aggregateMonthly($query),
            default => $this->aggregateLastDays($query, 7),
        };
    }

    public function geoStats(): array
    {
        return [
            'producers' => User::where('role', UserRole::Producer)->whereHas('locations')->count(),
            'distributors' => User::where('role', UserRole::Distributor)->whereHas('locations')->count(),
            'locations' => UserLocation::count(),
            'regions' => UserLocation::query()
                ->whereNotNull('region')
                ->select('region')
                ->selectRaw('count(*) as total')
                ->groupBy('region')
                ->orderByDesc('total')
                ->limit(8)
                ->get()
                ->map(fn ($row) => [
                    'region' => $row->region,
                    'total' => (int) $row->total,
                ])
                ->all(),
        ];
    }

    private function aggregateLastDays($query, int $days): array
    {
        $records = (clone $query)
            ->where('sale_date', '>=', now()->subDays($days))
            ->get()
            ->groupBy(fn ($r) => $r->sale_date->format('d/m'));

        $labels = [];
        $amounts = [];
        $orders = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $key = $date->format('d/m');
            $labels[] = $key;
            $dayRecords = $records->get($key, collect());
            $amounts[] = (float) $dayRecords->sum('amount');
            $orders[] = (int) $dayRecords->sum('orders_count');
        }

        return compact('labels', 'amounts', 'orders');
    }

    private function aggregateMonthly($query): array
    {
        $records = (clone $query)
            ->where('sale_date', '>=', now()->subMonths(12))
            ->get()
            ->groupBy(fn ($r) => $r->sale_date->format('M Y'));

        $labels = [];
        $amounts = [];
        $orders = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $key = $date->format('M Y');
            $labels[] = $date->translatedFormat('M');
            $monthRecords = $records->get($key, collect());
            $amounts[] = (float) $monthRecords->sum('amount');
            $orders[] = (int) $monthRecords->sum('orders_count');
        }

        return compact('labels', 'amounts', 'orders');
    }
}
