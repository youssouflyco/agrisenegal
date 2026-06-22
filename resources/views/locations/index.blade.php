@extends('layouts.dashboard')

@section('title', 'Mes localisations')

@section('content')
<div class="rounded-2xl bg-gradient-to-br from-agri-primary to-agri-light p-8 text-white shadow-lg">
    <h1 class="text-2xl font-bold md:text-3xl">Mes localisations</h1>
    <p class="mt-2 text-white/90">Gérez vos adresses, champs, boutiques ou dépôts. Les producteurs et distributeurs peuvent enregistrer plusieurs points visibles sur la carte selon leurs besoins.</p>
    <div class="mt-5 flex flex-wrap gap-3">
        <a href="{{ route('locations.create') }}" class="rounded-xl bg-white px-4 py-2 text-sm font-semibold text-agri-primary hover:bg-white/90">Ajouter une localisation</a>
        <a href="{{ route('agri.map') }}" class="rounded-xl bg-white/15 px-4 py-2 text-sm font-semibold text-white backdrop-blur hover:bg-white/25">Voir sur la carte</a>
    </div>
</div>

<div class="mt-8 grid gap-6 lg:grid-cols-3">
    <div class="kpi-card lg:col-span-2">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-agri-primary">Liste des localisations</h2>
                <p class="text-sm text-gray-500">{{ $locations->count() }} localisation(s) enregistrée(s).</p>
            </div>
        </div>

        <div class="mt-6 space-y-4">
            @forelse($locations as $location)
                <div class="rounded-2xl border border-soft-gray bg-white p-5 shadow-sm">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="text-base font-semibold text-gray-900">{{ $location->label }}</h3>
                                @if($location->is_primary)
                                    <span class="rounded-full bg-agri-primary/10 px-3 py-1 text-xs font-semibold text-agri-primary">Principale</span>
                                @endif
                                <span class="rounded-full bg-soft-gray px-3 py-1 text-xs font-medium text-gray-600">{{ $location->kindLabel() }}</span>
                                @if($location->publicly_visible)
                                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">Visible publiquement</span>
                                @else
                                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700">Privée</span>
                                @endif
                            </div>
                            <p class="mt-2 text-sm text-gray-600">{{ $location->region ?? 'Région non renseignée' }}</p>
                            <p class="mt-1 text-xs text-gray-500">Coordonnées : {{ number_format($location->latitude, 6, ',', ' ') }}, {{ number_format($location->longitude, 6, ',', ' ') }}</p>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('locations.edit', $location) }}" class="rounded-xl border border-soft-gray px-3 py-2 text-sm font-medium text-gray-700 hover:bg-soft-gray">Modifier</a>
                            @unless($location->is_primary)
                                <form method="POST" action="{{ route('locations.primary', $location) }}">
                                    @csrf
                                    <button type="submit" class="rounded-xl bg-agri-primary px-3 py-2 text-sm font-medium text-white">Définir comme principale</button>
                                </form>
                            @endunless
                            <form method="POST" action="{{ route('locations.destroy', $location) }}" onsubmit="return confirm('Supprimer cette localisation ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-xl bg-red-50 px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-100">Supprimer</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-dashed border-soft-gray bg-white p-8 text-center">
                    <p class="text-sm text-gray-500">Aucune localisation enregistrée pour le moment.</p>
                    <a href="{{ route('locations.create') }}" class="mt-4 inline-flex rounded-xl bg-agri-primary px-4 py-2 text-sm font-semibold text-white">Ajouter la première localisation</a>
                </div>
            @endforelse
        </div>
    </div>

    <div class="kpi-card">
        <h2 class="text-lg font-bold text-agri-primary">Aide rapide</h2>
        <ul class="mt-4 space-y-3 text-sm text-gray-600">
            <li>• Un producteur peut ajouter plusieurs champs et fermes.</li>
            <li>• Un distributeur peut gérer plusieurs boutiques ou dépôts.</li>
            <li>• Cochez la visibilité publique pour afficher le lieu sur la carte.</li>
            <li>• Un client peut ajouter plusieurs adresses de livraison.</li>
        </ul>
        <a href="{{ route('locations.create') }}" class="mt-6 inline-flex rounded-xl bg-agri-primary px-4 py-2 text-sm font-semibold text-white">Nouvelle localisation</a>
    </div>
</div>
@endsection