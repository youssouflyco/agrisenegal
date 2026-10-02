@extends('layouts.dashboard')

@section('title', 'Business Profile')

@section('content')

<div class="relative flex min-h-screen items-center justify-center p-4 py-12">
    <div class="relative z-10 w-full max-w-3xl">
        <div class="glass-card rounded-3xl p-8 md:p-10">

            {{-- Header --}}
            <div class="mb-8 text-center">
                <h1 class="text-2xl font-bold text-agri-primary">
                    Profil professionnel
                </h1>

                <p class="mt-2 text-sm text-gray-600">
                    Consultez les informations de votre profil professionnel et son statut de vérification.
                </p>
            </div>

            @include('partials.flash')

            {{-- Status --}}
            <div class="mb-8 rounded-2xl border p-5
                @if($profile->status === 'APPROVED')
                    border-green-200 bg-green-50
                @elseif($profile->status === 'REJECTED')
                    border-red-200 bg-red-50
                @else
                    border-yellow-200 bg-yellow-50
                @endif
            ">
                <div class="flex items-start gap-3">
                    <div class="flex-1">

                        <h2 class="font-semibold
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
                                Vérification en cours
                            @endif
                        </h2>

                        <p class="mt-1 text-sm
                            @if($profile->status === 'APPROVED')
                                text-green-700
                            @elseif($profile->status === 'REJECTED')
                                text-red-700
                            @else
                                text-yellow-700
                            @endif
                        ">
                            @if($profile->status === 'APPROVED')
                                Votre profil professionnel a été approuvé. Vous pouvez maintenant publier vos produits.
                            @elseif($profile->status === 'REJECTED')
                                Votre profil a été rejeté. Corrigez les informations ci-dessous puis soumettez à nouveau votre profil.
                            @else
                                Votre profil est actuellement en cours de vérification par un administrateur.
                            @endif
                        </p>

                        {{-- Rejection reason --}}
                        @if($profile->status === 'REJECTED' && $profile->rejection_reason)
                            <div class="mt-4 rounded-xl bg-white/70 p-4">
                                <p class="text-sm font-medium text-red-800">
                                    Motif du rejet
                                </p>

                                <p class="mt-1 text-sm text-gray-700">
                                    {{ $profile->rejection_reason }}
                                </p>
                            </div>
                        @endif

                    </div>
                </div>
            </div>

            {{-- Profile Form --}}
            <form
                method="POST"
                action="{{ route('business-profile.update') }}"
                enctype="multipart/form-data"
            >
                @csrf
                @method('PATCH')

                {{-- ===================================================== --}}
                {{-- PERSONAL INFORMATION --}}
                {{-- ===================================================== --}}
                <div class="mb-8">

                    <h2 class="mb-4 text-lg font-semibold text-gray-900">
                        Informations personnelles
                    </h2>

                    <div class="grid gap-4 sm:grid-cols-2">

                        {{-- CNI Number --}}
                        <div>
                            <label
                                for="cni"
                                class="mb-2 block text-sm font-medium text-gray-700"
                            >
                                Numéro CNI
                            </label>

                            <input
                                type="text"
                                id="cni"
                                name="cni"
                                value="{{ old('cni', $profile->cni) }}"
                                @disabled($profile->status !== 'REJECTED')
                                class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-gray-900
                                    focus:border-agri-primary focus:ring-2 focus:ring-agri-light/40
                                    disabled:cursor-not-allowed disabled:bg-gray-100 disabled:text-gray-500"
                            >

                            @error('cni')
                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                Statut
                            </label>

                            <div class="rounded-xl border border-gray-200 bg-gray-100 px-4 py-3 text-gray-600">
                                @if($profile->status === 'APPROVED')
                                    Approuvé
                                @elseif($profile->status === 'REJECTED')
                                    Rejeté
                                @else
                                    En attente
                                @endif
                            </div>
                        </div>

                    </div>

                    {{-- ================================================= --}}
                    {{-- CNI DOCUMENTS --}}
                    {{-- ================================================= --}}
                    <div class="mt-6 rounded-2xl border border-gray-200 bg-white/50 p-5">

                        <h3 class="mb-4 text-base font-semibold text-gray-900">
                            Pièce d'identité
                        </h3>

                        <div class="grid gap-6 md:grid-cols-2">

                            {{-- CNI FRONT --}}
                            <div>

                                <p class="mb-2 text-sm font-medium text-gray-700">
                                    CNI - Recto
                                </p>

                                @if($profile->cni_front_photo)

                                    <a
                                        href="{{ $profile->cni_front_photo }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="block"
                                    >
                                        <img
                                            src="{{ $profile->cni_front_photo }}"
                                            alt="CNI recto"
                                            class="h-64 w-full rounded-2xl border border-gray-200 bg-gray-100 object-contain transition hover:opacity-90"
                                        >
                                    </a>

                                @else

                                    <div class="flex h-64 items-center justify-center rounded-2xl border border-gray-200 bg-gray-100 text-sm text-gray-400">
                                        Aucun document fourni
                                    </div>

                                @endif

                                {{-- Replace CNI front --}}
                                @if($profile->status === 'REJECTED')

                                    <div class="mt-3">

                                        <label
                                            for="cni_front_photo"
                                            class="mb-2 block text-sm font-medium text-gray-700"
                                        >
                                            Remplacer le recto
                                        </label>

                                        <input
                                            type="file"
                                            id="cni_front_photo"
                                            name="cni_front_photo"
                                            accept="image/*"
                                            class="block w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-700"
                                        >

                                        @error('cni_front_photo')
                                            <p class="mt-1 text-sm text-red-600">
                                                {{ $message }}
                                            </p>
                                        @enderror

                                    </div>

                                @endif

                            </div>

                            {{-- CNI BACK --}}
                            <div>

                                <p class="mb-2 text-sm font-medium text-gray-700">
                                    CNI - Verso
                                </p>

                                @if($profile->cni_back_photo)

                                    <a
                                        href="{{ $profile->cni_back_photo }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="block"
                                    >
                                        <img
                                            src="{{ $profile->cni_back_photo }}"
                                            alt="CNI verso"
                                            class="h-64 w-full rounded-2xl border border-gray-200 bg-gray-100 object-contain transition hover:opacity-90"
                                        >
                                    </a>

                                @else

                                    <div class="flex h-64 items-center justify-center rounded-2xl border border-gray-200 bg-gray-100 text-sm text-gray-400">
                                        Aucun document fourni
                                    </div>

                                @endif

                                {{-- Replace CNI back --}}
                                @if($profile->status === 'REJECTED')

                                    <div class="mt-3">

                                        <label
                                            for="cni_back_photo"
                                            class="mb-2 block text-sm font-medium text-gray-700"
                                        >
                                            Remplacer le verso
                                        </label>

                                        <input
                                            type="file"
                                            id="cni_back_photo"
                                            name="cni_back_photo"
                                            accept="image/*"
                                            class="block w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-700"
                                        >

                                        @error('cni_back_photo')
                                            <p class="mt-1 text-sm text-red-600">
                                                {{ $message }}
                                            </p>
                                        @enderror

                                    </div>

                                @endif

                            </div>

                        </div>

                        <p class="mt-3 text-xs text-gray-500">
                            Cliquez sur une image pour l'ouvrir en taille réelle.
                        </p>

                    </div>

                </div>


                {{-- ===================================================== --}}
                {{-- PRODUCER INFORMATION --}}
                {{-- ===================================================== --}}
                @if(auth()->user()->role === \App\Enums\UserRole::Producer)

                    <div class="mb-8 border-t border-gray-200 pt-6">

                        <h2 class="mb-4 text-lg font-semibold text-gray-900">
                            Informations sur votre exploitation
                        </h2>

                        <div class="space-y-4">

                            {{-- Farm name --}}
                            <div>

                                <label
                                    for="farm_name"
                                    class="mb-2 block text-sm font-medium text-gray-700"
                                >
                                    Nom de l'exploitation
                                </label>

                                <input
                                    type="text"
                                    id="farm_name"
                                    name="farm_name"
                                    value="{{ old('farm_name', $profile->farm_name) }}"
                                    @disabled($profile->status !== 'REJECTED')
                                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-gray-900
                                        focus:border-agri-primary focus:ring-2 focus:ring-agri-light/40
                                        disabled:cursor-not-allowed disabled:bg-gray-100 disabled:text-gray-500"
                                >

                                @error('farm_name')
                                    <p class="mt-1 text-sm text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>


                            {{-- Production type --}}
                            <div>

                                <label
                                    for="production_type"
                                    class="mb-2 block text-sm font-medium text-gray-700"
                                >
                                    Type de production
                                </label>

                                <input
                                    type="text"
                                    id="production_type"
                                    name="production_type"
                                    value="{{ old('production_type', $profile->production_type) }}"
                                    @disabled($profile->status !== 'REJECTED')
                                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-gray-900
                                        focus:border-agri-primary focus:ring-2 focus:ring-agri-light/40
                                        disabled:cursor-not-allowed disabled:bg-gray-100 disabled:text-gray-500"
                                >

                                @error('production_type')
                                    <p class="mt-1 text-sm text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>


                            {{-- Farm photo --}}
                            <div>

                                <label
                                    for="farm_photo"
                                    class="mb-2 block text-sm font-medium text-gray-700"
                                >
                                    Photo de l'exploitation
                                </label>

                                @if($profile->farm_photo)

                                    <img
                                        src="{{ $profile->farm_photo }}"
                                        alt="Photo de l'exploitation"
                                        class="mb-4 h-64 w-full rounded-2xl object-cover"
                                    >

                                @endif

                                @if($profile->status === 'REJECTED')

                                    <input
                                        type="file"
                                        id="farm_photo"
                                        name="farm_photo"
                                        accept="image/*"
                                        class="block w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-700"
                                    >

                                    @error('farm_photo')
                                        <p class="mt-1 text-sm text-red-600">
                                            {{ $message }}
                                        </p>
                                    @enderror

                                @endif

                            </div>

                        </div>

                    </div>


                {{-- ===================================================== --}}
                {{-- DISTRIBUTOR INFORMATION --}}
                {{-- ===================================================== --}}
                @elseif(auth()->user()->role === \App\Enums\UserRole::Distributor)

                    <div class="mb-8 border-t border-gray-200 pt-6">

                        <h2 class="mb-4 text-lg font-semibold text-gray-900">
                            Informations sur votre entreprise
                        </h2>

                        <div class="space-y-4">

                            {{-- Business name --}}
                            <div>

                                <label
                                    for="business_name"
                                    class="mb-2 block text-sm font-medium text-gray-700"
                                >
                                    Nom de l'entreprise
                                </label>

                                <input
                                    type="text"
                                    id="business_name"
                                    name="business_name"
                                    value="{{ old('business_name', $profile->business_name) }}"
                                    @disabled($profile->status !== 'REJECTED')
                                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-gray-900
                                        focus:border-agri-primary focus:ring-2 focus:ring-agri-light/40
                                        disabled:cursor-not-allowed disabled:bg-gray-100 disabled:text-gray-500"
                                >

                                @error('business_name')
                                    <p class="mt-1 text-sm text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>


                            {{-- Business type --}}
                            <div>

                                <label
                                    for="business_type"
                                    class="mb-2 block text-sm font-medium text-gray-700"
                                >
                                    Type d'entreprise
                                </label>

                                <input
                                    type="text"
                                    id="business_type"
                                    name="business_type"
                                    value="{{ old('business_type', $profile->business_type) }}"
                                    @disabled($profile->status !== 'REJECTED')
                                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-gray-900
                                        focus:border-agri-primary focus:ring-2 focus:ring-agri-light/40
                                        disabled:cursor-not-allowed disabled:bg-gray-100 disabled:text-gray-500"
                                >

                                @error('business_type')
                                    <p class="mt-1 text-sm text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>


                            {{-- Business photo --}}
                            <div>

                                <label
                                    for="business_photo"
                                    class="mb-2 block text-sm font-medium text-gray-700"
                                >
                                    Photo de l'entreprise
                                </label>

                                @if($profile->business_photo)

                                    <img
                                        src="{{ $profile->business_photo }}"
                                        alt="Photo de l'entreprise"
                                        class="mb-4 h-64 w-full rounded-2xl object-cover"
                                    >

                                @endif

                                @if($profile->status === 'REJECTED')

                                    <input
                                        type="file"
                                        id="business_photo"
                                        name="business_photo"
                                        accept="image/*"
                                        class="block w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-700"
                                    >

                                    @error('business_photo')
                                        <p class="mt-1 text-sm text-red-600">
                                            {{ $message }}
                                        </p>
                                    @enderror

                                @endif

                            </div>

                        </div>

                    </div>

                @endif


                {{-- ===================================================== --}}
                {{-- ACTIONS --}}
                {{-- ===================================================== --}}
                @if($profile->status === 'REJECTED')

                    <div class="border-t border-gray-200 pt-6">

                        <button
                            type="submit"
                            class="btn-primary w-full rounded-xl py-3.5 font-medium"
                        >
                            Modifier et soumettre à nouveau
                        </button>

                    </div>

                @elseif($profile->status === 'PENDING')

                    <div class="border-t border-gray-200 pt-6">

                        <p class="text-center text-sm text-gray-500">
                            Votre profil est en cours de vérification.
                            Vous ne pouvez pas encore le modifier.
                        </p>

                    </div>

                @elseif($profile->status === 'APPROVED')

                    <div class="border-t border-gray-200 pt-6">

                        <p class="text-center text-sm text-green-700">
                            Votre profil est approuvé.
                            Vous pouvez maintenant publier vos produits.
                        </p>

                    </div>

                @endif

            </form>

        </div>
    </div>
</div>

@endsection
