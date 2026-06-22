@extends('layouts.dashboard')

@section('title', 'Espace Administrateur')

@section('content')
<div class="rounded-2xl bg-gradient-to-br from-agri-primary to-agri-light p-8 text-white shadow-lg">
    <h1 class="text-2xl font-bold md:text-3xl">Bienvenue, {{ auth()->user()->first_name }} 🛡️</h1>
    <p class="mt-2 text-white/90">Tableau de bord administrateur {{ config('agri.name') }}</p>
    <div class="mt-5 flex flex-wrap gap-3">
        <a href="{{ route('agri.map') }}" class="rounded-xl bg-white/15 px-4 py-2 text-sm font-semibold text-white backdrop-blur hover:bg-white/25">Ouvrir la carte agricole</a>
        <a href="{{ route('locations.index') }}" class="rounded-xl bg-white/15 px-4 py-2 text-sm font-semibold text-white backdrop-blur hover:bg-white/25">Gérer mes localisations</a>
        <a href="{{ route('admin.claims') }}" class="rounded-xl bg-white/15 px-4 py-2 text-sm font-semibold text-white backdrop-blur hover:bg-white/25">Réclamations</a>
    </div>
</div>

<div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
    @foreach([
        ['icon' => '🥬', 'title' => 'Catalogue produits', 'desc' => 'Consultez les produits publiés.', 'href' => route('catalog.products')],
        ['icon' => '👥', 'title' => 'Mes localisations', 'desc' => 'Gérez vos adresses et points de service.', 'href' => route('locations.index')],
        ['icon' => '📋', 'title' => 'Réclamations', 'desc' => 'Suivez les litiges et signalements.', 'href' => route('admin.claims')],
    ] as $card)
        <a href="{{ $card['href'] }}" class="kpi-card block transition hover:-translate-y-1 hover:shadow-lg">
            <span class="text-3xl">{{ $card['icon'] }}</span>
            <h3 class="mt-3 font-bold text-agri-primary">{{ $card['title'] }}</h3>
            <p class="mt-2 text-sm text-gray-600">{{ $card['desc'] }}</p>
        </a>
    @endforeach
</div>

<div class="mt-8 grid gap-6 lg:grid-cols-3">
    <div class="kpi-card lg:col-span-2">
        <h3 class="font-bold text-agri-primary">Géolocalisation</h3>
        <div class="mt-4 grid gap-4 sm:grid-cols-3">
            <div class="rounded-2xl bg-agri-primary/10 p-4">
                <p class="text-sm text-gray-500">Producteurs localisés</p>
                <p class="mt-2 text-2xl font-bold text-agri-primary">{{ number_format($geoStats['producers']) }}</p>
            </div>
            <div class="rounded-2xl bg-earth/10 p-4">
                <p class="text-sm text-gray-500">Distributeurs localisés</p>
                <p class="mt-2 text-2xl font-bold text-earth">{{ number_format($geoStats['distributors']) }}</p>
            </div>
            <div class="rounded-2xl bg-harvest/10 p-4">
                <p class="text-sm text-gray-500">Localisations enregistrées</p>
                <p class="mt-2 text-2xl font-bold text-harvest">{{ number_format($geoStats['locations']) }}</p>
            </div>
        </div>
    </div>
    <div class="kpi-card">
        <h3 class="font-bold text-agri-primary">Régions actives</h3>
        <div class="mt-4 space-y-3">
            @forelse($geoStats['regions'] as $region)
                <div class="flex items-center justify-between rounded-xl bg-soft-gray/60 px-4 py-3 text-sm">
                    <span class="font-medium text-gray-700">{{ $region['region'] }}</span>
                    <span class="font-semibold text-agri-primary">{{ number_format($region['total']) }}</span>
                </div>
            @empty
                <p class="text-sm text-gray-500">Aucune région renseignée pour le moment.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
