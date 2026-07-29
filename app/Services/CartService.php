<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;

class CartService
{
    private const SESSION_KEY = 'agri.cart';

    public function all(): Collection
    {
        $storedItems = session()->get(self::SESSION_KEY, []);
        $productIds = array_keys($storedItems);

        if ($productIds === []) {
            return collect();
        }

        $products = Product::query()
            ->with(['user', 'location'])
            ->where('is_active', true)
            ->whereIn('id', $productIds)
            ->get()
            ->keyBy('id');

        return collect($storedItems)
            ->map(function (array $item, int $productId) use ($products): ?array {
                $product = $products->get($productId);

                if (! $product) {
                    return null;
                }

                $quantity = max(1, (int) ($item['quantity'] ?? 1));

                return [
                    'product' => $product,
                    'quantity' => $quantity,
                    'line_total' => (float) ($product->price ?? 0) * $quantity,
                ];
            })
            ->filter()
            ->values();
    }

    public function count(): int
    {
        return $this->all()->sum('quantity');
    }

    public function subtotal(): float
    {
        return (float) $this->all()->sum('line_total');
    }

    public function add(Product $product, int $quantity = 1): void
    {
        $cart = session()->get(self::SESSION_KEY, []);
        $currentQuantity = (int) data_get($cart, $product->id . '.quantity', 0);
        $cart[$product->id] = ['quantity' => max(1, $currentQuantity + $quantity)];

        session()->put(self::SESSION_KEY, $cart);
    }

    public function update(Product $product, int $quantity): void
    {
        $cart = session()->get(self::SESSION_KEY, []);

        if ($quantity <= 0) {
            unset($cart[$product->id]);
        } else {
            $cart[$product->id] = ['quantity' => $quantity];
        }

        session()->put(self::SESSION_KEY, $cart);
    }

    public function remove(Product $product): void
    {
        $cart = session()->get(self::SESSION_KEY, []);
        unset($cart[$product->id]);
        session()->put(self::SESSION_KEY, $cart);
    }

    public function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }
}