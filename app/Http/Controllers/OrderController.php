<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Enums\UserRole;
use App\Constants\UserRoleConstants;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Services\CartService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(private CartService $cart) {}

    public function checkout(): View | RedirectResponse
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (!$user->isClient()) {
            return redirect()->route('client.dashboard');
        }

        $items = $this->cart->all();

        if ($items->isEmpty()) {
        return redirect()
            ->route('cart.index')
            ->with('error', 'Votre panier est vide ou ne contient plus de produits disponibles.');
        }

      $subtotal = $this->cart->subtotal();
      $deliveryFee = 0;

      return view('orders.checkout', [
        'items' => $items,
        'count' => $this->cart->count(),
        'subtotal' => $subtotal,
        'deliveryFee' => $deliveryFee,
        'total' => $subtotal + $deliveryFee,
    ]);
}


    /**
     * Create an order and decrement stock atomically.
     */
    public function store(Request $request): RedirectResponse
    {
      $validated = $request->validate([
        'payment_method' => [
            'required',
            'in:WAVE,ORANGE_MONEY,CASH_ON_DELIVERY',
        ],
        'delivery_method' => [
            'required',
            'in:PICKUP,DELIVERY',
        ],
        'delivery_address.recipient' => [
            'required_if:delivery_method,DELIVERY',
            'nullable',
            'string',
            'max:150',
        ],
        'delivery_address.phone' => [
            'required_if:delivery_method,DELIVERY',
            'nullable',
            'string',
            'max:30',
        ],
        'delivery_address.address' => [
            'required_if:delivery_method,DELIVERY',
            'nullable',
            'string',
            'max:500',
        ],
        'delivery_address.city' => [
            'required_if:delivery_method,DELIVERY',
            'nullable',
            'string',
            'max:100',
        ],
        'customer_note' => [
            'nullable',
            'string',
            'max:2000',
        ],
    ]);

     // Build product ID => quantity from the CartService.
     $cartItems = $this->cart->all();

      if ($cartItems->isEmpty()) {
        return redirect()
            ->route('cart.index')
            ->with('error', 'Your cart is empty or its products are no longer available.');
        }

    $quantities = $cartItems->mapWithKeys(
        fn (array $item) => [
            $item['product']->id => (int) $item['quantity'],
        ]
    )->all();

    $order = DB::transaction(function () use ($validated, $quantities) {
        // Reload and lock products to validate current stock and prices.
        $products = Product::query()
            ->whereIn('id', array_keys($quantities))
            ->where('is_active', true)
            ->orderBy('id')
            ->lockForUpdate()
            ->get()
            ->keyBy('id');

        if ($products->count() !== count($quantities)) {
            throw ValidationException::withMessages([
                'cart' => 'One or more products are no longer available. Please update your cart.',
            ]);
        }

        foreach ($quantities as $productId => $quantity) {
            $product = $products->get($productId);

            if ($quantity < 1 || $product->quantity < $quantity) {
                throw ValidationException::withMessages([
                    'cart' => "Insufficient stock for {$product->name}. Please update your cart.",
                ]);
            }
        }

        // Calculate totals using current database prices.
        $subtotal = $products->sum(
            fn (Product $product) =>
                (float) $product->price * $quantities[$product->id]
        );

        // Delivery fees must be calculated server-side.
        $deliveryFee = 0;

        $order = Order::create([
            'order_number' => $this->generateOrderNumber(),
            'user_id' => Auth::id(),
            'subtotal' => $subtotal,
            'delivery_fee' => $deliveryFee,
            'total_amount' => $subtotal + $deliveryFee,
            'status' => Order::STATUS_PENDING,
            'payment_method' => $validated['payment_method'],
            'payment_status' => Order::PAYMENT_PENDING,
            'delivery_method' => $validated['delivery_method'],
            'delivery_address' => $validated['delivery_method'] === 'DELIVERY'
                ? $validated['delivery_address']
                : null,
            'customer_note' => $validated['customer_note'] ?? null,
            'placed_at' => now(),
        ]);

        $sellerIds = $products
            ->pluck('user_id')
            ->filter()
            ->unique()
            ->values();

        $sellers = User::query()
            ->whereIn('id', $sellerIds)
            ->get()
            ->keyBy('id');

        foreach ($products as $product) {
            $quantity = $quantities[$product->id];
            $unitPrice = (float) $product->price;

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'seller_user_id' => $product->user_id,
                'product_name' => $product->name,
                'product_image' => $product->image_path,
                'seller_name' => $sellers->get($product->user_id)?->name,
                'unit_price' => $unitPrice,
                'quantity' => $quantity,
                'subtotal' => $unitPrice * $quantity,
            ]);

            $product->decrement('quantity', $quantity);
        }

        return $order;
    });

    // Clear the cart only after the transaction succeeds.
    $this->cart->clear();

    return redirect()
        ->route('client.orders.show', $order)
        ->with('success', 'Your order has been placed successfully.');
}


    /**
     * Display the authenticated customer's order history.
     */
    public function index(): View
    {
        $orders = Order::query()
            ->where('user_id', Auth::id())
            ->with('items')
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

  public function getCustomerOrders(): View
  {
    $orders = Order::query()
        ->whereHas('items', function ($query) {
            $query->where('seller_user_id', Auth::id());
        })
        ->with([
            'items' => function ($query) {
                $query->where('seller_user_id', Auth::id());
            },
            'user',
        ])
        ->latest()
        ->paginate(10);

    return view('orders.index', compact('orders'));
    }

    /**
     * Display an order belonging to the authenticated customer.
     */

    public function show(string $id): View
    {
     $user = Auth::user();

     $orderQuery = Order::query()->whereKey($id);

     // Admins can view any order.
     if (in_array($user->role, array_values(UserRoleConstants::ADMIN_ROLES))) {
        $order = $orderQuery
            ->with(['items.product', 'items.seller', 'user'])
            ->firstOrFail();

        return view('orders.show', compact('order'));
     }

     // Customers can view only orders they placed.
     if ($user->role === UserRole::Client) {
         $order = $orderQuery
             ->where('user_id', $user->id)
             ->with(['items.product', 'items.seller', 'user'])
             ->firstOrFail();

         return view('orders.show', compact('order'));
     }

    // Producers and distributors can view orders
    // containing their products, but only their own items.
    if (in_array($user->role, array_values(UserRoleConstants::BUSINESS_PROFILE_ROLES))) {
        $order = $orderQuery
            ->whereHas('items', function ($query) use ($user) {
                $query->where('seller_user_id', $user->id);
            })
            ->with([
                'items' => function ($query) use ($user) {
                    $query->where('seller_user_id', $user->id)
                        ->with(['product', 'seller']);
                },
                'user',
            ])
            ->firstOrFail();

        return view('orders.show', compact('order'));
    }

     abort(403);
    }


    /**
     * Display an order belonging to the authenticated customer.
     */
    public function pay(string $id): RedirectResponse
    {
        $order = Order::query()
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        if ($order->payment_status === Order::PAYMENT_PAID) {
            return redirect()
                ->route('client.orders.show', $order)
                ->with('info', 'This order has already been paid.');
        }

        // Simulate payment processing for demonstration purposes.
        $order->update([
            'status' => Order::STATUS_CONFIRMED,
            'payment_status' => Order::PAYMENT_PAID,
            'payment_reference' => Str::upper(Str::random(10)),
        ]);

        return redirect()
            ->route('client.orders.show', $order)
            ->with('success', 'Payment successful. Thank you for your order!');
    }

    public function allOrders(): View
    {
        $orders = Order::query()
            ->with(['items.product', 'items.seller', 'user'])
            ->latest()
            ->paginate(15);

        return view('orders.index', compact('orders'));
    }

    /**
     * Generate a unique order number.
     */
    private function generateOrderNumber(): string
    {
        do {
            $orderNumber = 'AGR-' . Str::upper(Str::random(12));
        } while (Order::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }


}
