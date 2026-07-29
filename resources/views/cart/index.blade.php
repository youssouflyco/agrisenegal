@extends('layouts.app')

@section('title', 'Panier')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-10 md:px-6">
    <div class="rounded-3xl bg-gradient-to-br from-agri-primary to-agri-light p-8 text-white shadow-lg">
        <h1 class="text-3xl font-bold">Votre panier</h1>
        <p class="mt-2 text-white/90">Les articles restent enregistrés dans votre session jusqu'à la commande.</p>
    </div>

    <div class="mt-8 grid gap-8 lg:grid-cols-3">
        <div class="lg:col-span-2 space-y-4">
            @forelse($items as $item)
                @php($product = $item['product'])
                <div class="rounded-3xl border border-soft-gray bg-white p-5 shadow-sm">
                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <div class="flex items-center gap-4">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-20 w-20 rounded-2xl object-cover">
                            <div>
                                <a href="{{ route('catalog.products.show', $product) }}" class="text-lg font-bold text-gray-900 hover:text-agri-primary">{{ $product->name }}</a>
                                <p class="text-sm text-gray-500">{{ $product->user?->full_name ?? 'Vendeur' }} · {{ $product->location?->region ?? 'Région inconnue' }}</p>
                                <p class="mt-1 text-sm text-gray-500">{{ number_format($product->price ?? 0, 0, ',', ' ') }} FCFA / {{ $product->unit }}</p>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-3">
                            <form action="{{ route('cart.update', $product) }}" method="POST" class="flex items-center gap-2">
                                @csrf
                                @method('PUT')
                                <input type="number" name="quantity" min="1" value="{{ $item['quantity'] }}" class="w-24 rounded-xl border border-soft-gray px-3 py-2 text-center">
                                <button type="submit" class="rounded-xl bg-agri-primary px-4 py-2 text-sm font-semibold text-white">Mettre à jour</button>
                            </form>
                            <form action="{{ route('cart.remove', $product) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-xl border border-red-200 bg-red-50 px-4 py-2 text-sm font-semibold text-red-600">Retirer</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="rounded-3xl border border-dashed border-soft-gray bg-white p-10 text-center shadow-sm">
                    <p class="text-lg font-semibold text-gray-900">Votre panier est vide.</p>
                    <a href="{{ route('catalog.products') }}" class="mt-4 inline-flex rounded-xl bg-agri-primary px-5 py-3 text-sm font-semibold text-white">Parcourir le catalogue</a>
                </div>
            @endforelse
        </div>

        <aside class="rounded-3xl bg-white p-6 shadow-lg">
            <h2 class="text-xl font-bold text-gray-900">Résumé</h2>
            <div class="mt-4 space-y-3 text-sm text-gray-600">
                <div class="flex items-center justify-between"><span>Articles</span><span class="font-semibold text-gray-900">{{ $count }}</span></div>
                <div class="flex items-center justify-between"><span>Sous-total</span><span class="font-semibold text-gray-900">{{ number_format($subtotal, 0, ',', ' ') }} FCFA</span></div>
            </div>

            @guest
                <div class="mt-6 rounded-2xl border border-harvest/30 bg-harvest/10 p-4">
                    <p class="text-sm font-semibold text-agri-primary">Connectez-vous pour finaliser</p>
                    <p class="mt-2 text-sm text-gray-600">Votre panier est conservé pendant la connexion ou l'inscription.</p>
                    <div class="mt-4 flex flex-col gap-3">
                        <a href="{{ route('login') }}" class="rounded-xl bg-agri-primary px-4 py-3 text-center font-semibold text-white">Connexion</a>
                        <a href="{{ route('register') }}" class="rounded-xl border border-soft-gray px-4 py-3 text-center font-semibold text-gray-700">Créer un compte</a>
                    </div>
                </div>
            @endguest

            <a href="{{ route('cart.checkout') }}" class="mt-6 inline-flex w-full items-center justify-center rounded-xl bg-harvest px-4 py-3 font-bold text-agri-primary">Commander</a>
        </aside>
    </div>
</div>
@endsection