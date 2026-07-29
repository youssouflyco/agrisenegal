<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProductCatalogController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $layout = request()->routeIs('super-admin.*') ? 'layouts.super-admin' : 'layouts.app';

        $products = Product::query()
            ->with(['user', 'photos'])
            ->where('is_active', true)
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($nested) use ($search) {
                    $nested->where('name', 'like', '%'.$search.'%')
                        ->orWhere('description', 'like', '%'.$search.'%')
                        ->orWhere('unit', 'like', '%'.$search.'%')
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', '%'.$search.'%')
                                ->orWhere('first_name', 'like', '%'.$search.'%')
                                ->orWhere('last_name', 'like', '%'.$search.'%');
                        });
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('products.catalog', [
            'layout' => $layout,
            'products' => $products,
            'search' => $search,
            'isSuperAdminView' => request()->routeIs('super-admin.*'),
        ]);
    }

    public function show(Product $product): View|RedirectResponse
    {
        if (! $product->is_active) {
            abort(404);
        }

        $product->load(['user', 'photos']);

        return view('products.show', [
            'product' => $product,
        ]);
    }
}