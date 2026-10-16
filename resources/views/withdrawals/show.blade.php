@extends('layouts.super-admin')

@section('title', 'Détail du retrait')
@section('page-title', 'Détail du retrait')

@section('content')
<div class="mx-auto max-w-3xl space-y-6 p-4 md:p-6">

    <div class="flex items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Demande de retrait</h1>
            <p class="mt-1 text-sm text-gray-500">
                Consultez et mettez à jour cette demande.
            </p>
        </div>

        <a href="{{ route('withdrawals.index') }}" class="btn-secondary">
            Retour
        </a>
    </div>

    {{-- Withdrawal details --}}
    <div class="rounded-2xl bg-white p-6 shadow-md">
        <h2 class="mb-5 text-lg font-semibold text-agri-primary">
            Informations du retrait
        </h2>

        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <p class="text-sm text-gray-500">Utilisateur</p>
                <p class="mt-1 font-medium text-gray-900">
                    {{ $withdrawal->user?->full_name ?? '—' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Montant demandé</p>
                <p class="mt-1 text-lg font-bold text-agri-primary">
                    {{ number_format($withdrawal->amount, 0, ',', ' ') }} FCFA
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Méthode de retrait</p>
                <p class="mt-1 font-medium text-gray-900">
                    {{ ucfirst(str_replace('_', ' ', $withdrawal->method)) }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Date de la demande</p>
                <p class="mt-1 font-medium text-gray-900">
                    {{ $withdrawal->created_at?->format('d/m/Y à H:i') ?? '—' }}
                </p>
            </div>

            <div class="sm:col-span-2">
                <p class="text-sm text-gray-500">Note de l'utilisateur</p>
                <p class="mt-1 whitespace-pre-line text-gray-700">
                    {{ $withdrawal->note ?: 'Aucune note fournie.' }}
                </p>
            </div>

            <div class="sm:col-span-2">
                <p class="text-sm text-gray-500">Statut actuel</p>
                @php
                    $statusClasses = [
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'approved' => 'bg-green-100 text-green-800',
                        'rejected' => 'bg-red-100 text-red-800',
                    ];

                    $statusLabels = [
                        'pending' => 'En attente',
                        'approved' => 'Approuvé',
                        'rejected' => 'Rejeté',
                    ];
                @endphp

                <span class="mt-2 inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses[$withdrawal->status] ?? 'bg-gray-100 text-gray-700' }}">
                    {{ $statusLabels[$withdrawal->status] ?? ucfirst($withdrawal->status) }}
                </span>
            </div>
        </div>
    </div>

    {{-- Admin update form --}}
    @if($withdrawal->status === 'pending')
    <div class="rounded-2xl bg-white p-6 shadow-md">
        <h2 class="mb-5 text-lg font-semibold text-agri-primary">
            Traiter la demande
        </h2>

        <form
            method="POST"
            action="{{ route('withdrawals.update', $withdrawal) }}"
            class="space-y-5"
        >
            @csrf
            @method('PATCH')

            <div>
                <label for="status" class="mb-2 block text-sm font-medium text-gray-700">
                    Statut *
                </label>

                <select
                    id="status"
                    name="status"
                    required
                    class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-agri-primary focus:outline-none focus:ring-2 focus:ring-agri-light/30"
                >
                    <option value="pending" @selected(old('status', $withdrawal->status) === 'pending')>
                        En attente
                    </option>
                    <option value="approved" @selected(old('status', $withdrawal->status) === 'approved')>
                        Approuvé
                    </option>
                    <option value="rejected" @selected(old('status', $withdrawal->status) === 'rejected')>
                        Rejeté
                    </option>
                </select>

                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="response_note" class="mb-2 block text-sm font-medium text-gray-700">
                    Note de traitement
                </label>

                <textarea
                    id="response_note"
                    name="response_note"
                    rows="4"
                    class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-agri-primary focus:outline-none focus:ring-2 focus:ring-agri-light/30"
                    placeholder="Ajoutez une explication ou une réponse à l'utilisateur..."
                >{{ old('response_note', $withdrawal->response_note) }}</textarea>

                @error('response_note')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="btn-primary">
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
    @endif

</div>
@endsection
