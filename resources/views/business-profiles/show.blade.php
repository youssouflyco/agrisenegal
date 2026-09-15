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
            @if($profile->verification_status === 'APPROVED')
                border-green-200 bg-green-50
            @elseif($profile->verification_status === 'REJECTED')
                border-red-200 bg-red-50
            @else
                border-yellow-200 bg-yellow-50
            @endif
        ">
            <div class="flex items-start gap-3">

                <div class="flex-1">
                    <h2 class="font-semibold
                        @if($profile->verification_status === 'APPROVED')
                            text-green-800
                        @elseif($profile->verification_status === 'REJECTED')
                            text-red-800
                        @else
                            text-yellow-800
                        @endif
                    ">
                        @if($profile->verification_status === 'APPROVED')
                            Profil approuvé
                        @elseif($profile->verification_status === 'REJECTED')
                            Profil rejeté
                        @else
                            Vérification en cours
                        @endif
                    </h2>

                    <p class="mt-1 text-sm
                        @if($profile->verification_status === 'APPROVED')
                            text-green-700
                        @elseif($profile->verification_status === 'REJECTED')
                            text-red-700
                        @else
                            text-yellow-700
                        @endif
                    ">
                        @if($profile->verification_status === 'APPROVED')
                            Votre profil professionnel a été approuvé. Vous pouvez maintenant publier vos produits.
                        @elseif($profile->verification_status === 'REJECTED')
                            Votre profil nécessite des modifications avant de pouvoir être approuvé.
                        @else
                            Votre profil est actuellement en cours de vérification par un administrateur.
                        @endif
                    </p>

                    @if($profile->verification_status === 'REJECTED' && $profile->rejection_reason)
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

        {{-- Personal information --}}
        <div class="mb-8">
            <h2 class="mb-4 text-lg font-semibold text-gray-900">
                Informations personnelles
            </h2>

            <div class="grid gap-4 sm:grid-cols-2">
                <div class="rounded-xl border border-gray-200 bg-white/50 p-4">
                    <p class="text-xs font-medium text-gray-500">
                        Numéro CNI
                    </p>
                    <p class="mt-1 font-medium text-gray-900">
                        {{ $profile->cni }}
                    </p>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white/50 p-4">
                    <p class="text-xs font-medium text-gray-500">
                        Statut
                    </p>
                    <p class="mt-1 font-medium text-gray-900">
                        @if($profile->verification_status === 'APPROVED')
                            Approuvé
                        @elseif($profile->verification_status === 'REJECTED')
                            Rejeté
                        @else
                            En attente
                        @endif
                    </p>
                </div>
            </div>
        </div>

        {{-- Producer information --}}
        @if(auth()->user()->role === \App\Enums\UserRole::Producer)

            <div class="mb-8 border-t border-gray-200 pt-6">
                <h2 class="mb-4 text-lg font-semibold text-gray-900">
                    Informations sur votre exploitation
                </h2>

                <div class="space-y-4">
                    <div class="rounded-xl border border-gray-200 bg-white/50 p-4">
                        <p class="text-xs font-medium text-gray-500">
                            Nom de l'exploitation
                        </p>
                        <p class="mt-1 font-medium text-gray-900">
                            {{ $profile->farm_name }}
                        </p>
                    </div>

                    <div class="rounded-xl border border-gray-200 bg-white/50 p-4">
                        <p class="text-xs font-medium text-gray-500">
                            Type de production
                        </p>
                        <p class="mt-1 font-medium text-gray-900">
                            {{ $profile->production_type }}
                        </p>
                    </div>

                    @if($profile->farm_photo)
                        <div>
                            <p class="mb-2 text-sm font-medium text-gray-700">
                                Photo de l'exploitation
                            </p>

                            <img
                                src="{{ Storage::url($profile->farm_photo) }}"
                                alt="Photo de l'exploitation"
                                class="h-64 w-full rounded-2xl object-cover"
                            >
                        </div>
                    @endif
                </div>
            </div>

        {{-- Distributor information --}}
        @elseif(auth()->user()->role === \App\Enums\UserRole::Distributor)

            <div class="mb-8 border-t border-gray-200 pt-6">
                <h2 class="mb-4 text-lg font-semibold text-gray-900">
                    Informations sur votre entreprise
                </h2>

                <div class="space-y-4">
                    <div class="rounded-xl border border-gray-200 bg-white/50 p-4">
                        <p class="text-xs font-medium text-gray-500">
                            Nom de l'entreprise
                        </p>
                        <p class="mt-1 font-medium text-gray-900">
                            {{ $profile->business_name }}
                        </p>
                    </div>

                    <div class="rounded-xl border border-gray-200 bg-white/50 p-4">
                        <p class="text-xs font-medium text-gray-500">
                            Type d'entreprise
                        </p>
                        <p class="mt-1 font-medium text-gray-900">
                            {{ $profile->business_type }}
                        </p>
                    </div>

                    @if($profile->business_photo)
                        <div>
                            <p class="mb-2 text-sm font-medium text-gray-700">
                                Photo de l'entreprise
                            </p>

                            <img
                                src="{{ Storage::url($profile->business_photo) }}"
                                alt="Photo de l'entreprise"
                                class="h-64 w-full rounded-2xl object-cover"
                            >
                        </div>
                    @endif
                </div>
            </div>

        @endif

        {{-- Action --}}
        @if($profile->verification_status === 'REJECTED')

            <div class="border-t border-gray-200 pt-6">
                <a
                    href="{{ route('business-profile.create') }}"
                    class="btn-primary block w-full rounded-xl py-3.5 text-center font-medium"
                >
                    Modifier mon profil et soumettre à nouveau
                </a>
            </div>

        @elseif($profile->verification_status === 'PENDING')

            <div class="border-t border-gray-200 pt-6">
                <p class="text-center text-sm text-gray-500">
                    Votre profil est en cours de vérification. Vous ne pouvez pas encore le modifier.
                </p>
            </div>

        @elseif($profile->verification_status === 'APPROVED')

            <div class="border-t border-gray-200 pt-6">
                <p class="text-center text-sm text-green-700">
                    Votre profil est approuvé. Vous pouvez maintenant publier vos produits.
                </p>
            </div>

        @endif

    </div>
</div>

</div>
@endsection
