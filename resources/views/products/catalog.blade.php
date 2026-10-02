@extends($layout)

@section('title', 'Catalogue des produits')
@if(isset($isSuperAdminView) && $isSuperAdminView)
@section('page-title', 'Catalogue des produits')
@endif

@section('content')

<div class="min-h-screen bg-gray-50/50">
    <div class="mx-auto w-full max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

        {{-- ========================================================= --}}
        {{-- HEADER --}}
        {{-- ========================================================= --}}
        <div class="overflow-hidden rounded-3xl bg-gradient-to-br from-agri-primary to-agri-light p-6 shadow-lg sm:p-8">
            <div class="max-w-3xl">
                <h1 class="text-2xl font-bold text-white sm:text-3xl">
                    Catalogue des produits
                </h1>

                <p class="mt-2 text-sm leading-6 text-white/90 sm:text-base">
                    Découvrez les produits publiés par les producteurs et distributeurs
                    disponibles sur notre plateforme.
                </p>
            </div>
        </div>


        {{-- ========================================================= --}}
        {{-- SEARCH --}}
        {{-- ========================================================= --}}
        <div class="mt-6 rounded-3xl border border-gray-100 bg-white p-4 shadow-sm sm:p-6">

            <form
                method="GET"
                action="{{ url('/catalogue-produits') }}"
                class="flex flex-col gap-3 sm:flex-row"
            >

                <div class="relative flex-1">
                    <input
                        type="search"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Rechercher un produit ou un vendeur..."
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3.5 text-sm text-gray-900 outline-none transition placeholder:text-gray-400 focus:border-agri-primary focus:bg-white focus:ring-4 focus:ring-agri-light/20"
                    >
                </div>

                <button
                    type="submit"
                    class="btn-primary w-full rounded-xl px-6 py-3.5 sm:w-auto"
                >
                    Rechercher
                </button>

            </form>

        </div>


        {{-- ========================================================= --}}
        {{-- PRODUCTS HEADER --}}
        {{-- ========================================================= --}}
        <div class="mt-8 flex items-center justify-between gap-4">

            <div>
                <h2 class="text-lg font-semibold text-gray-900">
                    Produits disponibles
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    {{ $products->total() }} produit(s) trouvé(s)
                </p>
            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- PRODUCTS GRID --}}
        {{-- ========================================================= --}}
        <div class="mt-5 grid gap-6 sm:grid-cols-2 xl:grid-cols-3">

            @forelse($products as $product)

                <article class="group flex flex-col overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl">

                    {{-- ================================================= --}}
                    {{-- PRODUCT IMAGE --}}
                    {{-- ================================================= --}}
                  <div class="relative h-56 overflow-hidden bg-gradient-to-br from-agri-primary/10 via-harvest/10 to-agri-light/10">
    @if($product->image_path)
        <img
            src="{{ $product->image_path }}"
            alt="{{ $product->name }}"
            class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
        >
    @else
        <div class="flex h-full items-center justify-center text-sm text-gray-400">
            Aucune image
        </div>
    @endif
