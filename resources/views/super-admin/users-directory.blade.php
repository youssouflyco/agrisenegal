@extends('layouts.super-admin')

@section('title', $title)
@section('page-title', $title)

@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <p class="text-gray-600">{{ $description }}</p>
</div>

<div class="rounded-2xl bg-white p-6 shadow-md">
    <div class="mb-5 flex items-center justify-between gap-4">
        <h3 class="text-lg font-semibold text-agri-primary">{{ $title }}</h3>
        <span class="text-sm text-gray-500">{{ $users->total() }} résultat(s)</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-soft-gray bg-soft-gray/50">
                <tr>
                    <th class="px-4 py-3 font-semibold text-gray-700">Nom</th>
                    <th class="px-4 py-3 font-semibold text-gray-700">Email</th>
                    <th class="px-4 py-3 font-semibold text-gray-700">Téléphone</th>
                    <th class="px-4 py-3 font-semibold text-gray-700">Rôle</th>
                    <th class="px-4 py-3 font-semibold text-gray-700">Statut</th>
                    <th class="px-4 py-3 font-semibold text-gray-700">Date création</th>
                    <th class="px-4 py-3 font-semibold text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-soft-gray">
                @forelse($users as $user)
                    <tr class="hover:bg-soft-gray/30">
                        <td class="px-4 py-3 font-medium">{{ $user->full_name }}</td>
                        <td class="px-4 py-3">{{ $user->email }}</td>
                        <td class="px-4 py-3">{{ $user->phone ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $user->role->label() }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-3 py-1 text-xs font-medium {{ $user->status->badgeClass() }}">
                                {{ $user->status->label() }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-500">{{ $user->created_at->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap items-center gap-2">
                                <a href="{{ route('super-admin.users.edit', $user) }}" class="text-xs text-gray-700 hover:underline">Modifier</a>
                                @if($user->status === \App\Enums\UserStatus::Active)
                                    <form method="POST" action="{{ route('super-admin.users.bloquer', $user->id) }}">
                                        @csrf
                                        <button type="submit" class="text-xs text-orange-600 hover:underline">Bloquer</button>
                                    </form>
                                @elseif($user->status === \App\Enums\UserStatus::Suspended)
                                    <form method="POST" action="{{ route('super-admin.users.activer', $user->id) }}">
                                        @csrf
                                        <button type="submit" class="text-xs text-emerald-600 hover:underline">Activer</button>
                                    </form>
                                @endif
                                @if($user->status !== \App\Enums\UserStatus::Archived)
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
                        <td colspan="7" class="px-4 py-12 text-center text-gray-500">Aucun utilisateur trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $users->links() }}</div>
</div>
@endsection