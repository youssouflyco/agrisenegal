@extends('layouts.guest')

@section('title', 'Inscription')

@section('content')
<div class="relative min-h-screen py-12 px-4">
    <div class="absolute inset-0">
        <img src="{{ agri_image('login_bg') }}" alt="" class="h-full w-full object-cover">
        <div class="absolute inset-0 bg-agri-primary/85"></div>
    </div>

    <div class="relative z-10 mx-auto max-w-4xl">
        <div class="text-center text-white">
            <h1 class="text-3xl font-bold md:text-4xl">Créer un compte</h1>
            <p class="mt-3 text-white/90">Choisissez votre profil sur {{ config('agri.name') }}</p>
        </div>

        <div class="mt-12 grid gap-6 md:grid-cols-3">
            @foreach([
                ['type' => 'client', 'icon' => '🛒', 'title' => 'Client', 'desc' => 'Achetez des produits agricoles frais directement auprès des producteurs.'],
                ['type' => 'producteur', 'icon' => '🌾', 'title' => 'Producteur', 'desc' => 'Vendez vos récoltes et développez votre clientèle en ligne.'],
                ['type' => 'distributeur', 'icon' => '🚚', 'title' => 'Distributeur', 'desc' => 'Approvisionnez-vous auprès des producteurs et gérez vos livraisons.'],
            ] as $card)
                <a href="{{ route('register.form', $card['type']) }}"
                   class="glass-card group rounded-2xl p-8 text-center transition hover:-translate-y-1 hover:shadow-2xl">
                    <span class="text-5xl">{{ $card['icon'] }}</span>
                    <h2 class="mt-4 text-xl font-bold text-agri-primary group-hover:text-earth">{{ $card['title'] }}</h2>
                    <p class="mt-3 text-sm text-gray-600">{{ $card['desc'] }}</p>
                    <span class="mt-6 inline-block rounded-xl bg-agri-primary px-5 py-2 text-sm font-semibold text-white">S'inscrire</span>
                </a>
            @endforeach
        </div>

        <p class="mt-10 text-center text-white/90">
            Déjà inscrit ?
            <a href="{{ url('/connexion') }}" class="font-bold text-harvest underline">Se connecter</a>
        </p>
    </div>
</div>
@endsection
