@extends('layouts.dashboard')

@section('title', 'Espace Producteur')

@section('content')
<div class="rounded-2xl bg-gradient-to-br from-agri-primary to-agri-light p-8 text-white shadow-lg">
    <h1 class="text-2xl font-bold md:text-3xl">Bienvenue, {{ auth()->user()->first_name }} 🌾</h1>
    <p class="mt-2 text-white/90">Gérez vos récoltes et vos ventes sur {{ config('agri.name') }}</p>
    <div class="mt-5">
        <a href="{{ route('agri.map') }}" class="rounded-xl bg-white/15 px-4 py-2 text-sm font-semibold text-white backdrop-blur hover:bg-white/25">Voir ma position sur la carte</a>
        <a href="{{ route('locations.index') }}" class="ml-2 rounded-xl bg-white/15 px-4 py-2 text-sm font-semibold text-white backdrop-blur hover:bg-white/25">Mes champs</a>
        <a href="{{ route('products.index') }}" class="ml-2 rounded-xl bg-white/15 px-4 py-2 text-sm font-semibold text-white backdrop-blur hover:bg-white/25">Mes produits</a>
        <a href="{{ route('withdrawals.index') }}" class="ml-2 rounded-xl bg-white/15 px-4 py-2 text-sm font-semibold text-white backdrop-blur hover:bg-white/25">Mes retraits</a>
    </div>
</div>

<div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
    @foreach([
        ['icon' => '📋', 'title' => 'Mes produits', 'desc' => 'Ajoutez et mettez à jour votre catalogue.', 'href' => route('products.index')],
        ['icon' => '🥬', 'title' => 'Catalogue produits', 'desc' => 'Voir tous les produits publiés.', 'href' => route('catalog.products')],
        ['icon' => '🗺️', 'title' => 'Mes localisations', 'desc' => 'Gérez vos champs et dépôts.', 'href' => route('locations.index')],
    ] as $card)
        <a href="{{ $card['href'] }}" class="kpi-card block transition hover:-translate-y-1 hover:shadow-lg">
            <span class="text-3xl">{{ $card['icon'] }}</span>
            <h3 class="mt-3 font-bold text-agri-primary">{{ $card['title'] }}</h3>
            <p class="mt-2 text-sm text-gray-600">{{ $card['desc'] }}</p>
        </a>
    @endforeach
</div>
@endsection
