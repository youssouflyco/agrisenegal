<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Services\DashboardStatsService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private DashboardStatsService $stats) {}

    public function index(): View
    {
        return view('super-admin.dashboard', [
            'kpis' => $this->stats->kpis(),
            'weekly' => $this->stats->chartData('weekly'),
            'monthly' => $this->stats->chartData('monthly'),
            'yearly' => $this->stats->chartData('yearly'),
            'geoStats' => $this->stats->geoStats(),
        ]);
    }
}
