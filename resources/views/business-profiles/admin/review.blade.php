@extends('layouts.super-admin')

@section('title', 'Vérification du profil')

@section('page-title', 'Vérification du profil professionnel')

@section('content')

<div class="mx-auto w-full max-w-5xl">
{{-- Header --}}
<div class="mb-6">
    <a
        href="{{ url()->previous() }}"
        class="text-sm text-gray-500 hover:text-agri-primary"
    >
        ← Retour
    </a>

    <div class="mt-4">
        <h1 class="text-2xl font-bold text-gray-900">
            Vérification du profil professionnel
        </h1>

        <p class="mt-1 text-sm text-gray-600">
            Consultez les informations et documents avant de valider ou rejeter ce profil.
        </p>
    </div>
</div>

@include('partials.flash')

{{-- Status --}}
<div class="mb-6 rounded-2xl border p-5
    @if($profile->status === 'APPROVED')
        border-green-200 bg-green-50
    @elseif($profile->status === 'REJECTED')
        border-red-200 bg-red-50
    @else
        border-yellow-200 bg-yellow-50
    @endif
">
    <div class="flex items-start justify-between gap-4">

        <div>
            <p class="text-xs font-medium uppercase tracking-wide
                @if($profile->status === 'APPROVED')
                    text-green-600
                @elseif($profile->status === 'REJECTED')
                    text-red-600
                @else
                    text-yellow-600
                @endif
            ">
                Statut du profil
            </p>

            <h2 class="mt-1 text-lg font-semibold
                @if($profile->status === 'APPROVED')
                    text-green-800
                @elseif($profile->status === 'REJECTED')
                    text-red-800
                @else
                    text-yellow-800
                @endif
            ">
                @if($profile->status === 'APPROVED')
                    Profil approuvé
                @elseif($profile->status === 'REJECTED')
                    Profil rejeté
                @else
                    En attente de vérification
                @endif
            </h2>

            @if($profile->status === 'REJECTED' && $profile->rejection_reason)
                <div class="mt-3 rounded-xl bg-white/70 p-4">
                    <p class="text-xs font-semibold uppercase text-red-700">
                        Motif du rejet
                    </p>

                    <p class="mt-1 text-sm text-gray-700">
                        {{ $profile->rejection_reason }}
                    </p>
                </div>
            @endif
        </div>

        <span class="rounded-full px-3 py-1 text-xs font-medium
            @if($profile->status === 'APPROVED')
                bg-green-100 text-green-700
            @elseif($profile->status === 'REJECTED')
                bg-red-100 text-red-700
            @else
                bg-yellow-100 text-yellow-700
            @endif
        ">
            {{ $profile->status }}
        </span>

    </div>
</div>

{{-- Personal information --}}
<div class="mb-6 rounded-2xl bg-white p-6 shadow-md">

    <h2 class="mb-5 text-lg font-semibold text-gray-900">
        Informations personnelles
    </h2>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">

        <div>
            <p class="text-xs font-medium text-gray-500">
                Prénom
            </p>
            <p class="mt-1 font-medium text-gray-900">
                {{ $profile->user->first_name }}
            </p>
        </div>

        <div>
            <p class="text-xs font-medium text-gray-500">
                Nom
            </p>
            <p class="mt-1 font-medium text-gray-900">
                {{ $profile->user->last_name }}
            </p>
        </div>

        <div>
            <p class="text-xs font-medium text-gray-500">
                Téléphone
            </p>
            <p class="mt-1 font-medium text-gray-900">
                {{ $profile->user->phone }}
            </p>
        </div>

        <div>
            <p class="text-xs font-medium text-gray-500">
                Email
            </p>
            <p class="mt-1 font-medium text-gray-900">
                {{ $profile->user->email }}
            </p>
        </div>

        <div>
            <p class="text-xs font-medium text-gray-500">
                Numéro CNI
            </p>
            <p class="mt-1 font-medium text-gray-900">
                {{ $profile->cni }}
            </p>
        </div>

        <div>
            <p class="text-xs font-medium text-gray-500">
                Type de profil
            </p>
            <p class="mt-1 font-medium text-gray-900">
                {{ $profile->business_type === 'PRODUCTEUR' ? 'Producteur' : 'Distributeur' }}
            </p>
        </div>

    </div>
