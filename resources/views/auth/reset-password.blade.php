@extends('layouts.guest')

@section('title', 'Réinitialiser le mot de passe')

@section('content')
<div class="relative flex min-h-screen items-center justify-center p-4">
    <div class="absolute inset-0">
        <img src="{{ agri_image('login_bg') }}" alt="" class="h-full w-full object-cover">
        <div class="absolute inset-0 bg-agri-primary/80"></div>
    </div>

    <div class="relative z-10 w-full max-w-md">
        <div class="glass-card rounded-3xl p-8">
            <h1 class="text-2xl font-bold text-agri-primary">Nouveau mot de passe</h1>
            @include('partials.flash')
            <form method="POST" action="{{ route('password.update') }}" class="mt-6 space-y-4">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="email" name="email" value="{{ $email }}" required class="w-full rounded-xl border px-4 py-3">
                <input type="password" name="password" required placeholder="Nouveau mot de passe" class="w-full rounded-xl border px-4 py-3">
                <input type="password" name="password_confirmation" required placeholder="Confirmer" class="w-full rounded-xl border px-4 py-3">
                <button type="submit" class="btn-primary w-full">Réinitialiser</button>
            </form>
        </div>
    </div>
</div>
@endsection
