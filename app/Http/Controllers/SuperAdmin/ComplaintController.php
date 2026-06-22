<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ComplaintController extends Controller
{
    public function index(): View
    {
        return view('claims.index', [
            'layout' => 'layouts.super-admin',
            'mode' => 'admin',
            'complaints' => Complaint::query()
                ->with(['user', 'product.user', 'resolver'])
                ->latest()
                ->paginate(15),
        ]);
    }

    public function update(Request $request, Complaint $complaint): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['open', 'in_progress', 'resolved'])],
            'resolution_note' => ['nullable', 'string'],
        ]);

        $complaint->update([
            'status' => $data['status'],
            'resolution_note' => $data['resolution_note'] ?? null,
            'resolved_by' => $data['status'] === 'resolved' ? $request->user()->id : null,
            'resolved_at' => $data['status'] === 'resolved' ? now() : null,
        ]);

        return back()->with('success', 'Réclamation mise à jour.');
    }
}