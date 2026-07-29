@extends('layouts.guest')

@section('title', 'Mot de passe oublié')

@section('content')
<div class="relative flex min-h-screen items-center justify-center p-4">
    <div class="absolute inset-0">
        <img src="{{ agri_image('login_bg') }}" alt="" class="h-full w-full object-cover">
        <div class="absolute inset-0 bg-agri-primary/80"></div>
    </div>

    <div class="relative z-10 w-full max-w-md">
        <div class="glass-card rounded-3xl p-8 md:p-10">
            <h1 class="text-2xl font-bold text-agri-primary">Mot de passe oublié</h1>
            <p class="mt-2 text-sm text-gray-600">Nous vous enverrons un lien de réinitialisation par email.</p>

            @include('partials.flash')

            <form method="POST" action="{{ route('password.email') }}" class="mt-8 space-y-5" data-auth-form>
                @csrf
                <div data-auth-field>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="Votre email"
                           class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
                </div>
                <button type="submit" class="btn-primary w-full">Envoyer le lien</button>
            </form>
            <a href="{{ route('login') }}" class="mt-6 block text-center text-sm text-agri-primary hover:underline">← Retour connexion</a>
        </div>
    </div>
</div>
@endsection
