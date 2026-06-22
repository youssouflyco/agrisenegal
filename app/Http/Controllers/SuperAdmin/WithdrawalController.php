<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\WithdrawalRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class WithdrawalController extends Controller
{
    public function index(): View
    {
        return view('withdrawals.index', [
            'layout' => 'layouts.super-admin',
            'mode' => 'admin',
            'requests' => WithdrawalRequest::query()
                ->with('user')
                ->latest()
                ->paginate(15),
        ]);
    }

    public function update(Request $request, WithdrawalRequest $withdrawalRequest): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['pending', 'approved', 'rejected'])],
            'response_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $withdrawalRequest->update([
            'status' => $data['status'],
            'response_note' => $data['response_note'] ?? null,
            'processed_by' => $request->user()->id,
            'processed_at' => $data['status'] === 'pending' ? null : now(),
        ]);

        return back()->with('success', 'Demande de retrait mise à jour.');
    }
}