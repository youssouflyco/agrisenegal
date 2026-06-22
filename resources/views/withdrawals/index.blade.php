@extends($layout)

@section('title', 'Retraits')
@if($mode === 'admin')
@section('page-title', 'Gestion des retraits')
@endif

@section('content')
@if($mode === 'user')
<div class="rounded-2xl bg-gradient-to-br from-agri-primary to-agri-light p-8 text-white shadow-lg">
    <h1 class="text-2xl font-bold md:text-3xl">Mes retraits</h1>
    <p class="mt-2 text-white/90">Envoyez une demande de retrait et suivez son traitement.</p>
</div>

<div class="mt-8 grid gap-6 lg:grid-cols-3">
    <div class="kpi-card lg:col-span-2">
        <form method="POST" action="{{ route('withdrawals.store') }}" class="space-y-5">
            @csrf
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm font-medium">Montant *</label>
                    <input type="number" name="amount" min="1" step="0.01" required class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30" placeholder="50000">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">Méthode *</label>
                    <select name="method" class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
                        <option value="wave">Wave</option>
                        <option value="orange_money">Orange Money</option>
                        <option value="virement">Virement bancaire</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium">Note</label>
                <textarea name="note" rows="4" class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30" placeholder="Précisions utiles pour le traitement"></textarea>
            </div>
            <button type="submit" class="btn-primary">Envoyer la demande</button>
        </form>
    </div>
    <div class="kpi-card">
        <h2 class="text-lg font-bold text-agri-primary">Règles</h2>
        <div class="mt-4 space-y-3 text-sm text-gray-600">
            <p>• Une demande part en attente de validation.</p>
            <p>• Le statut passe à approuvé ou rejeté côté super-admin.</p>
            <p>• Gardez un compte exact du montant demandé.</p>
        </div>
    </div>
</div>

<div class="mt-8 rounded-2xl bg-white p-6 shadow-md">
    <div class="mb-5 flex items-center justify-between gap-4">
        <h3 class="text-lg font-semibold text-agri-primary">Historique</h3>
        <span class="text-sm text-gray-500">{{ $requests->total() }} demande(s)</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-soft-gray bg-soft-gray/50">
                <tr>
                    <th class="px-4 py-3 font-semibold text-gray-700">Montant</th>
                    <th class="px-4 py-3 font-semibold text-gray-700">Méthode</th>
                    <th class="px-4 py-3 font-semibold text-gray-700">Statut</th>
                    <th class="px-4 py-3 font-semibold text-gray-700">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-soft-gray">
                @forelse($requests as $requestItem)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ number_format($requestItem->amount, 0, ',', ' ') }} FCFA</td>
                        <td class="px-4 py-3">{{ ucfirst(str_replace('_', ' ', $requestItem->method)) }}</td>
                        <td class="px-4 py-3">{{ ucfirst($requestItem->status) }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $requestItem->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-12 text-center text-gray-500">Aucune demande de retrait.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $requests->links() }}</div>
</div>
@else
<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <div>
        <p class="text-gray-600">Validez ou rejetez les demandes de retrait envoyées par les vendeurs.</p>
    </div>
</div>

<div class="rounded-2xl bg-white p-6 shadow-md">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-soft-gray bg-soft-gray/50">
                <tr>
                    <th class="px-4 py-3 font-semibold text-gray-700">Utilisateur</th>
                    <th class="px-4 py-3 font-semibold text-gray-700">Montant</th>
                    <th class="px-4 py-3 font-semibold text-gray-700">Méthode</th>
                    <th class="px-4 py-3 font-semibold text-gray-700">Statut</th>
                    <th class="px-4 py-3 font-semibold text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-soft-gray">
                @forelse($requests as $requestItem)
                    <tr class="align-top hover:bg-soft-gray/30">
                        <td class="px-4 py-3 font-medium">{{ $requestItem->user?->full_name ?? '—' }}</td>
                        <td class="px-4 py-3">{{ number_format($requestItem->amount, 0, ',', ' ') }} FCFA</td>
                        <td class="px-4 py-3">{{ ucfirst(str_replace('_', ' ', $requestItem->method)) }}</td>
                        <td class="px-4 py-3">{{ ucfirst($requestItem->status) }}</td>
                        <td class="px-4 py-3">
                            <form method="POST" action="{{ route('super-admin.withdrawals.update', $requestItem) }}" class="space-y-2">
                                @csrf
                                <select name="status" class="w-full rounded-xl border border-soft-gray px-3 py-2 text-sm">
                                    <option value="pending" @selected($requestItem->status === 'pending')>En attente</option>
                                    <option value="approved" @selected($requestItem->status === 'approved')>Approuvé</option>
                                    <option value="rejected" @selected($requestItem->status === 'rejected')>Rejeté</option>
                                </select>
                                <textarea name="response_note" rows="3" class="w-full rounded-xl border border-soft-gray px-3 py-2 text-sm" placeholder="Note de traitement">{{ $requestItem->response_note }}</textarea>
                                <button type="submit" class="btn-primary text-sm">Mettre à jour</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-12 text-center text-gray-500">Aucune demande de retrait.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $requests->links() }}</div>
</div>
@endif
@endsection