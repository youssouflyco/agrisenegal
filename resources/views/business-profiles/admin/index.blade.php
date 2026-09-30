@extends('layouts.super-admin')

@section('title', $title)
@section('page-title', 'Gestion des ' . strtolower($title))

@section('content')

<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <p class="text-gray-600">
        Gérez les profils {{ strtolower($title) }} de la plateforme.
    </p>
</div>

<div class="rounded-2xl bg-white p-6 shadow-md">

<x-data-table-toolbar
    search-placeholder="Nom, email, téléphone..."
>
    <x-slot:filters>
        <select name="status" class="rounded-xl border border-soft-gray px-3 py-2.5 text-sm">
            <option value="">Tous les statuts</option>

            @foreach(\App\Enums\UserStatus::cases() as $status)
                <option
                    value="{{ $status->value }}"
                    @selected(request('status') === $status->value)
                >
                    {{ $status->label() }}
                </option>
            @endforeach
        </select>
    </x-slot:filters>
</x-data-table-toolbar>

<div class="overflow-x-auto">
    <table class="w-full text-left text-sm">

        <thead class="border-b border-soft-gray bg-soft-gray/50">
            <tr>
                @if($businessType === 'PRODUCTEUR')
                    <th class="px-4 py-3 font-semibold text-gray-700">
                        Exploitation
                    </th>

                    <th class="px-4 py-3 font-semibold text-gray-700">
                        Production
                    </th>
                @else
                    <th class="px-4 py-3 font-semibold text-gray-700">
                        Entreprise
                    </th>

                    <th class="px-4 py-3 font-semibold text-gray-700">
                        Type
                    </th>
                @endif

                <th class="px-4 py-3 font-semibold text-gray-700">
                    Statut
                </th>

                <th class="px-4 py-3 font-semibold text-gray-700">
                    Créé le
                </th>

                <th class="px-4 py-3 font-semibold text-gray-700">
                    Actions
                </th>
            </tr>
        </thead>

        <tbody class="divide-y divide-soft-gray">

            @forelse($profiles as $profile)

                <tr class="hover:bg-soft-gray/30">
                    {{-- Business-specific data --}}
                    @if($businessType === 'PRODUCTEUR')

                        <td class="px-4 py-3">
                            {{ $profile->farm_name ?: '—' }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $profile->production_type ?: '—' }}
                        </td>

                    @else

                        <td class="px-4 py-3">
                            {{ $profile->business_name ?: '—' }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $profile->business_type ?: '—' }}
                        </td>

                    @endif

                    {{-- Status --}}
                    <td class="px-4 py-3">
                        <span class="rounded-full px-3 py-1 text-xs font-medium {{ $profile->status }}">
                            {{ $profile->status }}
                        </span>
                    </td>

                    {{-- Date --}}
                    <td class="px-4 py-3 text-gray-500">
                        {{ $profile->created_at->format('d/m/Y') }}
                    </td>

                    {{-- Actions --}}
                    <td class="px-4 py-3">
                        <div class="flex flex-wrap gap-2">

                            <a
                                href="{{ route('super-admin.business-profiles.review', $profile->id) }}"
                                class="text-agri-primary hover:underline text-xs"
                            >
                                Voir
                            </a>
                        </div>
                    </td>

                </tr>

            @empty

                <tr>
                    <td
                        colspan="10"
                        class="px-4 py-12 text-center text-gray-500"
                    >
                        Aucun profil trouvé.
                    </td>
                </tr>

            @endforelse

        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $profiles->links() }}
</div>

</div>

@endsection
