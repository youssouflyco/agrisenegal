<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use App\Services\FileUploader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(private FileUploader $fileUploader) {}

    public function index(Request $request): View
    {
        $user = $request->user();

        return view('products.index', [
            'products' => $user->products()->latest()->get(),
        ]);
    }

    public function create(Request $request): View
    {
        if (!auth()->user()->hasApprovedBusinessProfile()) {
          abort(403, 'Votre profil professionnel doit être approuvé pour effectuer cette action.');
        }

        return view('products.create', [
            'product' => new Product(['is_active' => true]),
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
            'unit' => ['required', 'in:kg,tonne'],
            'price' => ['required', 'numeric', 'min:0'],
            'quantity' => ['required', 'integer', 'min:0'],
            'image' => ['required', 'image', 'max:4096'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $this->fileUploader->uploadFile(
                $request->file('image')
            );
        }

        $product = Product::create([
            'user_id' => $user->id,
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'unit' => $data['unit'],
            'price' => $data['price'],
            'quantity' => $data['quantity'],
            'image_path' => $imagePath,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('products.index')->with('success', 'Produit créé avec succès.');
    }

    public function edit(Request $request, Product $product): View
    {
         if (!auth()->user()->hasApprovedBusinessProfile()) {
          abort(403, 'Votre profil professionnel doit être approuvé pour effectuer cette action.');
        }

        $this->authorizeProduct($request->user(), $product);

        return view('products.edit', [
            'product' => $product,
            'action' => route('products.update', $product),
            'method' => 'PUT',
            'title' => 'Modifier le produit',
            'buttonLabel' => 'Enregistrer',
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        if (!auth()->user()->hasApprovedBusinessProfile()) {
          abort(403, 'Votre profil professionnel doit être approuvé pour effectuer cette action.');
        }

        $this->authorizeProduct($request->user(), $product);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'unit' => ['required', 'in:kg,tonne'],
            'price' => ['required', 'numeric', 'min:0'],
            'quantity' => ['required', 'integer', 'min:0'],
            'image' => ['required', 'image', 'max:4096'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
          $imagePath = $this->fileUploader->uploadFile(
                $request->file('image')
            );
        }

        $product->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'unit' => $data['unit'],
            'price' => $data['price'],
            'quantity' => $data['quantity'],
            'image_path' => $imagePath,
            'is_active' => $request->boolean('is_active', false),
        ]);

        return redirect()->route('products.index')->with('success', 'Produit mis à jour.');
    }

    public function destroy(Request $request, Product $product): RedirectResponse
    {
        $this->authorizeProduct($request->user(), $product);

        $product->load('photos');

        foreach ([$product->image_path, ...$product->photos->pluck('path')->all()] as $storedPath) {
            if ($storedPath) {
                Storage::disk('public')->delete($storedPath);
            }
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produit supprimé.');
    }

    private function authorizeProduct($viewer, Product $product): void
    {
        abort_unless($viewer && ($viewer->id === $product->user_id || $viewer->isAdmin() || $viewer->isSuperAdmin()), 403);
    }
}
