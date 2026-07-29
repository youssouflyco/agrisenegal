@extends('layouts.dashboard')

@section('title', 'Mes produits')

@section('content')
<div class="rounded-2xl bg-gradient-to-br from-agri-primary to-agri-light p-8 text-white shadow-lg">
    <h1 class="text-2xl font-bold md:text-3xl">Mes produits</h1>
    <p class="mt-2 text-white/90">Ajoutez vos produits avec photo, prix et quantité pour les rendre visibles dans le catalogue.</p>
    <div class="mt-5 flex flex-wrap gap-3">
        <a href="{{ route('products.create') }}" class="rounded-xl bg-white px-4 py-2 text-sm font-semibold text-agri-primary hover:bg-white/90">Ajouter un produit</a>
    </div>
</div>

<div class="mt-8 grid gap-6 lg:grid-cols-3">
    <div class="kpi-card lg:col-span-2">
        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
            @forelse($products as $product)
                <article class="group overflow-hidden rounded-3xl border border-soft-gray bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                    <div class="h-40 bg-gradient-to-br from-agri-primary/10 via-harvest/10 to-agri-light/10 p-0">
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                    </div>
                    <div class="p-5">
                        <div class="flex items-start justify-between gap-3">
                            <span class="rounded-full bg-white/90 px-3 py-1 text-xs font-semibold text-agri-primary shadow-sm">
                                {{ $product->is_active ? 'Actif' : 'Inactif' }}
                            </span>
                            <span class="rounded-full bg-white/90 px-3 py-1 text-xs font-semibold text-gray-700 shadow-sm">
                                {{ $product->quantity }} {{ $product->unit }}
                            </span>
                        </div>
                        <div class="mt-10">
                            <h3 class="text-xl font-bold text-gray-900">{{ $product->name }}</h3>
                            <p class="mt-1 text-sm text-gray-600 line-clamp-2">{{ $product->description ?? 'Aucune description.' }}</p>
                        </div>
                    </div>

                    <div class="space-y-4 p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-wide text-gray-400">Prix</p>
                                <p class="text-base font-semibold text-gray-900">
                                    {{ $product->price ? number_format($product->price, 0, ',', ' ') . ' FCFA' : 'Prix non renseigné' }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs uppercase tracking-wide text-gray-400">Retrait</p>
                                <p class="text-sm font-medium text-gray-700">Communiqué après commande</p>
                            </div>
                        </div>

                        <div class="flex gap-2 pt-2">
                            <a href="{{ route('products.edit', $product) }}" class="flex-1 rounded-xl border border-soft-gray px-3 py-2 text-center text-sm font-medium text-gray-700 hover:bg-soft-gray">Modifier</a>
                            <form method="POST" action="{{ route('products.destroy', $product) }}" class="flex-1" onsubmit="return confirm('Supprimer ce produit ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full rounded-xl bg-red-50 px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-100">Supprimer</button>
                            </form>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full rounded-2xl border border-dashed border-soft-gray bg-white p-8 text-center">
                    <p class="text-sm text-gray-500">Aucun produit pour le moment.</p>
                    <a href="{{ route('products.create') }}" class="mt-4 inline-flex rounded-xl bg-agri-primary px-4 py-2 text-sm font-semibold text-white">Créer le premier produit</a>
                </div>
            @endforelse
        </div>
    </div>

    <div class="kpi-card">
        <h2 class="text-lg font-bold text-agri-primary">Rappel</h2>
        <p class="mt-4 text-sm text-gray-600">Le point de retrait est donné au client seulement après validation de sa commande.</p>
    </div>
</div>
@endsection