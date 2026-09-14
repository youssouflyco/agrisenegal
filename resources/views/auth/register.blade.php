@extends('layouts.guest')

@section('title', 'Inscription')

@section('content')
<div class="relative flex min-h-screen items-center justify-center p-4 py-12">
    <div class="absolute inset-0">
        <img src="{{ agri_image('login_bg') }}" alt="" class="h-full w-full object-cover">
        <div class="absolute inset-0 bg-agri-primary/80"></div>
    </div>

    <div class="relative z-10 w-full max-w-lg">
        <div class="glass-card rounded-3xl p-8 md:p-10">
            <div class="mb-6 text-center">
                <h1 class="text-2xl font-bold text-agri-primary">Inscription</h1>
            </div>

            @include('partials.flash')

            <form method="POST" action="{{ route('register.submit') }}" class="space-y-4" enctype="multipart/form-data" data-auth-form>
                @csrf
                <div class="grid gap-4 sm:grid-cols-2">
                    <div data-auth-field>
                        <label class="mb-1 block text-sm font-medium">Prénom *</label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}" required
                               class="w-full rounded-xl border border-black-200 px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
                    </div>
                    <div data-auth-field>
                        <label class="mb-1 block text-sm font-medium">Nom *</label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}" required
                               class="w-full rounded-xl border border-black-200 px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
                    </div>
                </div>
                <div data-auth-field>
                    <label class="mb-1 block text-sm font-medium">Email *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full rounded-xl border border-black-200 px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
                </div>
                <div data-auth-field>
                    <label class="mb-1 block text-sm font-medium">Téléphone *</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="+221 77 551 12 59"
                           class="w-full rounded-xl border border-black-200 px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
                </div>
                <div data-auth-field>
                    <label class="mb-1 block text-sm font-medium">Role *</label>
                    <select name="role" value="{{ old('role') }}" required
                            class="w-full rounded-xl border border-black-200 px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
                        <option value="">Sélectionner un rôle</option>
                        <option value="client" {{ old('role') == 'client' ? 'selected' : '' }}>Client</option>
                        <option value="producteur" {{ old('role') == 'producteur' ? 'selected' : '' }}>Producteur</option>
                        <option value="distributeur" {{ old('role') == 'distributeur' ? 'selected' : '' }}>Distributeur</option>
                    </select>
                </div>
                <div data-auth-field>
                    <label class="mb-1 block text-sm font-medium">Mot de passe *</label>
                    <input type="password" name="password" required minlength="8"
                           class="w-full rounded-xl border border-black-200 px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
                </div>
                <div data-auth-field>
                    <label class="mb-1 block text-sm font-medium">Confirmer le mot de passe *</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full rounded-xl border border-black-200 px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
                </div>

                <button type="submit" class="btn-primary w-full">Créer mon compte</button>
            </form>
            <p class="mt-2 text-center text-sm text-gray-600">
                Déjà inscrit ? <a href="{{ route('login') }}" class="font-semibold text-agri-primary hover:underline">Se connecter</a>
            </p>
        </div>

    </div>
</div>
@endsection
