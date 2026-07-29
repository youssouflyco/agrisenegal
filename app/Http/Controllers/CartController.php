<?php

namespace App\Http\Controllers;

use App\Http\Requests\Cart\AddToCartRequest;
use App\Http\Requests\Cart\UpdateCartRequest;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(private CartService $cart) {}

    public function index(): View
    {
        return view('cart.index', [
            'items' => $this->cart->all(),
            'subtotal' => $this->cart->subtotal(),
            'count' => $this->cart->count(),
        ]);
    }

    public function add(AddToCartRequest $request, Product $product): RedirectResponse
    {
        abort_unless($product->is_active, 404);

        $this->cart->add($product, (int) $request->integer('quantity', 1));

        return redirect()
            ->route('cart.index')
            ->with('success', 'Produit ajouté au panier.');
    }

    public function update(UpdateCartRequest $request, Product $product): RedirectResponse
    {
        $this->cart->update($product, (int) $request->integer('quantity'));

        return back()->with('success', 'Panier mis à jour.');
    }

    public function remove(Product $product): RedirectResponse
    {
        $this->cart->remove($product);

        return back()->with('success', 'Produit retiré du panier.');
    }

    public function checkout(Request $request): RedirectResponse|View
    {
        if ($this->cart->count() === 0) {
            return redirect()->route('cart.index')->withErrors(['cart' => 'Votre panier est vide.']);
        }

        if (! $request->user()) {
            session()->put('url.intended', route('cart.index'));

            return redirect()->route('login');
        }

        return view('cart.checkout', [
            'items' => $this->cart->all(),
            'subtotal' => $this->cart->subtotal(),
            'count' => $this->cart->count(),
        ]);
    }
}