</div>


                    {{-- ================================================= --}}
                    {{-- PRODUCT CONTENT --}}
                    {{-- ================================================= --}}
                    <div class="flex flex-1 flex-col p-5">

                        {{-- Tags --}}
                        <div class="flex items-center justify-between gap-3">

                            <span class="rounded-full bg-agri-primary/10 px-3 py-1 text-xs font-semibold text-agri-primary">
                                {{ $product->user?->role?->label() ?? 'Vendeur' }}
                            </span>

                            <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">
                                {{ $product->quantity }} {{ $product->unit }}
                            </span>

                        </div>


                        {{-- Name / Description --}}
                        <div class="mt-4">

                            <h3 class="text-lg font-bold text-gray-900">
                                {{ $product->name }}
                            </h3>

                            <p class="mt-2 line-clamp-2 text-sm leading-6 text-gray-500">
                                {{ $product->description ?? 'Aucune description disponible.' }}
                            </p>

                        </div>


                        {{-- Seller + Price --}}
                        <div class="mt-5 flex items-center justify-between gap-4">

                            {{-- Seller --}}
                            <div class="flex min-w-0 items-center gap-3">

                                <img
                                    src="{{ $product->user?->photo_url ?? asset('images/agri/placeholders/avatar.svg') }}"
                                    alt="{{ $product->user?->full_name ?? 'Vendeur' }}"
                                    class="h-10 w-10 shrink-0 rounded-full border border-gray-200 object-cover"
                                >

                                <div class="min-w-0">
                                    <p class="text-[11px] font-medium uppercase tracking-wide text-gray-400">
                                        Vendeur
                                    </p>

                                    <p class="truncate text-sm font-medium text-gray-700">
                                        {{ $product->user?->full_name ?? '—' }}
                                    </p>
                                </div>

                            </div>


                            {{-- Price --}}
                            <div class="shrink-0 text-right">

                                <p class="text-[11px] font-medium uppercase tracking-wide text-gray-400">
                                    Prix
                                </p>

                                <p class="text-sm font-bold text-agri-primary">
                                    @if($product->price)
                                        {{ number_format($product->price, 0, ',', ' ') }} FCFA
                                    @else
                                        Prix non renseigné
                                    @endif
                                </p>

                            </div>

                        </div>


                        {{-- Additional information --}}
                        <div class="mt-5 rounded-2xl bg-gray-50 px-4 py-3">

                            <div class="flex items-start justify-between gap-4 text-sm">

                                <div>
                                    <p class="text-xs font-medium text-gray-400">
                                        Remise
                                    </p>

                                    <p class="mt-1 text-gray-600">
                                        À confirmer après la commande
                                    </p>
                                </div>

                                <div class="text-right">
                                    <p class="text-xs font-medium text-gray-400">
                                        Retrait
                                    </p>

                                    <p class="mt-1 text-gray-600">
                                        Communiqué par le vendeur
                                    </p>
                                </div>

                            </div>

                        </div>


                        {{-- Actions --}}
                        <div class="mt-5 flex flex-col gap-2 sm:flex-row">

                            <a
                                href="{{ route('catalog.products.show', $product) }}"
                                class="flex-1 rounded-xl border border-gray-200 px-3 py-2.5 text-center text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                            >
                                Détails
                            </a>

                            <form
                                action="{{ route('cart.add', $product) }}"
                                method="POST"
                                class="flex-1"
                            >
                                @csrf

                                <input
                                    type="hidden"
                                    name="quantity"
                                    value="1"
                                >

                                <button
                                    type="submit"
                                    class="w-full rounded-xl bg-harvest px-3 py-2.5 text-sm font-semibold text-agri-primary transition hover:brightness-95"
                                >
                                    Ajouter au panier
                                </button>
                            </form>

                        </div>


                        {{-- Claim --}}
                        @if(auth()->check() && auth()->user()->isClient())

                            <a
                                href="{{ route('claims.index', ['product_id' => $product->id]) }}"
                                class="mt-2 block rounded-xl bg-agri-primary px-3 py-2.5 text-center text-sm font-medium text-white transition hover:bg-agri-primary/90"
                            >
                                Faire une réclamation
                            </a>

                        @endif

                    </div>

                </article>

            @empty

                <div class="col-span-full rounded-3xl border border-dashed border-gray-200 bg-white px-6 py-16 text-center">

                    <div class="mx-auto max-w-md">

                        <svg
                            class="mx-auto h-12 w-12 text-gray-300"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.5"
                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0H4m5-4h6"
                            />
                        </svg>

                        <p class="mt-4 font-medium text-gray-700">
                            Aucun produit trouvé
                        </p>

                        <p class="mt-1 text-sm text-gray-500">
                            Aucun produit actif ne correspond à votre recherche.
                        </p>

                    </div>

                </div>

            @endforelse

        </div>


        {{-- ========================================================= --}}
        {{-- PAGINATION --}}
        {{-- ========================================================= --}}
        @if($products->hasPages())

            <div class="mt-8 flex justify-center">
                {{ $products->links() }}
            </div>

        @endif

    </div>
</div>

@endsection
