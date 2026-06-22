@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-4">
    <div class="mb-6 flex items-center justify-between gap-4">
        <div>
            <p class="text-sm uppercase tracking-[0.2em] text-rose-600">Paramètres</p>
            <h1 class="text-2xl font-semibold text-rose-700">Supprimer le compte</h1>
        </div>
        <a href="{{ route('settings.edit') }}" class="text-sm font-semibold text-emerald-700">Retour</a>
    </div>

    @if($user->isAdmin() || $user->isSuperAdmin())
        <div class="rounded-3xl border border-amber-200 bg-amber-50 p-5 text-amber-900">
            Les comptes administrateurs ne peuvent pas être supprimés via cette interface.
        </div>
    @else
        <div class="rounded-3xl border border-rose-200 bg-rose-50 p-5 shadow-sm">
            <p class="font-semibold text-rose-900">Cette action archive votre compte et vous déconnecte.</p>
            <p class="mt-2 text-sm text-rose-800">Aucune confirmation par mail n'est envoyée. Une alerte de confirmation s'affiche avant validation finale.</p>

            <form method="POST" action="{{ route('settings.destroy') }}" class="mt-5 space-y-4" onsubmit="return confirm('Confirmez-vous la suppression de votre compte ?');">
                @csrf
                @method('DELETE')
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Mot de passe</label>
                    <input name="password" type="password" class="w-full rounded-2xl border border-slate-300 px-4 py-3" />
                </div>
                <div class="flex justify-end">
                    <button class="rounded-full bg-rose-600 px-5 py-3 font-semibold text-white">Supprimer mon compte</button>
                </div>
            </form>
        </div>
    @endif
</div>
@endsection
