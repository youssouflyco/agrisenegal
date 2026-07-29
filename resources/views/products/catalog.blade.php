@extends($layout)

@section('title', 'Catalogue des produits')
@if(isset($isSuperAdminView) && $isSuperAdminView)
@section('page-title', 'Catalogue des produits')
@endif

@section('content')
<div class="rounded-2xl bg-gradient-to-br from-agri-primary to-agri-light p-8 text-white shadow-lg">
    <h1 class="text-2xl font-bold md:text-3xl">Catalogue des produits</h1>
    <p class="mt-2 text-white/90">Les produits publiés par les producteurs et distributeurs sont visibles par tous les visiteurs de la plateforme.</p>
</div>

<div class="mt-8 rounded-2xl bg-white p-6 shadow-md">
    <form method="GET" class="flex flex-wrap gap-3">
        <input type="search" name="search" value="{{ $search }}" placeholder="Rechercher un produit ou un vendeur" class="min-w-0 flex-1 rounded-xl border border-soft-gray px-4 py-3 text-sm focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
        <button type="submit" class="btn-primary text-sm">Rechercher</button>
    </form>
</div>

<div class="mt-8 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
    @forelse($products as $product)
        <article class="overflow-hidden rounded-3xl border border-soft-gray bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
            <div
                class="relative h-40 overflow-hidden bg-gradient-to-br from-agri-primary/10 via-harvest/10 to-agri-light/10"
                x-data="productCarousel(@js($product->gallery_image_urls), 4000)"
                x-init="init()"
            >
                <img :src="currentImage() || '{{ $product->image_url }}'" alt="{{ $product->name }}" class="h-full w-full object-cover transition duration-700 ease-in-out">
                <div x-show="hasMultipleImages()" class="absolute bottom-3 left-1/2 flex -translate-x-1/2 gap-1.5 rounded-full bg-black/35 px-2 py-1 backdrop-blur" x-cloak>
                    <template x-for="(image, index) in images" :key="index">
                        <button type="button" @click="goTo(index)" class="h-2.5 w-2.5 rounded-full transition" :class="currentIndex === index ? 'bg-white' : 'bg-white/45'"></button>
                    </template>
                </div>
            </div>
            <div class="p-5">
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
                    <div class="flex items-center gap-3">
                        <img src="{{ $product->user?->photo_url ?? asset('images/agri/placeholders/avatar.svg') }}" alt="{{ $product->user?->full_name ?? 'Vendeur' }}" class="h-10 w-10 rounded-full border border-soft-gray object-cover">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-gray-400">Vendeur</p>
                            <p class="text-sm font-medium text-gray-700">{{ $product->user?->full_name ?? '—' }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xs uppercase tracking-wide text-gray-400">Prix</p>
                        <p class="text-base font-semibold text-gray-900">
                            {{ $product->price ? number_format($product->price, 0, ',', ' ') . ' FCFA' : 'Prix non renseigné' }}
                        </p>
                    </div>
                </div>

                <div class="rounded-2xl bg-soft-gray/50 px-4 py-3 text-sm text-gray-600">
                    <p><span class="font-medium text-gray-700">Remise :</span> À confirmer après la commande</p>
                    <p class="mt-1"><span class="font-medium text-gray-700">Point de retrait :</span> Communiqué par le vendeur</p>
                </div>

                <div class="flex flex-wrap gap-2 pt-1">
                    <a href="{{ route('catalog.products.show', $product) }}" class="flex-1 rounded-xl border border-soft-gray px-3 py-2 text-center text-sm font-medium text-gray-700 hover:bg-soft-gray">Détails</a>
                    <form action="{{ route('cart.add', $product) }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="w-full rounded-xl bg-harvest px-3 py-2 text-sm font-semibold text-agri-primary hover:brightness-95">Ajouter au panier</button>
                    </form>
                    @if(auth()->check() && auth()->user()->isClient())
                        <a href="{{ route('claims.index', ['product_id' => $product->id]) }}" class="flex-1 rounded-xl bg-agri-primary px-3 py-2 text-center text-sm font-medium text-white hover:bg-agri-primary/90">Faire une réclamation</a>
                    @elseif(! auth()->check())
                        <a href="{{ route('register') }}" class="flex-1 rounded-xl bg-agri-primary px-3 py-2 text-center text-sm font-medium text-white hover:bg-agri-primary/90">Créer un compte</a>
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