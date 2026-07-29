@extends('layouts.guest')

@section('title', 'Connexion')

@section('content')
<div class="relative flex min-h-screen items-center justify-center p-4">
    <div class="absolute inset-0">
        <img src="{{ agri_image('login_bg') }}" alt="" class="h-full w-full object-cover">
        <div class="absolute inset-0 bg-agri-primary/80"></div>
    </div>

    <div class="relative z-10 w-full max-w-md">
        <div class="glass-card rounded-3xl p-8 md:p-10">
            <div class="mb-8 text-center">
                <span class="inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-agri-primary text-2xl font-bold text-white">A</span>
                <h1 class="mt-4 text-2xl font-bold text-agri-primary">{{ config('agri.name') }}</h1>
            </div>

            @include('partials.flash')

            <form method="POST" action="{{ route('login') }}" class="space-y-5" data-auth-form>
                @csrf
                <div data-auth-field>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full rounded-xl border border-gray-200 bg-white/90 px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
                </div>
                <div data-auth-field>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Mot de passe</label>
                    <input type="password" name="password" required
                           class="w-full rounded-xl border border-gray-200 bg-white/90 px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
                </div>
                <div class="flex items-center justify-between text-sm" data-auth-field>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-agri-primary">
                        Se souvenir de moi
                    </label>
                    <a href="{{ route('password.request') }}" class="font-medium text-agri-primary hover:underline">Mot de passe oublié ?</a>
                </div>
                <button type="submit" class="btn-primary w-full">Se connecter</button>
            </form>

            <p class="mt-6 text-center text-sm text-gray-600">
                Pas encore de compte ?
                <a href="{{ url('/inscription') }}" class="font-semibold text-agri-primary hover:underline">S'inscrire</a>
            </p>

            <a href="{{ route('home') }}" class="mt-4 block text-center text-sm text-gray-500 hover:text-agri-primary">← Retour à l'accueil</a>
        </div>
    </div>
</div>
@endsection
