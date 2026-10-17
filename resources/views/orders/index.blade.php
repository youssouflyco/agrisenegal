@php
    $layout = auth()->user()->role->value === 'super_admin'
        ? 'layouts.super-admin'
        : 'layouts.app';
@endphp

@extends($layout)



@section('title', 'Commandes')

@section('content')
@php
    $statusConfig = [
        'PENDING' => ['label' => 'En attente', 'class' => 'bg-yellow-100 text-yellow-800'],
        'CONFIRMED' => ['label' => 'Confirmée', 'class' => 'bg-blue-100 text-blue-800'],
        'PROCESSING' => ['label' => 'En préparation', 'class' => 'bg-indigo-100 text-indigo-800'],
        'SHIPPED' => ['label' => 'Expédiée', 'class' => 'bg-purple-100 text-purple-800'],
        'DELIVERED' => ['label' => 'Livrée', 'class' => 'bg-green-100 text-green-800'],
        'CANCELLED' => ['label' => 'Annulée', 'class' => 'bg-red-100 text-red-800'],
    ];

    $paymentStatusConfig = [
        'PENDING' => ['label' => 'En attente', 'class' => 'bg-yellow-100 text-yellow-800'],
        'PAID' => ['label' => 'Payé', 'class' => 'bg-green-100 text-green-800'],
        'FAILED' => ['label' => 'Échec du paiement', 'class' => 'bg-red-100 text-red-800'],
        'REFUNDED' => ['label' => 'Remboursé', 'class' => 'bg-gray-100 text-gray-700'],
    ];

    $paymentMethods = [
        'WAVE' => 'Wave',
        'ORANGE_MONEY' => 'Orange Money',
        'CASH_ON_DELIVERY' => 'Paiement à la livraison',
    ];

    $deliveryMethods = [
        'PICKUP' => 'Retrait',
        'DELIVERY' => 'Livraison',
    ];

    $isClient = Auth::user()->isClient();
@endphp

<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                {{ $isClient ? 'Mes commandes' : 'Commandes' }}
            </h1>
            <p class="mt-1 text-sm text-gray-600">
                {{ $isClient
                    ? 'Consultez vos commandes et suivez leur évolution.'
                    : 'Consultez les commandes et suivez leur traitement.' }}
            </p>
        </div>

        @if($isClient)
            <a href="{{ route('products.index') }}" class="btn-primary">
                Découvrir les produits
            </a>
        @endif
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">
            {{ session('error') }}
        </div>
    @endif

    {{-- Orders table --}}
    <div class="rounded-2xl bg-white p-6 shadow-md">
        <div class="mb-5 flex flex-wrap items-center justify-between gap-4">
            <h2 class="text-lg font-semibold text-agri-primary">
                Historique des commandes
            </h2>

            <span class="text-sm text-gray-500">
                {{ $orders->total() }} commande(s)
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-soft-gray bg-soft-gray/50">
                    <tr>
                        @if(!$isClient)
                            <th class="px-4 py-3 font-semibold text-gray-700">Client</th>
                        @endif
                        <th class="px-4 py-3 font-semibold text-gray-700">Commande</th>
                        <th class="px-4 py-3 font-semibold text-gray-700">Montant</th>
                        <th class="px-4 py-3 font-semibold text-gray-700">Statut</th>
                        <th class="px-4 py-3 font-semibold text-gray-700">Paiement</th>
                        <th class="px-4 py-3 font-semibold text-gray-700">Méthode de paiement</th>
                        <th class="px-4 py-3 font-semibold text-gray-700">Date</th>
                        <th class="px-4 py-3 font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-soft-gray">
                    @forelse($orders as $order)
                        @php
                            $status = $statusConfig[$order->status] ?? [
                                'label' => ucfirst(strtolower($order->status)),
                                'class' => 'bg-gray-100 text-gray-700',
                            ];

                            $paymentStatus = $paymentStatusConfig[$order->payment_status] ?? [
                                'label' => ucfirst(strtolower($order->payment_status)),
                                'class' => 'bg-gray-100 text-gray-700',
                            ];
                        @endphp

                        <tr class="align-top transition hover:bg-soft-gray/30">
                            @if(!$isClient)
                                <td class="px-4 py-3 font-medium text-gray-900">
                                    {{ $order->user?->full_name ?? '—' }}
                                </td>
                            @endif

                            <td class="px-4 py-3">
                                <p class="font-semibold text-gray-900">
                                    {{ $order->order_number }}
                                </p>
                                <p class="mt-1 text-xs text-gray-500">
                                    {{ $order->items_count ?? $order->items()->count() }} article(s)
                                </p>
                            </td>

                            <td class="px-4 py-3 font-medium whitespace-nowrap">
                                {{ number_format((float) $order->total_amount, 0, ',', ' ') }} FCFA
                            </td>

                            <td class="px-4 py-3">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $status['class'] }}">
                                    {{ $status['label'] }}
                                </span>
                            </td>

                            <td class="px-4 py-3">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $paymentStatus['class'] }}">
                                    {{ $paymentStatus['label'] }}
                                </span>
                            </td>
                               <td class="px-4 py-3">
                                     <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $paymentStatus['class'] }}">
                                    {{ $paymentMethods[$order->payment_method] ?? $order->payment_method ?? 'Non renseigné' }}
                                </span>

                            </td>

                            <td class="px-4 py-3 whitespace-nowrap text-gray-500">
                                {{ ($order->placed_at ?? $order->created_at)?->format('d/m/Y H:i') ?? '—' }}
                            </td>

                            <td class="px-4 py-3">
                                <a
                                    href="{{ $isClient
                                        ? route('client.orders.show', $order->id)
                                        : route('sales.orders.show', $order->id) }}"
                                    title="Voir les détails"
                                    aria-label="Voir les détails de la commande"
                                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-gray-500 transition hover:bg-agri-light/30 hover:text-agri-primary"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M2.25 12s3.75-7.5 9.75-7.5 9.75 7.5 9.75 7.5-3.75 7.5-9.75 7.5S2.25 12 2.25 12Z"
                                        />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $isClient ? 6 : 7 }}" class="px-4 py-12 text-center text-gray-500">
                                Aucune commande trouvée.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection
