<?php

namespace App\Http\Controllers;

use App\Models\WithdrawalRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WithdrawalRequestController extends Controller
{
    public function index(Request $request): View
    {
        return view('withdrawals.index', [
            'layout' => 'layouts.dashboard',
            'mode' => 'user',
            'requests' => $request->user()->withdrawalRequests()->latest()->paginate(10),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
            'method' => ['required', 'string', 'max:50'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        WithdrawalRequest::create([
            'user_id' => $request->user()->id,
            'amount' => $data['amount'],
            'method' => $data['method'],
            'note' => $data['note'] ?? null,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Votre demande de retrait a été envoyée.');
    }
}