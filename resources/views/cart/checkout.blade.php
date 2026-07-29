@extends('layouts.app')

@section('title', 'Finaliser la commande')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-10 md:px-6">
    <div class="rounded-3xl bg-gradient-to-br from-agri-primary to-agri-light p-8 text-white shadow-lg">
        <h1 class="text-3xl font-bold">Finaliser la commande</h1>
        <p class="mt-2 text-white/90">Architecture prête pour le paiement et la livraison.</p>
    </div>

    <div class="mt-8 grid gap-8 lg:grid-cols-3">
        <div class="rounded-3xl bg-white p-6 shadow-lg lg:col-span-2">
            <h2 class="text-xl font-bold text-gray-900">Options de paiement</h2>
            <div class="mt-4 grid gap-4 md:grid-cols-3">
                @foreach(['Wave', 'Orange Money', 'Paiement à la livraison'] as $method)
                    <label class="rounded-2xl border border-soft-gray p-4 hover:border-agri-primary">
                        <input type="radio" name="payment_method" value="{{ $method }}" class="mr-2">
                        <span class="font-semibold text-gray-900">{{ $method }}</span>
                    </label>
                @endforeach
            </div>

            <h2 class="mt-8 text-xl font-bold text-gray-900">Livraison</h2>
            <div class="mt-4 grid gap-4 md:grid-cols-2">
                @foreach(['Retrait sur place', 'Livraison'] as $delivery)
                    <label class="rounded-2xl border border-soft-gray p-4 hover:border-agri-primary">
                        <input type="radio" name="delivery_mode" value="{{ $delivery }}" class="mr-2">
                        <span class="font-semibold text-gray-900">{{ $delivery }}</span>
                    </label>
                @endforeach
            </div>

            <div class="mt-8 rounded-2xl border border-dashed border-soft-gray bg-soft-gray/30 p-4 text-sm text-gray-600">
                Intégration API de paiement et suivi logistique à brancher ensuite sur ce parcours.
            </div>
        </div>

        <aside class="rounded-3xl bg-white p-6 shadow-lg">
            <h2 class="text-xl font-bold text-gray-900">Résumé</h2>
            <div class="mt-4 space-y-3 text-sm text-gray-600">
                <div class="flex items-center justify-between"><span>Articles</span><span class="font-semibold text-gray-900">{{ $count }}</span></div>
                <div class="flex items-center justify-between"><span>Sous-total</span><span class="font-semibold text-gray-900">{{ number_format($subtotal, 0, ',', ' ') }} FCFA</span></div>
            </div>
            <a href="{{ route('cart.index') }}" class="mt-6 inline-flex w-full items-center justify-center rounded-xl border border-soft-gray px-4 py-3 font-semibold text-gray-700">Retour au panier</a>
        </aside>
    </div>
</div>
@endsection