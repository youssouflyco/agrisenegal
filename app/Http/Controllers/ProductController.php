<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\UserLocation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        return view('products.index', [
            'products' => $user->products()->with('location')->latest()->get(),
            'locations' => $user->locations()->orderByDesc('is_primary')->orderBy('label')->get(),
        ]);
    }

    public function create(Request $request): View
    {
        $user = $request->user();

        return view('products.create', [
            'product' => new Product(['is_active' => true]),
            'locations' => $user->locations()->orderByDesc('is_primary')->orderBy('label')->get(),
            'action' => route('products.store'),
            'method' => 'POST',
            'title' => 'Ajouter un produit',
            'buttonLabel' => 'Créer le produit',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'unit' => ['required', 'string', 'max:30'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'quantity' => ['required', 'integer', 'min:0'],
            'user_location_id' => ['nullable', 'exists:user_locations,id'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $this->ensureOwnLocation($user, $data['user_location_id'] ?? null);

        Product::create([
            'user_id' => $user->id,
            'user_location_id' => $data['user_location_id'] ?? null,
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'unit' => $data['unit'],
            'price' => $data['price'] ?? null,
            'quantity' => $data['quantity'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('products.index')->with('success', 'Produit créé avec succès.');
    }

    public function edit(Request $request, Product $product): View
    {
        $this->authorizeProduct($request->user(), $product);

        return view('products.edit', [
            'product' => $product,
            'locations' => $request->user()->locations()->orderByDesc('is_primary')->orderBy('label')->get(),
            'action' => route('products.update', $product),
            'method' => 'PUT',
            'title' => 'Modifier le produit',
            'buttonLabel' => 'Enregistrer',
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $this->authorizeProduct($request->user(), $product);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'unit' => ['required', 'string', 'max:30'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'quantity' => ['required', 'integer', 'min:0'],
            'user_location_id' => ['nullable', 'exists:user_locations,id'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $this->ensureOwnLocation($request->user(), $data['user_location_id'] ?? null);

        $product->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'unit' => $data['unit'],
            'price' => $data['price'] ?? null,
            'quantity' => $data['quantity'],
            'user_location_id' => $data['user_location_id'] ?? null,
            'is_active' => $request->boolean('is_active', false),
        ]);

        return redirect()->route('products.index')->with('success', 'Produit mis à jour.');
    }

    public function destroy(Request $request, Product $product): RedirectResponse
    {
        $this->authorizeProduct($request->user(), $product);
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produit supprimé.');
    }

    private function authorizeProduct($viewer, Product $product): void
    {
        abort_unless($viewer && ($viewer->id === $product->user_id || $viewer->isAdmin() || $viewer->isSuperAdmin()), 403);
    }

    private function ensureOwnLocation($viewer, ?int $locationId): void
    {
        if (! $locationId) {
            return;
        }

        abort_unless($viewer->locations()->whereKey($locationId)->exists() || $viewer->isAdmin() || $viewer->isSuperAdmin(), 403);
    }
}