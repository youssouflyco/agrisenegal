<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductCatalogController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));

        $products = Product::query()
            ->with(['user', 'location'])
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
                        })
                        ->orWhereHas('location', function ($locationQuery) use ($search) {
                            $locationQuery->where('label', 'like', '%'.$search.'%')
                                ->orWhere('region', 'like', '%'.$search.'%');
                        });
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('products.catalog', [
            'layout' => request()->routeIs('super-admin.*') ? 'layouts.super-admin' : 'layouts.dashboard',
            'products' => $products,
            'search' => $search,
            'isSuperAdminView' => request()->routeIs('super-admin.*'),
        ]);
    }
}