@extends($layout)

@section('title', 'Réclamations')
@if($mode === 'admin')
@section('page-title', 'Gestion des réclamations')
@endif

@section('content')
@if($mode === 'client')
<div class="rounded-2xl bg-gradient-to-br from-agri-primary to-agri-light p-8 text-white shadow-lg">
    <h1 class="text-2xl font-bold md:text-3xl">Mes réclamations</h1>
    <p class="mt-2 text-white/90">Signalez un produit, un vendeur ou un problème de service depuis votre espace client.</p>
</div>

<div class="mt-8 grid gap-6 lg:grid-cols-3">
    <div class="kpi-card lg:col-span-2">
        <form method="POST" action="{{ route('claims.store') }}" class="space-y-5">
            @csrf
            <div>
                <label class="mb-1 block text-sm font-medium">Produit concerné</label>
                <select name="product_id" class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
                    <option value="">Réclamation générale</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" @selected((string) request('product_id') === (string) $product->id)>
                            {{ $product->name }} - {{ $product->user?->full_name ?? 'Vendeur' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium">Sujet *</label>
                <input type="text" name="subject" required class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30" placeholder="Ex. Produit endommagé à la livraison">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium">Message *</label>
                <textarea name="message" rows="5" required class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30" placeholder="Expliquez le problème en détail"></textarea>
            </div>
            <button type="submit" class="btn-primary">Envoyer la réclamation</button>
        </form>
    </div>
    <div class="kpi-card">
        <h2 class="text-lg font-bold text-agri-primary">Conseils</h2>
        <div class="mt-4 space-y-3 text-sm text-gray-600">
            <p>• Ajoutez le produit concerné quand c’est possible.</p>
            <p>• Décrivez le problème clairement pour accélérer le traitement.</p>
            <p>• Vous pouvez suivre le statut de chaque réclamation ci-dessous.</p>
        </div>
    </div>
</div>

<div class="mt-8 rounded-2xl bg-white p-6 shadow-md">
    <div class="mb-5 flex items-center justify-between gap-4">
        <h3 class="text-lg font-semibold text-agri-primary">Historique</h3>
        <span class="text-sm text-gray-500">{{ $complaints->total() }} réclamation(s)</span>
    </div>
    <div class="space-y-4">
        @forelse($complaints as $complaint)
            <div class="rounded-2xl border border-soft-gray p-4">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <h4 class="font-semibold text-gray-900">{{ $complaint->subject }}</h4>
                        <p class="mt-1 text-sm text-gray-600">{{ $complaint->message }}</p>
                    </div>
                    <span class="rounded-full px-3 py-1 text-xs font-medium text-gray-700 bg-soft-gray">{{ ucfirst(str_replace('_', ' ', $complaint->status)) }}</span>
                </div>
                <div class="mt-3 text-xs text-gray-500">
                    <p>Produit : {{ $complaint->product?->name ?? 'Réclamation générale' }}</p>
                    <p>Créée le : {{ $complaint->created_at->format('d/m/Y à H:i') }}</p>
                    @if($complaint->resolution_note)
                        <p class="mt-2 rounded-xl bg-harvest/10 px-3 py-2 text-gray-700">Réponse : {{ $complaint->resolution_note }}</p>
                    @endif
                </div>
            </div>
        @empty
            <p class="py-10 text-center text-sm text-gray-500">Aucune réclamation pour le moment.</p>
        @endforelse
    </div>
    <div class="mt-6">{{ $complaints->links() }}</div>
</div>
@else
<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <p class="text-gray-600">Traitez les réclamations envoyées par les clients.</p>
</div>

<div class="rounded-2xl bg-white p-6 shadow-md">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-soft-gray bg-soft-gray/50">
                <tr>
                    <th class="px-4 py-3 font-semibold text-gray-700">Client</th>
                    <th class="px-4 py-3 font-semibold text-gray-700">Produit</th>
                    <th class="px-4 py-3 font-semibold text-gray-700">Sujet</th>
                    <th class="px-4 py-3 font-semibold text-gray-700">Statut</th>
                    <th class="px-4 py-3 font-semibold text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-soft-gray">
                @forelse($complaints as $complaint)
                    <tr class="align-top hover:bg-soft-gray/30">
                        <td class="px-4 py-3 font-medium">{{ $complaint->user?->full_name ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $complaint->product?->name ?? 'Réclamation générale' }}</td>
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-900">{{ $complaint->subject }}</p>
                            <p class="mt-1 text-gray-600">{{ $complaint->message }}</p>
                            @if($complaint->resolution_note)
                                <p class="mt-2 rounded-xl bg-harvest/10 px-3 py-2 text-xs text-gray-700">Réponse : {{ $complaint->resolution_note }}</p>
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ ucfirst(str_replace('_', ' ', $complaint->status)) }}</td>
                        <td class="px-4 py-3">
                            <form method="POST" action="{{ route('super-admin.claims.update', $complaint) }}" class="space-y-2">
                                @csrf
                                <select name="status" class="w-full rounded-xl border border-soft-gray px-3 py-2 text-sm">
                                    <option value="open" @selected($complaint->status === 'open')>Ouverte</option>
                                    <option value="in_progress" @selected($complaint->status === 'in_progress')>En cours</option>
                                    <option value="resolved" @selected($complaint->status === 'resolved')>Résolue</option>
                                </select>
                                <textarea name="resolution_note" rows="3" class="w-full rounded-xl border border-soft-gray px-3 py-2 text-sm" placeholder="Note de traitement ou réponse client">{{ $complaint->resolution_note }}</textarea>
                                <button type="submit" class="btn-primary text-sm">Mettre à jour</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-12 text-center text-gray-500">Aucune réclamation trouvée.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $complaints->links() }}</div>
</div>
@endif
@endsection