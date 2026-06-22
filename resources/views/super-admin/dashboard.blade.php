@extends('layouts.super-admin')

@section('title', 'Dashboard')
@section('page-title', 'Tableau de bord')

@section('content')
<div x-data="{ period: 'weekly' }">
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-5">
        <x-kpi-card label="Clients" :value="number_format($kpis['clients'])" icon="👥" />
        <x-kpi-card label="Producteurs" :value="number_format($kpis['producers'])" icon="🌾" variant="light" />
        <x-kpi-card label="Distributeurs" :value="number_format($kpis['distributors'])" icon="🚚" variant="earth" />
        <x-kpi-card label="Administrateurs" :value="number_format($kpis['admins'])" icon="🛡️" />
        <x-kpi-card label="Commandes" :value="number_format($kpis['orders'])" icon="📦" variant="harvest" />
    </div>

    <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-kpi-card label="Ventes" :value="number_format($kpis['sales'])" icon="💰" />
        <x-kpi-card label="Réclamations" :value="number_format($kpis['claims'])" icon="⚠️" variant="earth" />
        <x-kpi-card label="Retraits" :value="number_format($kpis['withdrawals'])" icon="🏦" />
        <x-kpi-card label="Chiffre d'affaires" :value="number_format($kpis['revenue'], 0, ',', ' ') . ' FCFA'" icon="📈" variant="harvest" />
    </div>

    <div class="mt-8 rounded-2xl bg-white p-6 shadow-md">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-agri-primary">Carte et géolocalisation</h2>
                <p class="mt-1 text-sm text-gray-500">Accès rapide à la carte nationale et aux statistiques par région.</p>
            </div>
            <a href="{{ route('agri.map') }}" class="btn-secondary text-sm">Ouvrir la carte</a>
        </div>

        <div class="mt-4 grid gap-4 sm:grid-cols-3">
            <x-kpi-card label="Producteurs géolocalisés" :value="number_format($geoStats['producers'])" icon="🌾" variant="light" />
            <x-kpi-card label="Distributeurs géolocalisés" :value="number_format($geoStats['distributors'])" icon="🚚" variant="earth" />
            <x-kpi-card label="Localisations enregistrées" :value="number_format($geoStats['locations'])" icon="🗺️" variant="harvest" />
        </div>

        <div class="mt-6 grid gap-4 lg:grid-cols-2">
            <div class="rounded-2xl bg-soft-gray/40 p-4">
                <h3 class="font-semibold text-agri-primary">Répartition par région</h3>
                <div class="mt-3 space-y-2">
                    @forelse($geoStats['regions'] as $region)
                        <div class="flex items-center justify-between rounded-xl bg-white px-4 py-3 text-sm shadow-sm">
                            <span class="font-medium text-gray-700">{{ $region['region'] }}</span>
                            <span class="font-semibold text-agri-primary">{{ number_format($region['total']) }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">Aucune région disponible.</p>
                    @endforelse
                </div>
            </div>
            <div class="rounded-2xl bg-gradient-to-br from-agri-primary to-agri-light p-5 text-white">
                <h3 class="font-semibold">Protection des coordonnées</h3>
                <p class="mt-2 text-sm text-white/90">Les positions exactes restent réservées aux propriétaires et aux profils autorisés. La carte publique n’affiche qu’une approximation.</p>
            </div>
        </div>
    </div>

    <div class="mt-8 rounded-2xl bg-white p-6 shadow-md">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h2 class="text-lg font-bold text-agri-primary">Évolution des ventes</h2>
            <div class="flex rounded-xl bg-soft-gray p-1">
                @foreach(['weekly' => 'Semaine', 'monthly' => 'Mois', 'yearly' => 'Année'] as $key => $label)
                    <button @click="period = '{{ $key }}'" :class="period === '{{ $key }}' ? 'bg-agri-primary text-white' : 'text-gray-600'"
                            class="rounded-lg px-4 py-2 text-sm font-medium transition">{{ $label }}</button>
                @endforeach
            </div>
        </div>

        <div class="mt-6 h-80">
            @php
                $weeklyChart = json_encode([
                    'type' => 'line',
                    'data' => [
                        'labels' => $weekly['labels'],
                        'datasets' => [
                            ['label' => 'CA (FCFA)', 'data' => $weekly['amounts'], 'borderColor' => '#2E7D32', 'backgroundColor' => 'rgba(46,125,50,0.1)', 'fill' => true, 'tension' => 0.4],
                            ['label' => 'Commandes', 'data' => $weekly['orders'], 'borderColor' => '#F9A825', 'backgroundColor' => 'transparent', 'tension' => 0.4, 'yAxisID' => 'y1'],
                        ],
                    ],
                    'options' => ['responsive' => true, 'maintainAspectRatio' => false, 'scales' => ['y1' => ['position' => 'right', 'grid' => ['drawOnChartArea' => false]]]],
                ]);

                $monthlyChart = json_encode([
                    'type' => 'bar',
                    'data' => [
                        'labels' => $monthly['labels'],
                        'datasets' => [['label' => 'CA (FCFA)', 'data' => $monthly['amounts'], 'backgroundColor' => '#66BB6A']],
                    ],
                    'options' => ['responsive' => true, 'maintainAspectRatio' => false],
                ]);

                $yearlyChart = json_encode([
                    'type' => 'line',
                    'data' => [
                        'labels' => $yearly['labels'],
                        'datasets' => [['label' => 'CA annuel', 'data' => $yearly['amounts'], 'borderColor' => '#EF6C00', 'backgroundColor' => 'rgba(239,108,0,0.1)', 'fill' => true, 'tension' => 0.4]],
                    ],
                    'options' => ['responsive' => true, 'maintainAspectRatio' => false],
                ]);
            @endphp

            <canvas x-show="period === 'weekly'" data-chart='{!! $weeklyChart !!}'></canvas>
            <canvas x-show="period === 'monthly'" x-cloak data-chart='{!! $monthlyChart !!}'></canvas>
            <canvas x-show="period === 'yearly'" x-cloak data-chart='{!! $yearlyChart !!}'></canvas>
        </div>
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-2">
        <div class="rounded-2xl bg-white p-6 shadow-md">
            <h3 class="font-bold text-agri-primary">Actions rapides</h3>
            <div class="mt-4 flex flex-wrap gap-3">
                <a href="{{ route('super-admin.admins.create') }}" class="btn-primary text-sm">Nouvel administrateur</a>
                <a href="{{ route('super-admin.audit.index') }}" class="btn-secondary text-sm">Journal d'audit</a>
                <a href="{{ route('agri.map') }}" class="btn-secondary text-sm">Carte agricole</a>
                <a href="{{ route('locations.index') }}" class="btn-secondary text-sm">Mes localisations</a>
            </div>
        </div>
        <div class="rounded-2xl bg-gradient-to-br from-agri-primary to-agri-light p-6 text-white shadow-md">
            <h3 class="font-bold">Bienvenue, {{ auth()->user()->first_name }} 👋</h3>
            <p class="mt-2 text-white/90 text-sm">Pilotez la marketplace agricole sénégalaise depuis ce tableau de bord.</p>
                <div class="mt-5 flex flex-wrap gap-3">
                    <a href="{{ route('super-admin.notifications') }}" class="btn-secondary text-sm">Notifications</a>
                    <a href="{{ route('super-admin.withdrawals') }}" class="btn-secondary text-sm">Retraits</a>
                </div>
        </div>
    </div>
</div>
@endsection