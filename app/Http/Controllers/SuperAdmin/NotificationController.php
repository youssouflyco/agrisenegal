<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AdminActivityLog;
use App\Models\Complaint;
use App\Models\WithdrawalRequest;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        return view('super-admin.notifications.index', [
            'activities' => AdminActivityLog::with('admin')->latest()->paginate(15),
            'pendingComplaints' => Complaint::where('status', '!=', 'resolved')->count(),
            'pendingWithdrawals' => WithdrawalRequest::where('status', 'pending')->count(),
        ]);
    }
}