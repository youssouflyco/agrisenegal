@extends('layouts.dashboard')

@section('title', 'Business Profile')

@section('content')
<div class="relative flex min-h-screen items-center justify-center p-4 py-12">
    <div class="relative z-10 w-full max-w-2xl">
        <div class="glass-card rounded-3xl p-8 md:p-10">

            <div class="mb-8 text-center">
                <h1 class="text-2xl font-bold text-agri-primary">
                    Profil professionnel
                </h1>

                <p class="mt-2 text-sm text-gray-600">
                    Complétez vos informations afin de soumettre votre demande de vérification.
                </p>
            </div>

            @include('partials.flash')

            <form
                method="POST"
                action="{{ route('business-profile.store') }}"
                class="space-y-6"
                enctype="multipart/form-data"
            >
                @csrf

                {{-- CNI --}}
                <div>
                    <h2 class="mb-4 text-lg font-semibold text-gray-900">
                        Informations personnelles
                    </h2>

                    <div class="space-y-4">
                        <div>
                            <label for="cni" class="mb-1 block text-sm font-medium text-gray-700">
                                Numéro CNI *
                            </label>

                            <input
                                id="cni"
                                type="text"
                                name="cni"
                                value="{{ old('cni') }}"
                                required
                                placeholder="Numéro de votre carte d'identité"
                                class="w-full rounded-xl border border-black-200 px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30"
                            >

                            @error('cni')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">

                            <div>
                                <label for="cni_front_photo" class="mb-1 block text-sm font-medium text-gray-700">
                                    CNI - Recto *
                                </label>

                                <input
                                    id="cni_front_photo"
                                    type="file"
                                    name="cni_front_photo"
                                    accept="image/jpeg,image/png,image/webp"
                                    required
                                    class="w-full rounded-xl border border-black-200 bg-white px-4 py-3 text-sm focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30"
                                >

                                <p class="mt-1 text-xs text-gray-500">
                                    JPG, PNG ou WEBP — 5 Mo maximum
                                </p>

                                @error('cni_front_photo')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="cni_back_photo" class="mb-1 block text-sm font-medium text-gray-700">
                                    CNI - Verso *
                                </label>

                                <input
                                    id="cni_back_photo"
                                    type="file"
                                    name="cni_back_photo"
                                    accept="image/jpeg,image/png,image/webp"
                                    required
                                    class="w-full rounded-xl border border-black-200 bg-white px-4 py-3 text-sm focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30"
                                >

                                <p class="mt-1 text-xs text-gray-500">
                                    JPG, PNG ou WEBP — 5 Mo maximum
                                </p>

                                @error('cni_back_photo')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Producer information --}}
                @if(auth()->user()->role === \App\Enums\UserRole::Producer)

                    <div class="border-t border-gray-200 pt-6">
                        <h2 class="mb-4 text-lg font-semibold text-gray-900">
                            Informations sur votre exploitation
                        </h2>

                        <div class="space-y-4">

                            <div>
                                <label for="farm_name" class="mb-1 block text-sm font-medium text-gray-700">
                                    Nom de l'exploitation
                                </label>

                                <input
                                    id="farm_name"
                                    type="text"
                                    name="farm_name"
                                    value="{{ old('farm_name') }}"
                                    placeholder="Ex. Ferme de Dakar"
                                    class="w-full rounded-xl border border-black-200 px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30"
                                >

                                @error('farm_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="production_type" class="mb-1 block text-sm font-medium text-gray-700">
                                    Type de production
                                </label>

                                <input
                                    id="production_type"
                                    type="text"
                                    name="production_type"
                                    value="{{ old('production_type') }}"
                                    placeholder="Ex. Maraîchage, céréales, élevage..."
                                    class="w-full rounded-xl border border-black-200 px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30"
                                >

                                @error('production_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="farm_photo" class="mb-1 block text-sm font-medium text-gray-700">
                                    Photo de l'exploitation
                                </label>

                                <input
                                    id="farm_photo"
                                    type="file"
                                    name="farm_photo"
                                    accept="image/jpeg,image/png,image/webp"
                                    class="w-full rounded-xl border border-black-200 bg-white px-4 py-3 text-sm focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30"
                                >

                                <p class="mt-1 text-xs text-gray-500">
                                    JPG, PNG ou WEBP — 5 Mo maximum
                                </p>

                                @error('farm_photo')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>
                    </div>

                {{-- Distributor information --}}
                @elseif(auth()->user()->role === \App\Enums\UserRole::Distributor)

                    <div class="border-t border-gray-200 pt-6">
                        <h2 class="mb-4 text-lg font-semibold text-gray-900">
                            Informations sur votre entreprise
                        </h2>

                        <div class="space-y-4">

                            <div>
                                <label for="business_name" class="mb-1 block text-sm font-medium text-gray-700">
                                    Nom de l'entreprise
                                </label>

                                <input
                                    id="business_name"
                                    type="text"
                                    name="business_name"
                                    value="{{ old('business_name') }}"
                                    placeholder="Ex. Agro Distribution Sénégal"
                                    class="w-full rounded-xl border border-black-200 px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30"
                                >

                                @error('business_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="business_type" class="mb-1 block text-sm font-medium text-gray-700">
                                    Type d'entreprise
                                </label>

                                <input
                                    id="business_type"
                                    type="text"
                                    name="business_type"
                                    value="{{ old('business_type') }}"
                                    placeholder="Ex. Grossiste, détaillant, distributeur..."
                                    class="w-full rounded-xl border border-black-200 px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30"
                                >

                                @error('business_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="business_photo" class="mb-1 block text-sm font-medium text-gray-700">
                                    Photo de l'entreprise
                                </label>

                                <input
                                    id="business_photo"
                                    type="file"
                                    name="business_photo"
                                    accept="image/jpeg,image/png,image/webp"
                                    class="w-full rounded-xl border border-black-200 bg-white px-4 py-3 text-sm focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30"
                                >

                                <p class="mt-1 text-xs text-gray-500">
                                    JPG, PNG ou WEBP — 5 Mo maximum
                                </p>

                                @error('business_photo')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>
                    </div>

                @endif

                {{-- Submit --}}
                <div class="border-t border-gray-200 pt-6">
                    <button
                        type="submit"
                        class="btn-primary w-full rounded-xl py-3.5 font-medium"
                    >
                        Soumettre ma demande
                    </button>

                    <p class="mt-3 text-center text-xs text-gray-500">
                        Votre profil sera vérifié par un administrateur avant que vous puissiez
                        publier des produits.
                    </p>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
