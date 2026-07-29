@php($user = auth()->user())
@php($cartCount = collect(session('agri.cart', []))->sum('quantity'))
<nav class="fixed top-0 z-50 w-full bg-white/95 shadow-sm backdrop-blur-md transition-all duration-300" x-data="{ open: false }" data-sticky-nav>
    <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 md:px-6">
        <a href="{{ route('home') }}" class="flex items-center gap-2">
            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-agri-primary text-lg font-bold text-white">A</span>
            <span class="text-xl font-bold text-agri-primary">{{ config('agri.name') }}</span>
        </a>

        <div class="flex items-center gap-2 md:hidden">
            @auth
                <a href="{{ auth()->user()->homeUrl() }}" class="rounded-lg border border-agri-primary px-3 py-2 text-sm font-semibold text-agri-primary">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="rounded-lg bg-agri-primary px-3 py-2 text-sm font-semibold text-white">Déconnexion</button>
                </form>
            @else
                <a href="{{ url('/connexion') }}" class="rounded-lg border border-agri-primary px-3 py-2 text-sm font-semibold text-agri-primary">Connexion</a>
                <a href="{{ url('/inscription') }}" class="rounded-lg bg-agri-primary px-3 py-2 text-sm font-semibold text-white">S'inscrire</a>
            @endauth
        </div>

        <div class="hidden items-center gap-6 md:flex">
            <a href="{{ route('catalog.products') }}" class="text-sm font-medium hover:text-agri-primary">Catalogue</a>
            <a href="{{ route('cart.index') }}" class="relative text-sm font-medium hover:text-agri-primary">
                Panier
                @if($cartCount > 0)
                    <span class="absolute -right-3 -top-2 inline-flex h-5 min-w-5 items-center justify-center rounded-full bg-harvest px-1 text-[11px] font-bold text-agri-primary">{{ $cartCount }}</span>
                @endif
            </a>
            @if($user?->isClient())
                <a href="{{ route('agri.map') }}" class="text-sm font-medium hover:text-agri-primary">Carte</a>
                <a href="{{ route('locations.index') }}" class="text-sm font-medium hover:text-agri-primary">Mes adresses</a>
                <a href="{{ route('claims.index') }}" class="text-sm font-medium hover:text-agri-primary">Réclamations</a>
            @else
                <a href="#presentation" class="text-sm font-medium hover:text-agri-primary">Présentation</a>
                <a href="#fonctionnalites" class="text-sm font-medium hover:text-agri-primary">Fonctionnalités</a>
                <a href="#producteurs" class="text-sm font-medium hover:text-agri-primary">Producteurs</a>
                <a href="#contact" class="text-sm font-medium hover:text-agri-primary">Contact</a>
            @endif
            @auth
                <a href="{{ auth()->user()->homeUrl() }}" class="text-sm font-semibold text-agri-primary hover:underline">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="btn-primary text-sm">Déconnexion</button>
                </form>
            @else
                <a href="{{ url('/connexion') }}" class="text-sm font-semibold text-agri-primary hover:underline">Connexion</a>
                <a href="{{ url('/inscription') }}" class="btn-primary text-sm">S'inscrire</a>
            @endauth
        </div>

        <button @click="open = !open" class="md:hidden rounded-lg p-2 hover:bg-soft-gray">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
    </div>

    <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" class="border-t bg-white px-4 py-4 md:hidden">
        <div class="flex flex-col gap-3">
            <a href="{{ route('catalog.products') }}" @click="open = false" class="py-2">Catalogue</a>
            <a href="{{ route('cart.index') }}" @click="open = false" class="py-2">Panier @if($cartCount > 0) ({{ $cartCount }}) @endif</a>
            @if($user?->isClient())
                <a href="{{ route('agri.map') }}" @click="open = false" class="py-2">Carte</a>
                <a href="{{ route('locations.index') }}" @click="open = false" class="py-2">Mes adresses</a>
                <a href="{{ route('claims.index') }}" @click="open = false" class="py-2">Réclamations</a>
            @else
                <a href="#presentation" @click="open = false" class="py-2">Présentation</a>
                <a href="#fonctionnalites" @click="open = false" class="py-2">Fonctionnalités</a>
                <a href="#producteurs" @click="open = false" class="py-2">Producteurs</a>
                <a href="#contact" @click="open = false" class="py-2">Contact</a>
            @endif
            @auth
                <a href="{{ auth()->user()->homeUrl() }}" @click="open = false" class="py-2 font-semibold text-agri-primary">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-primary w-full text-center">Déconnexion</button>
                </form>
            @else
                <a href="{{ url('/connexion') }}" @click="open = false" class="py-2 font-semibold text-agri-primary">Connexion</a>
                <a href="{{ url('/inscription') }}" @click="open = false" class="btn-primary text-center">S'inscrire</a>
            @endauth
        </div>
    </div>
</nav>
