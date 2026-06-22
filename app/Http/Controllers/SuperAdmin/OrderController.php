<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SalesRecord;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        $records = SalesRecord::query()->latest('sale_date')->paginate(20);

        return view('super-admin.orders.index', [
            'records' => $records,
            'totals' => [
                'orders' => (int) $records->sum('orders_count'),
                'amount' => (float) $records->sum('amount'),
            ],
        ]);
    }
}