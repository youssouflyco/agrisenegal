<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Constants\UserRoleConstants;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

class ComplaintController extends Controller
{
    /**
     * List claims according to the authenticated user's role.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $query = Complaint::query()
            ->with(['user', 'product.user', 'resolver'])
            ->latest();

        if ($user->isClient()) {
            $complaints = (clone $query)
                ->where('user_id', $user->id)
                ->paginate(10);

            return view('claims.index', [
                'layout' => 'layouts.dashboard',
                'mode' => 'client',
                'products' => Product::query()
                    ->with('user')
                    ->where('is_active', true)
                    ->latest()
                    ->get(),
                'complaints' => $complaints,
            ]);
        }

        // Admins can see all claims.
        if ($this->isAdmin($user)) {
            return view('claims.index', [
                'layout' => 'layouts.dashboard',
                'mode' => 'admin',
                'complaints' => $query->paginate(15),
            ]);
        }

        // Producers and distributors see claims for their products.
        $complaints = Complaint::query()
            ->whereHas('product', function ($productQuery) use ($user) {
                $productQuery->where('user_id', $user->id);
            })
            ->with(['user', 'product.user', 'resolver'])
            ->latest()
            ->paginate(15);

        return view('claims.index', [
            'layout' => 'layouts.dashboard',
            'mode' => 'owner',
            'complaints' => $complaints,
        ]);
    }

    /**
     * Create a claim as a customer.
     */
    public function store(Request $request): RedirectResponse
    {
        abort_unless($request->user()->isClient(), 403);

        $data = $request->validate([
            'product_id' => [
                'nullable',
                'integer',
                'exists:products,id',
            ],
            'subject' => [
                'required',
                'string',
                'max:255',
            ],
            'message' => [
                'required',
                'string',
                'min:10',
                'max:5000',
            ],
        ]);

        if (! empty($data['product_id'])) {
            $product = Product::query()
                ->whereKey($data['product_id'])
                ->where('is_active', true)
                ->first();

            if (! $product) {
                throw ValidationException::withMessages([
                    'product_id' => 'Ce produit est indisponible.',
                ]);
            }
        }

        Complaint::create([
            'user_id' => $request->user()->id,
            'product_id' => $data['product_id'] ?? null,
            'subject' => $data['subject'],
            'message' => $data['message'],
            'status' => 'open',
        ]);

        return back()->with(
            'success',
            'Votre réclamation a été envoyée.'
        );
    }

    /**
     * View a claim if the user is authorized to access it.
     */
    public function show(Request $request, Complaint $complaint): View
    {
        $user = $request->user();

        $isOwner = $complaint->product()
            ->where('user_id', $user->id)
            ->exists();

        abort_unless(
            $this->isAdmin($user)
            || $complaint->user_id === $user->id
            || $isOwner,
            403
        );

        $complaint->load([
            'user',
            'product.user',
            'resolver',
        ]);

        return view('claims.show', compact('complaint'));
    }

    /**
     * Resolve or reject a claim as an administrator.
     */
    public function resolve(Request $request, Complaint $complaint): RedirectResponse
    {
        abort_unless($this->isAdmin($request->user()), 403);

        $data = $request->validate([
            'status' => [
                'required',
                'in:resolved,rejected',
            ],
        ]);

        if (in_array($complaint->status, ['resolved', 'rejected'], true)) {
            return back()->with(
                'error',
                'Cette réclamation a déjà été traitée.'
            );
        }

        $complaint->update([
            'status' => $data['status'],
            'resolved_by' => $request->user()->id,
        ]);

        return back()->with(
            'success',
            $data['status'] === 'resolved'
                ? 'La réclamation a été résolue.'
                : 'La réclamation a été rejetée.'
        );
    }

    /**
     * Check whether the user is an administrator.
     */
    private function isAdmin($user): bool
    {
        return in_array(
            $user->role,
            array_values(UserRoleConstants::ADMIN_ROLES),
            true
        );
    }
}
