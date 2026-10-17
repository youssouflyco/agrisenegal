@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="mx-auto max-w-7xl px-4 py-20 md:px-6">
    <div class="grid gap-8 lg:grid-cols-2">

        {{-- Product Image --}}
        <div class="overflow-hidden rounded-3xl bg-white shadow-lg">
            <img
                src="{{ $product->image_path }}"
                alt="{{ $product->name }}"
                class="h-80 w-full object-cover object-center lg:h-full lg:min-h-[28rem]"
            >
        </div>

        {{-- Product Details --}}
        <div class="rounded-3xl bg-white p-8 shadow-lg">
            <div class="flex flex-wrap items-center gap-2">
                <span class="rounded-full bg-agri-primary/10 px-3 py-1 text-sm font-semibold text-agri-primary">
                    {{ $product->user?->role?->label() ?? 'Vendeur' }}
                </span>

                <span class="rounded-full bg-soft-gray px-3 py-1 text-sm font-semibold text-gray-700">
                    Point de retrait à confirmer après commande
                </span>
            </div>

            <h1 class="mt-4 text-3xl font-bold text-gray-900">
                {{ $product->name }}
            </h1>

            <p class="mt-4 text-gray-600">
                {{ $product->description ?? 'Aucune description disponible pour ce produit.' }}
            </p>

            {{-- Price and Stock --}}
            <div class="mt-6 grid gap-4 sm:grid-cols-2">
                <div class="rounded-2xl bg-soft-gray/50 p-4">
                    <p class="text-xs uppercase tracking-wide text-gray-400">Prix</p>
                    <p class="mt-2 text-2xl font-bold text-agri-primary">
                        {{ $product->price ? number_format($product->price, 0, ',', ' ') . ' FCFA' : 'Sur demande' }}
                    </p>
                </div>

                <div class="rounded-2xl bg-soft-gray/50 p-4">
                    <p class="text-xs uppercase tracking-wide text-gray-400">Stock</p>
                    <p class="mt-2 text-2xl font-bold text-gray-900">
                        {{ $product->quantity }} {{ $product->unit }}
                    </p>
                </div>
            </div>

            {{-- Seller --}}
            <div class="mt-6 rounded-2xl border border-soft-gray p-4">
                <h2 class="font-semibold text-gray-900">Vendeur</h2>

                <div class="mt-3 flex items-center gap-3">
                    <img
                        src="{{ $product->user?->photo_url ?? asset('images/agri/placeholders/avatar.svg') }}"
                        alt="{{ $product->user?->full_name ?? 'Vendeur' }}"
                        class="h-12 w-12 rounded-full object-cover"
                    >

                    <div>
                        <p class="font-semibold text-gray-900">
                            {{ $product->user?->full_name ?? '—' }}
                        </p>
                        <p class="text-sm text-gray-500">
                            L'adresse de retrait sera communiquée après la commande
                        </p>
                    </div>
                </div>
            </div>

            {{-- Cart Actions --}}
            <div class="mt-6 flex flex-wrap gap-3">
                <form action="{{ route('cart.add', $product) }}" method="POST" class="flex-1">
                    @csrf
                    <input type="hidden" name="quantity" value="1">

                    <button
                        type="submit"
                        class="w-full rounded-xl bg-agri-primary px-4 py-3 font-semibold text-white transition hover:bg-agri-primary/90"
                    >
                        Ajouter au panier
                    </button>
                </form>

                <a
                    href="{{ route('cart.index') }}"
                    class="flex-1 rounded-xl border border-soft-gray px-4 py-3 text-center font-semibold text-gray-700 transition hover:bg-soft-gray"
                >
                    Voir le panier
                </a>
            </div>

            {{-- Payment and Delivery Information --}}
            <div class="mt-6 flex flex-wrap gap-3 text-sm text-gray-500">
                <span class="rounded-full bg-soft-gray px-3 py-1">Livraison possible</span>
                <span class="rounded-full bg-soft-gray px-3 py-1">Paiement à la livraison</span>
                <span class="rounded-full bg-soft-gray px-3 py-1">Wave / Orange Money</span>
            </div>
        </div>
    </div>
</div>
@endsection
