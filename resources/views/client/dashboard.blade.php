@extends('layouts.dashboard')

@section('title', 'Espace Client')

@section('content')
<div class="rounded-2xl bg-gradient-to-br from-agri-primary to-agri-light p-8 text-white shadow-lg" data-reveal>
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold md:text-3xl">Bienvenue, {{ auth()->user()->first_name }} 👋</h1>
            <p class="mt-2 text-white/90">Votre espace client {{ config('agri.name') }}</p>
        </div>
        <img src="{{ auth()->user()->photo_url }}" alt="{{ auth()->user()->full_name }}" class="h-20 w-20 rounded-full border-4 border-white/25 object-cover shadow-lg">
    </div>
    <div class="mt-5">
        <a href="{{ route('agri.map') }}" class="rounded-xl bg-white/15 px-4 py-2 text-sm font-semibold text-white backdrop-blur hover:bg-white/25">Trouver des vendeurs proches</a>
        <a href="{{ route('locations.index') }}" class="ml-2 rounded-xl bg-white/15 px-4 py-2 text-sm font-semibold text-white backdrop-blur hover:bg-white/25">Mes adresses</a>
    </div>
</div>

<div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
    @foreach([
        ['icon' => '🥬', 'title' => 'Catalogue produits', 'desc' => 'Parcourez les produits agricoles disponibles.', 'href' => route('catalog.products')],
        ['icon' => '📦', 'title' => 'Mes réclamations', 'desc' => 'Déposez et suivez vos réclamations.', 'href' => route('claims.index')],
        ['icon' => '🗺️', 'title' => 'Carte agricole', 'desc' => 'Repérez producteurs et distributeurs proches.', 'href' => route('agri.map')],
    ] as $card)
        <a href="{{ $card['href'] }}" class="kpi-card block transition hover:-translate-y-1 hover:shadow-lg" data-reveal>
            <span class="text-3xl">{{ $card['icon'] }}</span>
            <h3 class="mt-3 font-bold text-agri-primary">{{ $card['title'] }}</h3>
            <p class="mt-2 text-sm text-gray-600">{{ $card['desc'] }}</p>
        </a>
    @endforeach
</div>
@endsection
