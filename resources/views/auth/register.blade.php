@extends('layouts.guest')

@section('title', 'Inscription ' . $roleLabel)

@section('content')
<div class="relative flex min-h-screen items-center justify-center p-4 py-12">
    <div class="absolute inset-0">
        <img src="{{ agri_image('login_bg') }}" alt="" class="h-full w-full object-cover">
        <div class="absolute inset-0 bg-agri-primary/80"></div>
    </div>

    <div class="relative z-10 w-full max-w-lg">
        <div class="glass-card rounded-3xl p-8 md:p-10">
            <div class="mb-6 text-center">
                <h1 class="text-2xl font-bold text-agri-primary">Inscription {{ $roleLabel }}</h1>
            </div>

            @include('partials.flash')

            <form method="POST" action="{{ route('register.submit', $type) }}" class="space-y-4">
                @csrf
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium">Prénom *</label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}" required
                               class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium">Nom *</label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}" required
                               class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
                    </div>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">Email *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">Téléphone *</label>
                          <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="+221 77 551 12 59"
                           class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">Mot de passe *</label>
                    <input type="password" name="password" required minlength="8"
                           class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">Confirmer le mot de passe *</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
                </div>

                @if($type !== 'client')
                    <div class="rounded-2xl border border-soft-gray bg-white/70 p-4">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <h2 class="text-sm font-semibold text-agri-primary">Localisation principale</h2>
                                <p class="text-xs text-gray-500">Ajoutez au moins un champ ou une boutique visible sur la carte. Vous pourrez ensuite en ajouter d'autres depuis votre espace.</p>
                            </div>
                        </div>

                        <div class="mt-4 grid gap-4 md:grid-cols-2">
                            <div class="md:col-span-2">
                                <label class="mb-1 block text-sm font-medium">Nom du lieu *</label>
                                <input type="text" name="location_name" value="{{ old('location_name') }}" placeholder="Champ de Keur Massar, Boutique centrale"
                                       class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium">Région *</label>
                                <select name="location_region" class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
                                    <option value="">Sélectionner</option>
                                    @foreach($regionOptions as $region)
                                        <option value="{{ $region }}" @selected(old('location_region') === $region)>{{ $region }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium">Type *</label>
                                <select name="location_type" class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
                                    <option value="">Sélectionner</option>
                                    @foreach($locationTypes as $locationType)
                                        <option value="{{ $locationType }}" @selected(old('location_type') === $locationType)>{{ ucfirst($locationType) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium">Latitude *</label>
                                <input type="number" name="latitude" step="0.000001" min="-90" max="90" value="{{ old('latitude') }}"
                                       class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium">Longitude *</label>
                                <input type="number" name="longitude" step="0.000001" min="-180" max="180" value="{{ old('longitude') }}"
                                       class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
                            </div>
                            <div class="md:col-span-2">
                                <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                    <input type="checkbox" name="location_visible_publicly" value="1" @checked(old('location_visible_publicly', true)) class="rounded border-gray-300 text-agri-primary focus:ring-agri-primary">
                                    Rendre ce lieu visible sur la carte publique
                                </label>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="rounded-2xl border border-soft-gray bg-white/70 p-4">
                        <p class="text-sm text-gray-600">Vous pourrez ajouter plusieurs adresses de livraison depuis votre espace après inscription.</p>

                        <div class="mt-4">
                            <p class="text-sm font-semibold text-gray-700">Souhaitez-vous fixer une adresse maintenant ?</p>
                            <div class="mt-3 flex items-center gap-3">
                                <button type="button" onclick="fillLocationFromDevice(this)" class="btn-secondary">Utiliser ma position</button>
                                <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                    <input type="checkbox" name="location_visible_publicly" value="1" class="rounded border-gray-300 text-agri-primary focus:ring-agri-primary">
                                    Rendre cette adresse visible sur la carte publique
                                </label>
                            </div>

                            <input type="hidden" name="location_name" value="{{ old('location_name') }}">
                            <input type="hidden" name="latitude" value="{{ old('latitude') }}">
                            <input type="hidden" name="longitude" value="{{ old('longitude') }}">
                        </div>
                    </div>
                @endif

                <button type="submit" class="btn-primary w-full">Créer mon compte</button>
            </form>

            <p class="mt-6 text-center text-sm text-gray-600">
                <a href="{{ route('register') }}" class="text-agri-primary hover:underline">← Choisir un autre profil</a>
            </p>
            <p class="mt-2 text-center text-sm text-gray-600">
                Déjà inscrit ? <a href="{{ route('login') }}" class="font-semibold text-agri-primary hover:underline">Se connecter</a>
            </p>
        </div>

    </div>
</div>
@endsection
