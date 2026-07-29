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

            <form method="POST" action="{{ route('register.submit', $type) }}" class="space-y-4" enctype="multipart/form-data" data-auth-form>
                @csrf
                <div class="grid gap-4 sm:grid-cols-2">
                    <div data-auth-field>
                        <label class="mb-1 block text-sm font-medium">Prénom *</label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}" required
                               class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
                    </div>
                    <div data-auth-field>
                        <label class="mb-1 block text-sm font-medium">Nom *</label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}" required
                               class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
                    </div>
                </div>
                <div data-auth-field>
                    <label class="mb-1 block text-sm font-medium">Email *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
                </div>
                <div data-auth-field>
                    <label class="mb-1 block text-sm font-medium">CNI *</label>
                    <input type="text" name="cni" value="{{ old('cni') }}" required placeholder="Numéro d'identité national"
                           class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
                </div>
                <div data-auth-field>
                    <label class="mb-1 block text-sm font-medium">Téléphone *</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="+221 77 551 12 59"
                           class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
                </div>
                <div data-auth-field>
                    <label class="mb-1 block text-sm font-medium">Photo de profil</label>
                    <input type="file" name="photo" accept="image/*"
                           class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
                </div>

                @if($type !== 'client')
                    <div class="rounded-2xl border border-harvest/30 bg-harvest/10 p-4" data-auth-field>
                        <h2 class="font-semibold text-agri-primary">Documents d'identité (obligatoires pour vendeurs)</h2>
                        <p class="mt-2 text-xs text-gray-600">Ces documents nous permettent de valider votre compte avant publication.</p>
                        <div class="mt-4 grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="mb-1 block text-sm font-medium">Photo CNI recto *</label>
                                <input type="file" name="cni_front_photo" accept="image/*" required
                                       class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium">Photo CNI verso *</label>
                                <input type="file" name="cni_back_photo" accept="image/*" required
                                       class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
                            </div>
                        </div>
                    </div>

                    @if($type === 'producteur')
                        <div class="rounded-2xl border border-agri-primary/20 bg-agri-primary/5 p-4" data-auth-field>
                            <h2 class="font-semibold text-agri-primary">Informations exploitation</h2>
                            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="mb-1 block text-sm font-medium">Nom exploitation *</label>
                                    <input type="text" name="farm_name" value="{{ old('farm_name') }}" required
                                           class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium">Type production *</label>
                                    <input type="text" name="production_type" value="{{ old('production_type') }}" required placeholder="Maraîchage, céréales, fruits, etc."
                                           class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="mb-1 block text-sm font-medium">Photo exploitation (optionnel)</label>
                                    <input type="file" name="farm_photo" accept="image/*"
                                           class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($type === 'distributeur')
                        <div class="rounded-2xl border border-agri-primary/20 bg-agri-primary/5 p-4" data-auth-field>
                            <h2 class="font-semibold text-agri-primary">Informations commerce</h2>
                            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="mb-1 block text-sm font-medium">Nom commerce *</label>
                                    <input type="text" name="business_name" value="{{ old('business_name') }}" required
                                           class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium">Type commerce *</label>
                                    <input type="text" name="business_type" value="{{ old('business_type') }}" required placeholder="Magasin, boutique, entrepôt, etc."
                                           class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="mb-1 block text-sm font-medium">Photo commerce (optionnel)</label>
                                    <input type="file" name="business_photo" accept="image/*"
                                           class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
                                </div>
                            </div>
                        </div>
                    @endif
                @endif

                <div data-auth-field>
                    <label class="mb-1 block text-sm font-medium">Mot de passe *</label>
                    <input type="password" name="password" required minlength="8"
                           class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
                </div>
                <div data-auth-field>
                    <label class="mb-1 block text-sm font-medium">Confirmer le mot de passe *</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
                </div>

                @if($type !== 'client')
                    <div class="rounded-2xl border border-soft-gray bg-white/70 p-4" data-auth-field>
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <h2 class="text-sm font-semibold text-agri-primary">Lieu de dépôt principal</h2>
                                <p class="text-xs text-gray-500">Pour les producteurs et distributeurs, renseignez seulement le nom du lieu, la région et le type de dépôt. La position sera estimée automatiquement.</p>
                            </div>
                        </div>

                        <div class="mt-4 grid gap-4 md:grid-cols-2">
                            <div class="md:col-span-2">
                                <label class="mb-1 block text-sm font-medium">Nom du lieu *</label>
                                <input type="text" name="location_name" value="{{ old('location_name') }}" placeholder="Champ de Keur Massar, Boutique centrale, Magasin principal"
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
                                <label class="mb-1 block text-sm font-medium">Type de dépôt *</label>
                                <select name="location_type" class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
                                    <option value="">Sélectionner</option>
                                    @foreach($locationTypes as $locationType)
                                        <option value="{{ $locationType }}" @selected(old('location_type') === $locationType)>{{ ucfirst($locationType) }}</option>
                                    @endforeach
                                </select>
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
                    <div class="rounded-2xl border border-soft-gray bg-white/70 p-4" data-auth-field>
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
