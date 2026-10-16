@extends('layouts.super-admin')

@section('title', 'Détail de la réclamation')

@section('content')

   @php
        $isAdmin = in_array(
            auth()->user()->role->value,
            ['admin', 'super_admin']
        );

        $statusLabels = [
            'open' => 'Ouverte',
            'in_progress' => 'En cours',
            'resolved' => 'Résolue',
            'rejected' => 'Rejetée',
        ];

        $statusClasses = [
            'open' => 'bg-yellow-100 text-yellow-800',
            'in_progress' => 'bg-blue-100 text-blue-800',
            'resolved' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
        ];
    @endphp

<div class="mx-auto max-w-3xl space-y-6 p-4 md:p-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                Détail de la réclamation
            </h1>
            <p class="mt-1 text-sm text-gray-500">
                Consultez les informations et le traitement de cette réclamation.
            </p>
        </div>

        <a href="{{ route('admin.claims') }}" class="btn-secondary">
            Retour
        </a>
    </div>



    {{-- Claim details --}}
    <div class="rounded-2xl bg-white p-6 shadow-sm">
        <div class="space-y-5">

            <div>
                <p class="text-sm text-gray-500">Client</p>
                <p class="mt-1 font-semibold text-gray-900">
                    {{ $complaint->user?->full_name ?? '—' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Produit concerné</p>
                <p class="mt-1 font-semibold text-gray-900">
                    {{ $complaint->product?->name ?? 'Réclamation générale' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Sujet</p>
                <p class="mt-1 font-semibold text-gray-900">
                    {{ $complaint->subject }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Message</p>
                <div class="mt-1 whitespace-pre-line rounded-xl bg-gray-50 p-4 text-gray-700">{{ $complaint->message }}</div>
            </div>

            <div>
                <p class="text-sm text-gray-500">Statut actuel</p>
                <span class="mt-2 inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses[$complaint->status] ?? 'bg-gray-100 text-gray-700' }}">
                    {{ $statusLabels[$complaint->status] ?? ucfirst(str_replace('_', ' ', $complaint->status)) }}
                </span>
            </div>

            @if($complaint->resolution_note)
                <div>
                    <p class="text-sm text-gray-500">Note de résolution</p>
                    <div class="mt-1 whitespace-pre-line rounded-xl bg-green-50 p-4 text-gray-700">{{ $complaint->resolution_note }}</div>
                </div>
            @endif

            <div>
                <p class="text-sm text-gray-500">Date de création</p>
                <p class="mt-1 text-gray-700">
                    {{ $complaint->created_at?->format('d/m/Y à H:i') ?? '—' }}
                </p>
            </div>

        </div>
    </div>

    {{-- Admin update form --}}
    @if($isAdmin && in_array($complaint->status, ['open', 'in_progress']))
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h2 class="mb-5 text-lg font-semibold text-gray-900">
                Traiter la réclamation
            </h2>

            <form
                method="POST"
                action="{{ route('admin.claims.resolve', $complaint) }}"
                class="space-y-5"
            >
                @csrf
                @method('PATCH')

                <div>
                    <label for="status" class="mb-2 block text-sm font-medium text-gray-700">
                        Statut
                    </label>

                    <select
                        id="status"
                        name="status"
                        required
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-agri-primary focus:outline-none focus:ring-2 focus:ring-agri-primary/20"
                    >
                        <option value="open" @selected(old('status', $complaint->status) === 'open')>
                            Ouverte
                        </option>
                        <option value="in_progress" @selected(old('status', $complaint->status) === 'in_progress')>
                            En cours
                        </option>
                        <option value="resolved" @selected(old('status', $complaint->status) === 'resolved')>
                            Résolue
                        </option>
                        <option value="rejected" @selected(old('status', $complaint->status) === 'rejected')>
                            Rejetée
                        </option>
                    </select>

                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="resolution_note" class="mb-2 block text-sm font-medium text-gray-700">
                        Note de résolution / réponse au client
                    </label>

                    <textarea
                        id="resolution_note"
                        name="resolution_note"
                        rows="5"
                        maxlength="5000"
                        placeholder="Ajoutez une réponse ou expliquez les mesures prises..."
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-agri-primary focus:outline-none focus:ring-2 focus:ring-agri-primary/20"
                    >{{ old('resolution_note', $complaint->resolution_note) }}</textarea>

                    @error('resolution_note')
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
