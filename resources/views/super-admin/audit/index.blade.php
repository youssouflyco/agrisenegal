@extends('layouts.super-admin')

@section('title', 'Audit')
@section('page-title', 'Journal d\'audit')

@section('content')
<div class="rounded-2xl bg-white p-6 shadow-md">
    <x-data-table-toolbar export-route="super-admin.audit.export" search-placeholder="Action, cible, motif...">
        <x-slot:filters>
            <select name="admin_id" class="rounded-xl border border-soft-gray px-3 py-2.5 text-sm">
                <option value="">Tous les admins</option>
                @foreach($admins as $a)
                    <option value="{{ $a->id }}" @selected(request('admin_id') == $a->id)>{{ $a->full_name }}</option>
                @endforeach
            </select>
            <select name="action" class="rounded-xl border border-soft-gray px-3 py-2.5 text-sm">
                <option value="">Toutes les actions</option>
                @foreach($actions as $act)
                    <option value="{{ $act }}" @selected(request('action') === $act)>{{ str_replace('_', ' ', ucfirst($act)) }}</option>
                @endforeach
            </select>
            <input type="date" name="from" value="{{ request('from') }}" class="rounded-xl border border-soft-gray px-3 py-2.5 text-sm">
            <input type="date" name="to" value="{{ request('to') }}" class="rounded-xl border border-soft-gray px-3 py-2.5 text-sm">
        </x-slot:filters>
    </x-data-table-toolbar>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="border-b bg-soft-gray/50">
                <tr>
                    <th class="px-4 py-3 font-semibold">Date</th>
                    <th class="px-4 py-3 font-semibold">Heure</th>
                    <th class="px-4 py-3 font-semibold">Administrateur</th>
                    <th class="px-4 py-3 font-semibold">Action</th>
                    <th class="px-4 py-3 font-semibold">Cible</th>
                    <th class="px-4 py-3 font-semibold">Motif</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($logs as $log)
                    <tr class="hover:bg-soft-gray/30">
                        <td class="px-4 py-3">{{ $log->created_at->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">{{ $log->created_at->format('H:i') }}</td>
                        <td class="px-4 py-3 font-medium">{{ $log->admin?->full_name ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-lg bg-agri-light/20 px-2 py-1 text-xs font-medium text-agri-primary">
                                {{ str_replace('_', ' ', ucfirst($log->action)) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">{{ $log->target_label }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $log->reason ?? '—' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-12 text-center text-gray-500">Aucune entrée trouvée.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $logs->links() }}</div>
</div>
@endsection
