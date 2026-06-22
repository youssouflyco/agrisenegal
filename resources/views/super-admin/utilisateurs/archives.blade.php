@extends('layouts.super-admin')

@section('title', 'Utilisateurs archivés')
@section('page-title', 'Utilisateurs archivés')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <p class="text-gray-600">Historique des utilisateurs archivés.</p>
    <a href="{{ route('super-admin.users.index') }}" class="text-sm font-semibold text-agri-primary hover:underline">Retour à la liste principale</a>
</div>

<div class="rounded-2xl bg-white p-6 shadow-md">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-soft-gray bg-soft-gray/50">
                <tr>
                    <th class="px-4 py-3 font-semibold text-gray-700">Nom</th>
                    <th class="px-4 py-3 font-semibold text-gray-700">Email</th>
                    <th class="px-4 py-3 font-semibold text-gray-700">Rôle</th>
                    <th class="px-4 py-3 font-semibold text-gray-700">Date archivage</th>
                    <th class="px-4 py-3 font-semibold text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-soft-gray">
                @forelse($users as $user)
                    <tr class="hover:bg-soft-gray/30">
                        <td class="px-4 py-3 font-medium">{{ $user->name }}</td>
                        <td class="px-4 py-3">{{ $user->email }}</td>
                        <td class="px-4 py-3">{{ $user->role->label() }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ optional($user->archived_at)->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3">
                            <form method="POST" action="{{ route('super-admin.users.restaurer', $user->id) }}">
                                @csrf
                                <button type="submit" class="text-xs font-semibold text-emerald-600 hover:underline">Restaurer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-12 text-center text-gray-500">Aucun utilisateur archivé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $users->links() }}</div>
</div>
@endsection
