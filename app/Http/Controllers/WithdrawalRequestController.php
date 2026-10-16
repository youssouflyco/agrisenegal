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

    public function update(Request $request, WithdrawalRequest $withdrawalRequest): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending,approved,rejected'],
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

    public function show(string $id): View
    {
        $withdrawal = WithdrawalRequest::findOrFail($id);

        return view('withdrawals.show', [
            'layout' => 'layouts.super-admin',
            'mode' => 'admin',
            'withdrawal' => $withdrawal->load('user'),
        ]);
    }
}
