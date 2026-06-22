@extends('layouts.super-admin')

@section('title', 'Administrateurs')
@section('page-title', 'Gestion des administrateurs')

@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <p class="text-gray-600">Créez et gérez les comptes administrateurs de la plateforme.</p>
    <a href="{{ route('super-admin.admins.create') }}" class="btn-primary text-sm">+ Nouvel administrateur</a>
</div>

<div class="rounded-2xl bg-white p-6 shadow-md">
    <x-data-table-toolbar export-route="super-admin.admins.export" search-placeholder="Nom, email, téléphone...">
        <x-slot:filters>
            <select name="status" class="rounded-xl border border-soft-gray px-3 py-2.5 text-sm">
                <option value="">Tous les statuts</option>
                @foreach(\App\Enums\UserStatus::cases() as $status)
                    <option value="{{ $status->value }}" @selected(request('status') === $status->value)>{{ $status->label() }}</option>
                @endforeach
            </select>
        </x-slot:filters>
    </x-data-table-toolbar>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-soft-gray bg-soft-gray/50">
                <tr>
                    @foreach(['Photo' => '', 'Prénom' => 'first_name', 'Nom' => 'last_name', 'Email' => 'email', 'Téléphone' => '', 'Statut' => 'status', 'Créé le' => 'created_at', 'Actions' => ''] as $label => $sort)
                        <th class="px-4 py-3 font-semibold text-gray-700">
                            @if($sort)
                                <a href="{{ request()->fullUrlWithQuery(['sort' => $sort, 'dir' => request('dir') === 'asc' ? 'desc' : 'asc']) }}" class="hover:text-agri-primary">
                                    {{ $label }} @if(request('sort') === $sort){{ request('dir') === 'asc' ? '↑' : '↓' }}@endif
                                </a>
                            @else
                                {{ $label }}
                            @endif
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-soft-gray">
                @forelse($admins as $admin)
                    <tr class="hover:bg-soft-gray/30">
                        <td class="px-4 py-3">
                            <img src="{{ $admin->photo_url }}" alt="" class="h-10 w-10 rounded-full object-cover">
                        </td>
                        <td class="px-4 py-3 font-medium">{{ $admin->first_name }}</td>
                        <td class="px-4 py-3">{{ $admin->last_name }}</td>
                        <td class="px-4 py-3">{{ $admin->email }}</td>
                        <td class="px-4 py-3">{{ $admin->phone }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-3 py-1 text-xs font-medium {{ $admin->status->badgeClass() }}">
                                {{ $admin->status->label() }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-500">{{ $admin->created_at->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('super-admin.admins.show', $admin) }}" class="text-agri-primary hover:underline text-xs">Voir</a>
                                <a href="{{ route('super-admin.admins.edit', $admin) }}" class="text-gray-600 hover:underline text-xs">Modifier</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="px-4 py-12 text-center text-gray-500">Aucun administrateur trouvé.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $admins->links() }}</div>
</div>
@endsection
