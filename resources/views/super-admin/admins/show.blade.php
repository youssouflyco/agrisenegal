@extends('layouts.super-admin')

@section('title', $admin->full_name)
@section('page-title', $admin->full_name)

@section('content')
<div class="grid gap-6 lg:grid-cols-3">
    <div class="rounded-2xl bg-white p-6 shadow-md lg:col-span-1">
        <div class="text-center">
            <img src="{{ $admin->photo_url }}" alt="" class="mx-auto h-24 w-24 rounded-full border-4 border-agri-light object-cover">
            <h2 class="mt-4 text-xl font-bold text-agri-primary">{{ $admin->full_name }}</h2>
            <span class="mt-2 inline-block rounded-full px-3 py-1 text-xs font-medium {{ $admin->status->badgeClass() }}">
                {{ $admin->status->label() }}
            </span>
        </div>

        <dl class="mt-6 space-y-3 text-sm">
            <div><dt class="text-gray-500">Email</dt><dd class="font-medium">{{ $admin->email }}</dd></div>
            <div><dt class="text-gray-500">Téléphone</dt><dd class="font-medium">{{ $admin->phone }}</dd></div>
            <div><dt class="text-gray-500">Créé le</dt><dd class="font-medium">{{ $admin->created_at->format('d/m/Y à H:i') }}</dd></div>
        </dl>

        <div class="mt-8 space-y-2">
            <a href="{{ route('super-admin.admins.edit', $admin) }}" class="btn-primary w-full text-center text-sm">Modifier</a>

            <form method="POST" action="{{ route('super-admin.admins.reset-password', $admin) }}" onsubmit="return confirm('Réinitialiser le mot de passe ?')">
                @csrf
                <button type="submit" class="w-full rounded-xl border border-harvest py-2.5 text-sm font-medium text-harvest hover:bg-harvest hover:text-white">Réinitialiser mot de passe</button>
            </form>

            @if($admin->status === \App\Enums\UserStatus::Active)
                <form method="POST" action="{{ route('super-admin.admins.suspend', $admin) }}">
                    @csrf
                    <input type="text" name="reason" placeholder="Motif (optionnel)" class="mb-2 w-full rounded-lg border px-3 py-2 text-sm">
                    <button type="submit" class="w-full rounded-xl border border-orange-400 py-2.5 text-sm text-orange-600 hover:bg-orange-50">Suspendre</button>
                </form>
            @else
                <form method="POST" action="{{ route('super-admin.admins.activate', $admin) }}">
                    @csrf
                    <button type="submit" class="w-full rounded-xl border border-agri-primary py-2.5 text-sm text-agri-primary hover:bg-agri-primary hover:text-white">Réactiver</button>
                </form>
            @endif

            @if($admin->status !== \App\Enums\UserStatus::Archived)
                <form method="POST" action="{{ route('super-admin.admins.archive', $admin) }}" onsubmit="return confirm('Archiver cet administrateur ?')">
                    @csrf
                    <input type="text" name="reason" placeholder="Motif (optionnel)" class="mb-2 w-full rounded-lg border px-3 py-2 text-sm">
                    <button type="submit" class="w-full rounded-xl bg-gray-600 py-2.5 text-sm text-white hover:bg-gray-700">Archiver</button>
                </form>
            @endif
        </div>
    </div>

    <div class="rounded-2xl bg-white p-6 shadow-md lg:col-span-2">
        <h3 class="text-lg font-bold text-agri-primary">Journal d'activité</h3>
        <div class="mt-4 overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b"><tr>
                    <th class="py-2 text-left">Date</th>
                    <th class="py-2 text-left">Action</th>
                    <th class="py-2 text-left">Cible</th>
                    <th class="py-2 text-left">Motif</th>
                </tr></thead>
                <tbody class="divide-y">
                    @forelse($activities as $log)
                        <tr>
                            <td class="py-3 text-gray-500">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                            <td class="py-3">{{ str_replace('_', ' ', ucfirst($log->action)) }}</td>
                            <td class="py-3">{{ $log->target_label }}</td>
                            <td class="py-3 text-gray-500">{{ $log->reason ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="py-8 text-center text-gray-500">Aucune activité enregistrée.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $activities->links() }}</div>
    </div>
</div>
@endsection
