@extends($layout)

@section('title', 'Catalogue des produits')
@if(isset($isSuperAdminView) && $isSuperAdminView)
@section('page-title', 'Catalogue des produits')
@endif

@section('content')
<div class="rounded-2xl bg-gradient-to-br from-agri-primary to-agri-light p-8 text-white shadow-lg">
    <h1 class="text-2xl font-bold md:text-3xl">Catalogue des produits</h1>
    <p class="mt-2 text-white/90">Les produits publiés par les producteurs et distributeurs sont visibles par tous les utilisateurs connectés.</p>
</div>

<div class="mt-8 rounded-2xl bg-white p-6 shadow-md">
    <form method="GET" class="flex flex-wrap gap-3">
        <input type="search" name="search" value="{{ $search }}" placeholder="Rechercher un produit, un vendeur ou une localisation" class="min-w-0 flex-1 rounded-xl border border-soft-gray px-4 py-3 text-sm focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
        <button type="submit" class="btn-primary text-sm">Rechercher</button>
    </form>
</div>

<div class="mt-8 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
    @forelse($products as $product)
        <article class="overflow-hidden rounded-3xl border border-soft-gray bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
            <div class="h-40 bg-gradient-to-br from-agri-primary/10 via-harvest/10 to-agri-light/10 p-5">
                <div class="flex items-start justify-between gap-3">
                    <span class="rounded-full bg-white/90 px-3 py-1 text-xs font-semibold text-agri-primary shadow-sm">
                        {{ $product->user?->role?->label() ?? 'Vendeur' }}
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
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-xs uppercase tracking-wide text-gray-400">Prix</p>
                        <p class="text-base font-semibold text-gray-900">
                            {{ $product->price ? number_format($product->price, 0, ',', ' ') . ' FCFA' : 'Prix non renseigné' }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs uppercase tracking-wide text-gray-400">Vendeur</p>
                        <p class="text-sm font-medium text-gray-700">{{ $product->user?->full_name ?? '—' }}</p>
                    </div>
                </div>

                <div class="rounded-2xl bg-soft-gray/50 px-4 py-3 text-sm text-gray-600">
                    <p><span class="font-medium text-gray-700">Localisation :</span> {{ $product->location?->label ?? 'Non rattachée' }}</p>
                    <p class="mt-1"><span class="font-medium text-gray-700">Région :</span> {{ $product->location?->region ?? '—' }}</p>
                </div>

                <div class="flex flex-wrap gap-2 pt-1">
                    @if(auth()->user()->isClient())
                        <a href="{{ route('claims.index', ['product_id' => $product->id]) }}" class="flex-1 rounded-xl bg-agri-primary px-3 py-2 text-center text-sm font-medium text-white hover:bg-agri-primary/90">Faire une réclamation</a>
                    @endif
                    @if($product->user_location_id)
                        <a href="{{ route('agri.map') }}" class="flex-1 rounded-xl border border-soft-gray px-3 py-2 text-center text-sm font-medium text-gray-700 hover:bg-soft-gray">Voir sur la carte</a>
                    @endif
                </div>
            </div>
        </article>
    @empty
        <div class="col-span-full rounded-2xl border border-dashed border-soft-gray bg-white p-8 text-center">
            <p class="text-sm text-gray-500">Aucun produit actif pour le moment.</p>
        </div>
    @endforelse
</div>

<div class="mt-6">{{ $products->links() }}</div>
@endsection