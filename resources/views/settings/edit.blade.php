@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto p-4 space-y-6">
    <div class="rounded-3xl bg-gradient-to-r from-emerald-700 via-emerald-600 to-lime-500 text-white p-6 shadow-lg">
        <p class="text-sm uppercase tracking-[0.28em] text-emerald-50/80">Paramètres</p>
        <h1 class="mt-2 text-3xl font-bold">Options du compte</h1>
    </div>

    @if(session('status'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800">{{ session('status') }}</div>
    @endif

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        @if($user->isAdmin() || $user->isSuperAdmin())
            <a href="{{ route('settings.profile') }}" class="group rounded-3xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-xl">
                <h2 class="text-xl font-bold text-slate-900">Modifier profil</h2>
            </a>

            <a href="{{ route('settings.two-factor') }}" class="group rounded-3xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-xl">
                <h2 class="text-xl font-bold text-slate-900">Configurer la double authentification</h2>
            </a>
        @else
            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="mt-2 text-xl font-bold text-slate-900">Nous contacter</h2>
                <div class="mt-4 flex flex-wrap gap-3">
                    <a href="mailto:{{ config('agri.support_email') }}" class="rounded-full bg-emerald-600 px-4 py-2 text-sm font-semibold text-white">Envoyer un mail</a>
                    <a href="tel:{{ config('agri.support_phone') }}" class="rounded-full border border-emerald-200 px-4 py-2 text-sm font-semibold text-emerald-700">Appeler</a>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm opacity-90">
                <h2 class="text-xl font-bold text-slate-900">Conditions d'utilisation</h2>
                <p class="mt-3 text-sm text-slate-600">Bientôt disponible.</p>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm opacity-90">
                <h2 class="text-xl font-bold text-slate-900">Politiques de confidentialité</h2>
                <p class="mt-3 text-sm text-slate-600">Bientôt disponible.</p>
            </div>
        @endif

        <a href="{{ route('settings.password') }}" class="group rounded-3xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-xl">
            <h2 class="text-xl font-bold text-slate-900">Changer le mot de passe</h2>
        </a>

        <button type="button" onclick="document.getElementById('logout-form').submit()" class="group rounded-3xl border border-slate-200 bg-white p-5 text-left shadow-sm transition hover:-translate-y-1 hover:shadow-xl">
            <h2 class="text-xl font-bold text-slate-900">Déconnexion</h2>
        </button>

        @unless($user->isAdmin() || $user->isSuperAdmin())
            <a href="{{ route('settings.delete') }}" class="group rounded-3xl border border-rose-200 bg-rose-50 p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-xl">
                <h2 class="text-xl font-bold text-rose-900">Supprimer le compte</h2>
            </a>
        @endunless
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
    </form>
</div>
@endsection
