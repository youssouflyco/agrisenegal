@extends('layouts.super-admin')

@section('title', 'Commandes')
@section('page-title', 'Commandes et ventes')

@section('content')
<div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
    <x-kpi-card label="Commandes" :value="number_format($totals['orders'])" icon="📦" />
    <x-kpi-card label="Chiffre d'affaires" :value="number_format($totals['amount'], 0, ',', ' ') . ' FCFA'" icon="💰" variant="harvest" />
    <x-kpi-card label="Périodes enregistrées" :value="number_format($records->total())" icon="📆" variant="earth" />
</div>

<div class="mt-8 rounded-2xl bg-white p-6 shadow-md">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-soft-gray bg-soft-gray/50">
                <tr>
                    <th class="px-4 py-3 font-semibold text-gray-700">Date</th>
                    <th class="px-4 py-3 font-semibold text-gray-700">Commandes</th>
                    <th class="px-4 py-3 font-semibold text-gray-700">Montant</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-soft-gray">
                @forelse($records as $record)
                    <tr class="hover:bg-soft-gray/30">
                        <td class="px-4 py-3 font-medium">{{ $record->sale_date->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">{{ number_format($record->orders_count) }}</td>
                        <td class="px-4 py-3">{{ number_format($record->amount, 0, ',', ' ') }} FCFA</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-12 text-center text-gray-500">Aucune commande enregistrée.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $records->links() }}</div>
</div>
@endsection