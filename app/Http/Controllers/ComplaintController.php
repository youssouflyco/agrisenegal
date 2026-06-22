<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ComplaintController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        if (! $user->isClient()) {
            return view('claims.index', [
                'layout' => 'layouts.dashboard',
                'mode' => 'admin',
                'complaints' => Complaint::query()
                    ->with(['user', 'product.user', 'resolver'])
                    ->latest()
                    ->paginate(15),
            ]);
        }

        return view('claims.index', [
            'layout' => 'layouts.dashboard',
            'mode' => 'client',
            'products' => Product::query()
                ->with('user')
                ->where('is_active', true)
                ->latest()
                ->get(),
            'complaints' => $user->complaints()
                ->with(['product.user', 'resolver'])
                ->latest()
                ->paginate(10),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['nullable', 'exists:products,id'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'min:10'],
        ]);

        Complaint::create([
            'user_id' => $request->user()->id,
            'product_id' => $data['product_id'] ?? null,
            'subject' => $data['subject'],
            'message' => $data['message'],
            'status' => 'open',
        ]);

        return back()->with('success', 'Votre réclamation a été envoyée.');
    }
}