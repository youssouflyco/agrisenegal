@extends('layouts.super-admin')

@section('title', 'Utilisateurs')
@section('page-title', 'Gestion des utilisateurs')

@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <p class="text-gray-600">Liste des utilisateurs actifs et bloqués.</p>
    <a href="{{ route('super-admin.users.create') }}" class="btn-primary text-sm">Ajouter un utilisateur</a>
</div>

<div class="rounded-2xl bg-white p-6 shadow-md">
    <div class="mb-5 flex items-center justify-between">
        <h3 class="text-lg font-semibold text-agri-primary">Utilisateurs non archivés</h3>
        <a href="{{ route('super-admin.users.archives') }}" class="text-sm font-semibold text-agri-primary hover:underline">Voir les archives</a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-soft-gray bg-soft-gray/50">
                <tr>
                    <th class="px-4 py-3 font-semibold text-gray-700">Nom</th>
                    <th class="px-4 py-3 font-semibold text-gray-700">Email</th>
                    <th class="px-4 py-3 font-semibold text-gray-700">Rôle</th>
                    <th class="px-4 py-3 font-semibold text-gray-700">Statut</th>
                    <th class="px-4 py-3 font-semibold text-gray-700">Date création</th>
                    <th class="px-4 py-3 font-semibold text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-soft-gray">
                @forelse($users as $user)
                    <tr class="hover:bg-soft-gray/30">
                        <td class="px-4 py-3 font-medium">{{ $user->name }}</td>
                        <td class="px-4 py-3">{{ $user->email }}</td>
                        <td class="px-4 py-3">{{ $user->role->label() }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-3 py-1 text-xs font-medium {{ $user->status->badgeClass() }}">
                                {{ $user->status->label() }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-500">{{ $user->created_at->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap items-center gap-2">
                                @if($user->status === \App\Enums\UserStatus::Active)
                                    <a href="{{ route('super-admin.users.edit', $user) }}" class="text-xs text-gray-700 hover:underline">Modifier</a>
                                    <form method="POST" action="{{ route('super-admin.users.bloquer', $user->id) }}">
                                        @csrf
                                        <button type="submit" class="text-xs text-orange-600 hover:underline">Bloquer</button>
                                    </form>
                                    <form method="POST" action="{{ route('super-admin.users.archiver', $user->id) }}">
                                        @csrf
                                        <button type="submit" class="text-xs text-gray-600 hover:underline">Archiver</button>
                                    </form>
                                @elseif($user->status === \App\Enums\UserStatus::Suspended)
                                    <form method="POST" action="{{ route('super-admin.users.activer', $user->id) }}">
                                        @csrf
                                        <button type="submit" class="text-xs text-emerald-600 hover:underline">Activer</button>
                                    </form>
                                    <form method="POST" action="{{ route('super-admin.users.archiver', $user->id) }}">
                                        @csrf
                                        <button type="submit" class="text-xs text-gray-600 hover:underline">Archiver</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center text-gray-500">Aucun utilisateur trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $users->links() }}</div>
</div>
@endsection
