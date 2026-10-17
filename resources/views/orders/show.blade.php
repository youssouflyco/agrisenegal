
@php
    $layout = auth()->user()->role->value === 'super_admin'
        ? 'layouts.super-admin'
        : 'layouts.app';
@endphp

@extends($layout)

@section('title', 'Commande ' . $order->order_number)

@section('content')
@php
    $statusConfig = [
        'PENDING' => [
            'label' => 'En attente',
            'class' => 'bg-amber-100 text-amber-800',
            'icon' => 'clock',
        ],
        'CONFIRMED' => [
            'label' => 'Confirmée',
            'class' => 'bg-blue-100 text-blue-800',
            'icon' => 'check-circle',
        ],
        'PROCESSING' => [
            'label' => 'En préparation',
            'class' => 'bg-indigo-100 text-indigo-800',
            'icon' => 'package',
        ],
        'SHIPPED' => [
            'label' => 'Expédiée',
            'class' => 'bg-purple-100 text-purple-800',
            'icon' => 'truck',
        ],
        'DELIVERED' => [
            'label' => 'Livrée',
            'class' => 'bg-green-100 text-green-800',
            'icon' => 'check-circle',
        ],
        'CANCELLED' => [
            'label' => 'Annulée',
            'class' => 'bg-red-100 text-red-800',
            'icon' => 'x-circle',
        ],
    ];

    $paymentStatusConfig = [
        'PENDING' => ['label' => 'En attente', 'class' => 'bg-amber-100 text-amber-800'],
        'PAID' => ['label' => 'Payé', 'class' => 'bg-green-100 text-green-800'],
        'FAILED' => ['label' => 'Échec du paiement', 'class' => 'bg-red-100 text-red-800'],
        'REFUNDED' => ['label' => 'Remboursé', 'class' => 'bg-gray-100 text-gray-700'],
    ];

    $status = $statusConfig[$order->status] ?? [
        'label' => $order->status,
        'class' => 'bg-gray-100 text-gray-700',
        'icon' => 'info',
    ];

    $paymentStatus = $paymentStatusConfig[$order->payment_status] ?? [
        'label' => $order->payment_status,
        'class' => 'bg-gray-100 text-gray-700',
    ];

    $paymentMethods = [
        'WAVE' => 'Wave',
        'ORANGE_MONEY' => 'Orange Money',
        'CASH_ON_DELIVERY' => 'Paiement à la livraison',
    ];

    $deliveryMethods = [
        'PICKUP' => 'Retrait auprès du vendeur',
        'DELIVERY' => 'Livraison à domicile',
    ];

    $address = $order->delivery_address ?? [];
@endphp

