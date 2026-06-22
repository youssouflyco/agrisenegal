@extends('layouts.super-admin')

@section('title', 'Notifications')
@section('page-title', 'Notifications')

@section('content')
<div class="grid gap-4 sm:grid-cols-3">
    <x-kpi-card label="Réclamations ouvertes" :value="number_format($pendingComplaints)" icon="⚠️" />
    <x-kpi-card label="Retraits en attente" :value="number_format($pendingWithdrawals)" icon="🏦" variant="earth" />
    <x-kpi-card label="Activités récentes" :value="number_format($activities->total())" icon="🔔" variant="harvest" />
</div>

<div class="mt-8 rounded-2xl bg-white p-6 shadow-md">
    <h2 class="text-lg font-bold text-agri-primary">Flux d'activité</h2>
    <div class="mt-4 space-y-3">
        @forelse($activities as $activity)
            <div class="rounded-2xl border border-soft-gray p-4">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <p class="font-semibold text-gray-900">{{ $activity->action }}</p>
                        <p class="mt-1 text-sm text-gray-600">{{ $activity->target_label }}</p>
                        @if($activity->reason)
                            <p class="mt-2 text-sm text-gray-500">Motif : {{ $activity->reason }}</p>
                        @endif
                    </div>
                    <div class="text-right text-xs text-gray-500">
                        <p>{{ $activity->created_at->format('d/m/Y') }}</p>
                        <p>{{ $activity->created_at->format('H:i') }}</p>
                    </div>
                </div>
            </div>
        @empty
            <p class="py-10 text-center text-sm text-gray-500">Aucune notification pour le moment.</p>
        @endforelse
    </div>
    <div class="mt-6">{{ $activities->links() }}</div>
</div>
@endsection