</div>

{{-- CNI Documents --}}
<div class="mb-6 rounded-2xl bg-white p-6 shadow-md">

    <h2 class="mb-5 text-lg font-semibold text-gray-900">
        Pièce d'identité
    </h2>

    <div class="grid gap-6 md:grid-cols-2">

        {{-- CNI Front --}}
        <div>
            <p class="mb-2 text-sm font-medium text-gray-700">
                CNI - Recto
            </p>

            @if($profile->cni_front_photo)
                <a
                    href="{{ $profile->cni_front_photo }}"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    <img
                        src="{{ $profile->cni_front_photo }}"
                        alt="CNI recto"
                        class="h-72 w-full rounded-2xl border border-gray-200 object-contain bg-gray-100 transition hover:opacity-90"
                    >
                </a>
            @else
                <div class="flex h-72 items-center justify-center rounded-2xl bg-gray-100 text-sm text-gray-400">
                    Aucun document fourni
                </div>
            @endif
        </div>

        {{-- CNI Back --}}
        <div>
            <p class="mb-2 text-sm font-medium text-gray-700">
                CNI - Verso
            </p>

            @if($profile->cni_back_photo)
                <a
                    href="{{ $profile->cni_back_photo }}"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    <img
                        src="{{ $profile->cni_back_photo }}"
                        alt="CNI verso"
                        class="h-72 w-full rounded-2xl border border-gray-200 object-contain bg-gray-100 transition hover:opacity-90"
                    >
                </a>
            @else
                <div class="flex h-72 items-center justify-center rounded-2xl bg-gray-100 text-sm text-gray-400">
                    Aucun document fourni
                </div>
            @endif
        </div>

    </div>

    <p class="mt-3 text-xs text-gray-500">
        Cliquez sur une image pour l'ouvrir en taille réelle.
    </p>

</div>

{{-- Producer --}}
@if($profile->business_type === 'PRODUCTEUR')

    <div class="mb-6 rounded-2xl bg-white p-6 shadow-md">

        <h2 class="mb-5 text-lg font-semibold text-gray-900">
            Informations sur l'exploitation
        </h2>

        <div class="grid gap-4 sm:grid-cols-2">

            <div>
                <p class="text-xs font-medium text-gray-500">
                    Nom de l'exploitation
                </p>

                <p class="mt-1 font-medium text-gray-900">
                    {{ $profile->farm_name ?: '—' }}
                </p>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-500">
                    Type de production
                </p>

                <p class="mt-1 font-medium text-gray-900">
                    {{ $profile->production_type ?: '—' }}
                </p>
            </div>

        </div>

        @if($profile->farm_photo)
            <div class="mt-6">

                <p class="mb-2 text-sm font-medium text-gray-700">
                    Photo de l'exploitation
                </p>

                <a
                    href="{{ $profile->farm_photo }}"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    <img
                        src="{{ $profile->farm_photo }}"
                        alt="Photo de l'exploitation"
                        class="h-80 w-full rounded-2xl border border-gray-200 object-cover transition hover:opacity-90"
                    >
                </a>

            </div>
        @endif

    </div>

{{-- Distributor --}}
@elseif($profile->business_type === 'DISTRIBUTEUR')

    <div class="mb-6 rounded-2xl bg-white p-6 shadow-md">

        <h2 class="mb-5 text-lg font-semibold text-gray-900">
            Informations sur l'entreprise
        </h2>

        <div class="grid gap-4 sm:grid-cols-2">

            <div>
                <p class="text-xs font-medium text-gray-500">
                    Nom de l'entreprise
                </p>

                <p class="mt-1 font-medium text-gray-900">
                    {{ $profile->business_name ?: '—' }}
                </p>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-500">
                    Type d'entreprise
                </p>

                <p class="mt-1 font-medium text-gray-900">
                    {{ $profile->business_type ?: '—' }}
                </p>
            </div>

        </div>

        @if($profile->business_photo)
            <div class="mt-6">

                <p class="mb-2 text-sm font-medium text-gray-700">
                    Photo de l'entreprise
                </p>

                <a
                    href="{{ $profile->business_photo }}"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    <img
                        src="{{ $profile->business_photo }}"
                        alt="Photo de l'entreprise"
                        class="h-80 w-full rounded-2xl border border-gray-200 object-cover transition hover:opacity-90"
                    >
                </a>

            </div>
        @endif

    </div>

@endif

{{-- Review actions --}}
@if($profile->status === 'PENDING')

    <div class="rounded-2xl bg-white p-6 shadow-md">

        <h2 class="mb-2 text-lg font-semibold text-gray-900">
            Décision de vérification
        </h2>

        <p class="mb-6 text-sm text-gray-600">
            Vérifiez attentivement les informations et les documents avant de prendre une décision.
        </p>

        <div class="grid gap-4 md:grid-cols-2">

            {{-- Approve --}}
            <form
                method="POST"
                action="{{ route('super-admin.business-profiles.approve', $profile->id) }}"
            >
                @csrf

                <button
                    type="submit"
                    class="w-full rounded-xl bg-green-600 px-5 py-3.5 font-medium text-white transition hover:bg-green-700"
                    onclick="return confirm('Êtes-vous sûr de vouloir approuver ce profil ?')"
                >
                    Approuver le profil
                </button>
            </form>

            {{-- Reject --}}
            <div>
                <button
                    type="button"
                    onclick="document.getElementById('rejection-form').classList.toggle('hidden')"
                    class="w-full rounded-xl bg-red-600 px-5 py-3.5 font-medium text-white transition hover:bg-red-700"
                >
                    Rejeter le profil
                </button>
            </div>

        </div>

        {{-- Rejection form --}}
        <div
            id="rejection-form"
            class="mt-6 hidden rounded-2xl border border-red-200 bg-red-50 p-5"
        >
            <form
                method="POST"
                action="{{ route('super-admin.business-profiles.reject', $profile->id) }}"
            >
                @csrf

                <label
                    for="rejection_reason"
                    class="mb-2 block text-sm font-semibold text-gray-800"
                >
                    Motif du rejet *
                </label>

                <textarea
                    id="rejection_reason"
                    name="rejection_reason"
                    rows="4"
                    required
                    placeholder="Expliquez clairement les informations ou documents à corriger..."
                    class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm focus:border-red-500 focus:ring-2 focus:ring-red-200"
                >{{ old('rejection_reason') }}</textarea>

                @error('rejection_reason')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror

                <div class="mt-4 flex justify-end">
                    <button
                        type="submit"
                        class="rounded-xl bg-red-600 px-5 py-3 font-medium text-white transition hover:bg-red-700"
                    >
                        Confirmer le rejet
                    </button>
                </div>
            </form>
        </div>

    </div>

@elseif($profile->status === 'APPROVED')

    <div class="rounded-2xl border border-green-200 bg-green-50 p-5">
        <p class="font-medium text-green-800">
            Ce profil a été approuvé.
        </p>

        <p class="mt-1 text-sm text-green-700">
            Aucune action supplémentaire n'est requise.
        </p>
    </div>

@elseif($profile->status === 'REJECTED')

    <div class="rounded-2xl border border-red-200 bg-red-50 p-5">
        <p class="font-medium text-red-800">
            Ce profil a été rejeté.
        </p>

        @if($profile->rejection_reason)
            <p class="mt-1 text-sm text-red-700">
                Le motif du rejet est affiché ci-dessus.
            </p>
        @endif
    </div>

@endif
</div>

@endsection
