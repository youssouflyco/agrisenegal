@extends('layouts.super-admin')

@section('title', $title)
@section('page-title', $title)

@section('content')
<div class="flex min-h-[50vh] flex-col items-center justify-center rounded-2xl bg-white p-12 text-center shadow-md">
    <span class="text-6xl">🌱</span>
    <h2 class="mt-6 text-2xl font-bold text-agri-primary">{{ $title }}</h2>
    <p class="mt-3 max-w-md text-gray-600">Ce module sera disponible dans une prochaine version de la plateforme.</p>

    @if($section === 'settings')
        <div class="mt-8 w-full max-w-md rounded-xl border border-soft-gray p-6 text-left">
            <h3 class="font-bold text-agri-primary">Double authentification</h3>
            <p class="mt-2 text-sm text-gray-600">Renforcez la sécurité de votre compte Super Admin.</p>
            @if(auth()->user()->two_factor_enabled)
                <p class="mt-4 text-sm text-agri-primary font-medium">✓ 2FA activée</p>
                <form method="POST" action="{{ route('super-admin.two-factor.disable') }}" class="mt-4 space-y-3">
                    @csrf
                    <input type="password" name="password" required placeholder="Mot de passe pour désactiver" class="w-full rounded-xl border px-4 py-2 text-sm">
                    <button type="submit" class="rounded-xl border border-red-300 px-4 py-2 text-sm text-red-600 hover:bg-red-50">Désactiver la 2FA</button>
                </form>
            @else
                <a href="{{ route('super-admin.two-factor.setup') }}" class="btn-primary mt-4 inline-block text-sm">Configurer la 2FA</a>
            @endif
        </div>
    @endif

    <a href="{{ route('super-admin.dashboard') }}" class="btn-secondary mt-8 text-sm">Retour au dashboard</a>
</div>
@endsection
