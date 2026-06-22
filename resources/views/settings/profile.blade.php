@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-4">
    <div class="mb-6 flex items-center justify-between gap-4">
        <div>
            <p class="text-sm uppercase tracking-[0.2em] text-emerald-600">Paramètres</p>
            <h1 class="text-2xl font-semibold">Modifier le profil</h1>
        </div>
        <a href="{{ route('settings.edit') }}" class="text-sm font-semibold text-emerald-700">Retour</a>
    </div>

    @if(session('status'))
        <div class="mb-4 rounded-2xl bg-emerald-100 px-4 py-3 text-emerald-800">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('settings.profile.update') }}" class="space-y-4 rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
        @csrf
        @method('PUT')
        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Prénom</label>
                <input name="first_name" value="{{ old('first_name', $user->first_name) }}" class="w-full rounded-2xl border border-slate-300 px-4 py-3" />
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Nom</label>
                <input name="last_name" value="{{ old('last_name', $user->last_name) }}" class="w-full rounded-2xl border border-slate-300 px-4 py-3" />
            </div>
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Téléphone</label>
            <input name="phone" value="{{ old('phone', $user->phone) }}" class="w-full rounded-2xl border border-slate-300 px-4 py-3" />
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Email</label>
            <input name="email" value="{{ old('email', $user->email) }}" class="w-full rounded-2xl border border-slate-300 px-4 py-3" />
        </div>
        <div class="flex justify-end">
            <button class="rounded-full bg-emerald-600 px-5 py-3 font-semibold text-white">Enregistrer</button>
        </div>
    </form>
</div>
@endsection