<div class="min-h-screen bg-gray-50 px-4 py-8 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-6xl">

        {{-- Back navigation --}}
        <a href="{{ route('client.orders.index') }}"
           class="mb-6 inline-flex items-center gap-2 text-sm font-semibold text-gray-600 transition hover:text-agri-primary">
            <svg class="h-4 w-4" fill="none" stroke="currentColor"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Retour à mes commandes
        </a>

        {{-- Flash messages --}}
        @if (session('success'))
            <div class="mb-6 flex items-start gap-3 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-800">
                <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none"
                     stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Order header --}}
        <div class="mb-8 overflow-hidden rounded-3xl bg-gradient-to-br from-agri-primary to-agri-light shadow-lg">
            <div class="flex flex-col gap-5 p-6 text-white sm:flex-row sm:items-center sm:justify-between sm:p-8">
                <div>
                    <p class="mb-2 text-sm font-medium text-white/80">
                        Détails de votre commande
                    </p>

                    <h1 class="break-all text-2xl font-extrabold sm:text-3xl">
                        {{ $order->order_number }}
                    </h1>

                    <p class="mt-2 text-sm text-white/80">
                        Commandée le {{ $order->placed_at?->format('d/m/Y à H:i') ?? $order->created_at->format('d/m/Y à H:i') }}
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <span class="inline-flex items-center gap-2 rounded-full bg-gray-100 px-4 py-2 text-sm font-bold {{ $status['class'] }}">
                        <span class="h-2 w-2 rounded-full bg-current"></span>
                        {{ $status['label'] }}
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 items-start gap-6 lg:grid-cols-3">

            {{-- Main column --}}
            <div class="space-y-6 lg:col-span-2">

                {{-- Ordered products --}}
                <section class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
                    <div class="border-b border-gray-100 px-5 py-5 sm:px-6">
                        <h2 class="text-lg font-bold text-gray-900">
                            Articles commandés
                        </h2>
                        <p class="mt-1 text-sm text-gray-500">
                            {{ $order->items->sum('quantity') }}
                            article(s) au total
                        </p>
                    </div>

                    <div class="divide-y divide-gray-100">
                        @forelse ($order->items as $item)
                            <div class="flex gap-4 p-5 sm:p-6">
                                <div class="h-20 w-20 shrink-0 overflow-hidden rounded-xl bg-gray-100 sm:h-24 sm:w-24">
                                        <img
                                            src="{{ $item->image_path }}"
                                            alt="{{ $item->product_name }}"
                                            class="h-full w-full object-cover"
                                            loading="lazy"
                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                        >
                                        <div class="hidden h-full w-full items-center justify-center text-gray-400">
                                            <svg class="h-8 w-8" fill="none"
                                                 stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      stroke-width="1.5"
                                                      d="M4 16l4-4a2 2 0 013 0l4 4m-1-1l1-1a2 2 0 013 0l2 2M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                </div>

                                <div class="flex min-w-0 flex-1 flex-col justify-between gap-3 sm:flex-row sm:items-center">
                                    <div class="min-w-0">
                                        <h3 class="font-bold text-gray-900">
                                            {{ $item->product_name }}
                                        </h3>

                                        @if ($item->seller_name)
                                            <p class="mt-1 text-sm text-gray-500">
                                                Vendu par {{ $item->seller_name }}
                                            </p>
                                        @endif

                                        <p class="mt-2 text-sm text-gray-500">
                                            {{ number_format((float) $item->unit_price, 0, ',', ' ') }} FCFA
                                            <span class="mx-1">×</span>
                                            {{ $item->quantity }}
                                        </p>
                                    </div>

                                    <p class="shrink-0 font-extrabold text-gray-900">
                                        {{ number_format((float) $item->subtotal, 0, ',', ' ') }}
                                        <span class="text-sm font-semibold">FCFA</span>
                                    </p>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center text-sm text-gray-500">
                                Aucun article n'est associé à cette commande.
                            </div>
                        @endforelse
                    </div>
                </section>

                {{-- Delivery details --}}
                <section class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm sm:p-6">
                    <div class="mb-5 flex items-center gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-agri-light/20 text-agri-primary">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="1.8"
                                      d="M3 7h11v10H3zM14 10h4l3 3v4h-7zM7 20a2 2 0 100-4 2 2 0 000 4zm11 0a2 2 0 100-4 2 2 0 000 4z"/>
                            </svg>
                        </div>

                        <div>
                            <h2 class="font-bold text-gray-900">
                                Livraison et réception
                            </h2>
                            <p class="text-sm text-gray-500">
                                Informations de réception de la commande
                            </p>
                        </div>
                    </div>

                    <div class="rounded-xl bg-gray-50 p-4">
                        <p class="text-sm font-semibold text-gray-500">
                            Mode de réception
                        </p>
                        <p class="mt-1 font-bold text-gray-900">
                            {{ $deliveryMethods[$order->delivery_method] ?? $order->delivery_method }}
                        </p>

                        @if ($order->delivery_method === 'DELIVERY')
                            <div class="mt-5 grid gap-4 sm:grid-cols-2">
                                <div>
                                    <p class="text-sm font-semibold text-gray-500">
                                        Destinataire
                                    </p>
                                    <p class="mt-1 font-medium text-gray-900">
                                        {{ $address['recipient'] ?? 'Non renseigné' }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-sm font-semibold text-gray-500">
                                        Téléphone
                                    </p>
                                    <p class="mt-1 font-medium text-gray-900">
                                        {{ $address['phone'] ?? 'Non renseigné' }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-sm font-semibold text-gray-500">
                                        Adresse
                                    </p>
                                    <p class="mt-1 break-words font-medium text-gray-900">
                                        {{ $address['address'] ?? 'Non renseignée' }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-sm font-semibold text-gray-500">
                                        Ville
                                    </p>
                                    <p class="mt-1 font-medium text-gray-900">
                                        {{ $address['city'] ?? 'Non renseignée' }}
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>

                    @if ($order->customer_note)
                        <div class="mt-5">
                            <p class="text-sm font-semibold text-gray-500">
                                Votre note
                            </p>
                            <p class="mt-2 whitespace-pre-line text-sm leading-6 text-gray-700">{{ $order->customer_note }}</p>
                        </div>
                    @endif
                </section>

            </div>

            {{-- Sidebar --}}
            <aside class="space-y-6">

                {{-- Order summary --}}
                <section class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm sm:p-6">
                    <h2 class="text-lg font-bold text-gray-900">
                        Résumé de la commande
                    </h2>

                    <div class="mt-5 space-y-4 text-sm">
                        <div class="flex items-center justify-between gap-4">
                            <span class="text-gray-500">Sous-total</span>
                            <span class="font-semibold text-gray-900">
                                {{ number_format((float) $order->subtotal, 0, ',', ' ') }} FCFA
                            </span>
                        </div>

                        <div class="flex items-center justify-between gap-4">
                            <span class="text-gray-500">Frais de livraison</span>
                            <span class="font-semibold text-gray-900">
                                {{ (float) $order->delivery_fee > 0
                                    ? number_format((float) $order->delivery_fee, 0, ',', ' ') . ' FCFA'
                                    : '0 FCFA' }}
                            </span>
                        </div>

                        <div class="border-t border-dashed border-gray-200 pt-4">
                            <div class="flex items-start justify-between gap-4">
                                <span class="font-bold text-gray-900">Total</span>
                                <span class="text-right text-xl font-extrabold text-agri-primary">
                                    {{ number_format((float) $order->total_amount, 0, ',', ' ') }}
                                    <span class="text-sm">FCFA</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Payment details --}}
                <section class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm sm:p-6">
                    <h2 class="text-lg font-bold text-gray-900">
                        Paiement
                    </h2>

                    <div class="mt-4 space-y-4">
                        <div>
                            <p class="text-sm text-gray-500">Mode de paiement</p>
                            <p class="mt-1 font-semibold text-gray-900">
                                {{ $paymentMethods[$order->payment_method] ?? $order->payment_method ?? 'Non renseigné' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">Statut du paiement</p>
                            <span class="mt-2 inline-flex rounded-full px-3 py-1.5 text-xs font-bold {{ $paymentStatus['class'] }}">
                                {{ $paymentStatus['label'] }}
                            </span>
                        </div>

                        @if ($order->payment_reference)
                            <div>
                                <p class="text-sm text-gray-500">Référence de paiement</p>
                                <p class="mt-1 break-all font-medium text-gray-900">
                                    {{ $order->payment_reference }}
                                </p>
                            </div>
                        @endif
                    </div>

     @if ($order->payment_status === 'PENDING')
     <div class="mt-5 space-y-4">
        <div class="rounded-xl border border-amber-200 bg-amber-50 p-3">
            <p class="text-sm leading-5 text-amber-800">
                Votre paiement est en attente de confirmation.
            </p>
        </div>

        <form action="{{ route('client.orders.pay', $order->id) }}" method="POST">
            @csrf

            <button
                type="submit"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-agri-primary px-5 py-3 font-bold text-white shadow-sm transition hover:bg-agri-primary/90 focus:outline-none focus:ring-4 focus:ring-agri-light/40"
                onclick="return confirm('Confirmer le paiement de cette commande ?')"
            >
                <svg class="h-5 w-5" fill="none" stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          stroke-width="2"
                          d="M3 10h18M7 15h2m4 0h2M5 6h14a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2z"/>
                </svg>
                Payer
            </button>
        </form>
    </div>
@endif
                </section>

                {{-- Order timeline --}}
                <section class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm sm:p-6">
                    <h2 class="text-lg font-bold text-gray-900">
                        Suivi de la commande
                    </h2>

                    <div class="mt-5 space-y-5">
                        <div class="flex gap-3">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-green-100 text-green-700">
                                <svg class="h-5 w-5" fill="none"
                                     stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">Commande passée</p>
                                <p class="mt-1 text-xs text-gray-500">
                                    {{ $order->placed_at?->format('d/m/Y à H:i') ?? $order->created_at->format('d/m/Y à H:i') }}
                                </p>
                            </div>
                        </div>

                        @if ($order->confirmed_at)
                            <div class="flex gap-3">
                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-blue-100 text-blue-700">
                                    <svg class="h-5 w-5" fill="none"
                                         stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">Commande confirmée</p>
                                    <p class="mt-1 text-xs text-gray-500">
                                        {{ $order->confirmed_at->format('d/m/Y à H:i') }}
                                    </p>
                                </div>
                            </div>
                        @endif

                        @if ($order->delivered_at)
                            <div class="flex gap-3">
                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-green-100 text-green-700">
                                    <svg class="h-5 w-5" fill="none"
                                         stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">Commande livrée</p>
                                    <p class="mt-1 text-xs text-gray-500">
                                        {{ $order->delivered_at->format('d/m/Y à H:i') }}
                                    </p>
                                </div>
                            </div>
                        @endif

                        @if ($order->cancelled_at)
                            <div class="flex gap-3">
                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-700">
                                    <svg class="h-5 w-5" fill="none"
                                         stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">Commande annulée</p>
                                    <p class="mt-1 text-xs text-gray-500">
                                        {{ $order->cancelled_at->format('d/m/Y à H:i') }}
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                </section>
                {{-- Seller Order Actions --}}
@if (
    in_array(auth()->user()->role->value, ['producer', 'distributor']) &&
    $order->payment_status === 'PAID' &&
    in_array($order->status, ['CONFIRMED', 'PROCESSING', 'SHIPPED'])
)
    @php
        $nextStatus = match ($order->status) {
            'CONFIRMED' => 'PROCESSING',
            'PROCESSING' => 'SHIPPED',
            'SHIPPED' => 'DELIVERED',
        };

        $buttonLabels = [
            'PROCESSING' => 'Commencer la préparation',
            'SHIPPED' => 'Marquer comme expédiée',
            'DELIVERED' => 'Confirmer la livraison',
        ];
    @endphp

    <section class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm sm:p-6">
        <h2 class="text-lg font-bold text-gray-900">
            Gestion de la commande
        </h2>

        <p class="mt-2 text-sm text-gray-500">
            Le paiement est confirmé. Vous pouvez maintenant faire avancer
            le traitement de cette commande.
        </p>

        <form
            action="{{ route('sales.orders.update-status', $order->id) }}"
            method="POST"
            class="mt-5"
        >
            @csrf
            @method('PATCH')

            <input type="hidden" name="status" value="{{ $nextStatus }}">

            <button
                type="submit"
                onclick="return confirm('Confirmer cette mise à jour du statut ?')"
                class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-agri-primary px-5 py-3 font-bold text-white transition hover:bg-agri-primary/90 focus:outline-none focus:ring-4 focus:ring-agri-light/40"
            >
                {{ $buttonLabels[$nextStatus] }}

                <svg class="h-5 w-5" fill="none" stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </button>
        </form>
    </section>
@endif
            </aside>
        </div>
    </div>
</div>
@endsection
