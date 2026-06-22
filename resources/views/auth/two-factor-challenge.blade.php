@extends('layouts.guest')

@section('title', 'Double authentification')

@section('content')
<div class="relative flex min-h-screen items-center justify-center p-4">
    <div class="absolute inset-0">
        <img src="{{ agri_image('login_bg') }}" alt="" class="h-full w-full object-cover">
        <div class="absolute inset-0 bg-agri-primary/80"></div>
    </div>

    <div class="relative z-10 w-full max-w-md">
        <div class="glass-card rounded-3xl p-8 md:p-10">
            <h1 class="text-center text-2xl font-bold text-agri-primary">Vérification 2FA</h1>
            <p class="mt-2 text-center text-sm text-gray-600">Entrez le code à 6 chiffres de votre application d'authentification</p>

            @include('partials.flash')

            <form method="POST" action="{{ route('two-factor.verify') }}" class="mt-8 space-y-5">
                @csrf
                <input type="text" name="code" maxlength="6" pattern="[0-9]{6}" required autofocus
                       placeholder="000000"
                       class="w-full rounded-xl border border-gray-200 px-4 py-4 text-center text-2xl tracking-[0.5em] focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
                <button type="submit" class="btn-primary w-full">Vérifier</button>
            </form>
        </div>
    </div>
</div>
@endsection
