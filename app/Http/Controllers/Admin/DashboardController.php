<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardStatsService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private DashboardStatsService $stats) {}

    public function index(): View
    {
        return view('admin.dashboard', [
            'geoStats' => $this->stats->geoStats(),
        ]);
    }
}
