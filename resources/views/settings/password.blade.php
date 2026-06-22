@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-4">
    <div class="mb-6 flex items-center justify-between gap-4">
        <div>
            <p class="text-sm uppercase tracking-[0.2em] text-sky-600">Paramètres</p>
            <h1 class="text-2xl font-semibold">Changer le mot de passe</h1>
        </div>
        <a href="{{ route('settings.edit') }}" class="text-sm font-semibold text-emerald-700">Retour</a>
    </div>

    @if(session('status'))
        <div class="mb-4 rounded-2xl bg-emerald-100 px-4 py-3 text-emerald-800">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('settings.password.update') }}" class="space-y-4 rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
        @csrf
        @method('PUT')
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Mot de passe actuel</label>
            <input name="current_password" type="password" class="w-full rounded-2xl border border-slate-300 px-4 py-3" />
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Nouveau mot de passe</label>
            <input name="password" type="password" class="w-full rounded-2xl border border-slate-300 px-4 py-3" />
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Confirmer le nouveau mot de passe</label>
            <input name="password_confirmation" type="password" class="w-full rounded-2xl border border-slate-300 px-4 py-3" />
        </div>
        <div class="flex justify-end">
            <button class="rounded-full bg-sky-600 px-5 py-3 font-semibold text-white">Modifier</button>
        </div>
    </form>
</div>
@endsection
