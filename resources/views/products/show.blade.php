@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="mx-auto max-w-7xl px-4 py-10 md:px-6">
    <div class="grid gap-8 lg:grid-cols-2">
        <div class="overflow-hidden rounded-3xl bg-white shadow-lg">
            <div
                class="relative h-80 overflow-hidden bg-gradient-to-br from-agri-primary/10 via-harvest/10 to-agri-light/10"
                x-data="productCarousel(@js($product->gallery_image_urls), 4000)"
                x-init="init()"
            >
                <img :src="currentImage() || '{{ $product->image_url }}'" alt="{{ $product->name }}" class="h-full w-full rounded-2xl object-cover transition duration-700 ease-in-out">
                <div x-show="hasMultipleImages()" class="absolute bottom-4 left-1/2 flex -translate-x-1/2 gap-1.5 rounded-full bg-black/35 px-2 py-1 backdrop-blur" x-cloak>
                    <template x-for="(image, index) in images" :key="index">
                        <button type="button" @click="goTo(index)" class="h-2.5 w-2.5 rounded-full transition" :class="currentIndex === index ? 'bg-white' : 'bg-white/45'"></button>
                    </template>
                </div>
            </div>
            @if($product->photos->count() > 1)
                <div class="grid grid-cols-3 gap-3 bg-white p-4">
                    @foreach($product->photos->skip(1) as $photo)
                        <img src="{{ asset('storage/' . $photo->path) }}" alt="{{ $product->name }}" class="h-24 w-full rounded-2xl object-cover">
                    @endforeach
                </div>
            @endif
        </div>

        <div class="rounded-3xl bg-white p-8 shadow-lg">
            <div class="flex flex-wrap items-center gap-2">
                <span class="rounded-full bg-agri-primary/10 px-3 py-1 text-sm font-semibold text-agri-primary">{{ $product->user?->role?->label() ?? 'Vendeur' }}</span>
                <span class="rounded-full bg-soft-gray px-3 py-1 text-sm font-semibold text-gray-700">Point de retrait à confirmer après commande</span>
            </div>

            <h1 class="mt-4 text-3xl font-bold text-gray-900">{{ $product->name }}</h1>
            <p class="mt-4 text-gray-600">{{ $product->description ?? 'Aucune description disponible pour ce produit.' }}</p>

            <div class="mt-6 grid gap-4 sm:grid-cols-2">
                <div class="rounded-2xl bg-soft-gray/50 p-4">
                    <p class="text-xs uppercase tracking-wide text-gray-400">Prix</p>
                    <p class="mt-2 text-2xl font-bold text-agri-primary">{{ $product->price ? number_format($product->price, 0, ',', ' ') . ' FCFA' : 'Sur demande' }}</p>
                </div>
                <div class="rounded-2xl bg-soft-gray/50 p-4">
                    <p class="text-xs uppercase tracking-wide text-gray-400">Stock</p>
                    <p class="mt-2 text-2xl font-bold text-gray-900">{{ $product->quantity }} {{ $product->unit }}</p>
                </div>
            </div>

            <div class="mt-6 rounded-2xl border border-soft-gray p-4">
                <h2 class="font-semibold text-gray-900">Vendeur</h2>
                <div class="mt-3 flex items-center gap-3">
                    <img src="{{ $product->user?->photo_url ?? asset('images/agri/placeholders/avatar.svg') }}" alt="{{ $product->user?->full_name ?? 'Vendeur' }}" class="h-12 w-12 rounded-full object-cover">
                    <div>
                        <p class="font-semibold text-gray-900">{{ $product->user?->full_name ?? '—' }}</p>
                        <p class="text-sm text-gray-500">L'adresse de retrait sera communiquée après la commande</p>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex flex-wrap gap-3">
                <form action="{{ route('cart.add', $product) }}" method="POST" class="flex-1">
                    @csrf
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="w-full rounded-xl bg-agri-primary px-4 py-3 font-semibold text-white">Ajouter au panier</button>
                </form>
                <a href="{{ route('cart.index') }}" class="flex-1 rounded-xl border border-soft-gray px-4 py-3 text-center font-semibold text-gray-700">Voir le panier</a>
            </div>

            <div class="mt-6 flex flex-wrap gap-3 text-sm text-gray-500">
                <span class="rounded-full bg-soft-gray px-3 py-1">Livraison possible</span>
                <span class="rounded-full bg-soft-gray px-3 py-1">Paiement à la livraison</span>
                <span class="rounded-full bg-soft-gray px-3 py-1">Wave / Orange Money</span>
            </div>

            @if($product->photos->count() > 1)
                <div class="mt-8 rounded-2xl border border-soft-gray p-4">
                    <h2 class="font-semibold text-gray-900">Galerie du produit</h2>
                    <div class="mt-4 grid gap-3 sm:grid-cols-2">
                        @foreach($product->photos as $photo)
                            <img src="{{ asset('storage/' . $photo->path) }}" alt="{{ $product->name }}" class="h-40 w-full rounded-2xl object-cover">
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection