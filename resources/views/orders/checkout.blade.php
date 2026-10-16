
@extends('layouts.app')

@section('title', 'Finaliser la commande')

@section('content')
<div class="min-h-screen bg-gray-50/70 py-8 md:py-12">
    <div class="mx-auto max-w-7xl px-4 md:px-6">

        {{-- Page header --}}
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-agri-primary to-agri-light p-6 text-white shadow-lg md:p-10">
            <div class="relative z-10 max-w-2xl">
                <div class="mb-4 inline-flex items-center gap-2 rounded-full bg-white/15 px-3 py-1.5 text-sm font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M5 13l4 4L19 7"/>
                    </svg>
                    Finalisation de votre commande
                </div>

                <h1 class="text-3xl font-extrabold tracking-tight md:text-4xl">
                    Presque terminé !
                </h1>

                <p class="mt-3 text-sm leading-6 text-white/90 md:text-base">
                    Vérifiez vos articles, choisissez votre mode de paiement
                    et indiquez vos informations de livraison.
                </p>
            </div>

            <div class="pointer-events-none absolute -right-10 -top-16 h-64 w-64 rounded-full border-[40px] border-white/10"></div>
            <div class="pointer-events-none absolute -bottom-24 right-32 h-48 w-48 rounded-full bg-white/10 blur-2xl"></div>
        </div>

        {{-- Validation errors --}}
        @if ($errors->any())
            <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 p-5 text-red-800">
                <div class="flex items-start gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-5 w-5 shrink-0"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    </svg>

                    <div>
                        <p class="font-bold">Veuillez vérifier les informations saisies.</p>
                        <ul class="mt-2 list-inside list-disc space-y-1 text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form
            id="checkout-form"
            method="POST"
            action="{{ route('client.orders.store') }}"
            class="mt-8"
        >
            @csrf

            <div class="grid items-start gap-8 lg:grid-cols-3">

                {{-- Main checkout form --}}
                <div class="space-y-8 lg:col-span-2">

                    {{-- 1. Order items --}}
                    <section class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-sm">
                        <div class="flex items-center justify-between border-b border-gray-100 px-5 py-5 md:px-7">
                            <div>
                                <h2 class="text-lg font-bold text-gray-900 md:text-xl">
                                    Vos articles
                                </h2>
                                <p class="mt-1 text-sm text-gray-500">
                                    Vérifiez les produits et les quantités.
                                </p>
                            </div>

                            <span class="rounded-full bg-agri-primary/10 px-3 py-1.5 text-sm font-bold text-agri-primary">
                                {{ $count }} article(s)
                            </span>
                        </div>

                        <div class="divide-y divide-gray-100 px-5 md:px-7">
                            @foreach ($items as $item)
                                @php
                                    $product = $item['product'];
                                @endphp

                                <div class="flex gap-4 py-5">
                                    <div class="h-20 w-20 shrink-0 overflow-hidden rounded-2xl bg-gray-100 sm:h-24 sm:w-24">
                                            <img
                                                src="{{ $product->image_path }}"
                                                alt="{{ $product->name }}"
                                                class="h-full w-full object-cover"
                                            >
                                    </div>

                                    <div class="flex min-w-0 flex-1 flex-col justify-between gap-3 sm:flex-row sm:items-center">
                                        <div class="min-w-0">
                                            <h3 class="font-bold text-gray-900">
                                                {{ $product->name }}
                                            </h3>

                                            <p class="mt-1 text-sm text-gray-500">
                                                Prix unitaire :
                                                {{ number_format($product->price, 0, ',', ' ') }} FCFA
                                            </p>

                                            <p class="mt-1 text-sm text-gray-500">
                                                Quantité : {{ $item['quantity'] }}
                                            </p>
                                        </div>

                                        <p class="shrink-0 font-extrabold text-gray-900 sm:text-right">
                                            Sous-total : {{ number_format($item['line_total'], 0, ',', ' ') }}
                                            <span class="text-xs font-semibold text-gray-500">FCFA</span>
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="border-t border-gray-100 bg-gray-50/70 px-5 py-4 md:px-7">
                            <a
                                href="{{ route('cart.index') }}"
                                class="inline-flex items-center gap-2 text-sm font-semibold text-agri-primary transition hover:underline"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 19l-7-7 7-7"/>
                                </svg>
                                Modifier mon panier
                            </a>
                        </div>
                    </section>

                    {{-- 2. Payment method --}}

{{-- 2. Payment method --}}
<section class="rounded-3xl border border-gray-100 bg-white p-5 shadow-sm md:p-7">
    <div class="flex items-start gap-3">
        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-agri-primary/10 text-agri-primary">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 10h18M7 15h2m4 0h2M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
            </svg>
        </div>

        <div>
            <h2 class="text-lg font-bold text-gray-900 md:text-xl">
                Mode de paiement
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Choisissez comment vous souhaitez payer.
            </p>
        </div>
    </div>

    @php
        $paymentMethods = [
            ['value' => 'WAVE', 'name' => 'Wave'],
            ['value' => 'ORANGE_MONEY', 'name' => 'Orange Money'],
            ['value' => 'CASH_ON_DELIVERY', 'name' => 'Paiement à la livraison'],
        ];
    @endphp

    <div class="mt-5 flex flex-nowrap gap-2 sm:gap-3">
        @foreach ($paymentMethods as $method)
            <label class="payment-option flex min-w-0 flex-1 cursor-pointer items-center justify-center gap-2 rounded-xl border-2 border-gray-200 px-2 py-3 text-center transition hover:border-agri-primary/50 sm:px-4">
                <input
                    type="radio"
                    name="payment_method"
                    value="{{ $method['value'] }}"
                    class="h-4 w-4 shrink-0 accent-agri-primary"
                    required
                    {{ old('payment_method', 'WAVE') === $method['value'] ? 'checked' : '' }}
                >

                <span class="text-xs font-bold leading-5 text-gray-900 sm:text-sm">
                    {{ $method['name'] }}
                </span>
            </label>
        @endforeach
    </div>
</section>


                    {{-- 3. Delivery method --}}
                    <section class="rounded-3xl border border-gray-100 bg-white p-5 shadow-sm md:p-7">
                        <div class="flex items-start gap-3">
                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-agri-primary/10 text-agri-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 7h11v10H3zM14 10h4l3 3v4h-7zM7 20a2 2 0 100-4 2 2 0 000 4zm11 0a2 2 0 100-4 2 2 0 000 4z"/>
                                </svg>
                            </div>

                            <div>
                                <h2 class="text-lg font-bold text-gray-900 md:text-xl">
                                    Mode de réception
                                </h2>
                                <p class="mt-1 text-sm text-gray-500">
                                    Choisissez comment recevoir vos produits.
                                </p>
                            </div>
                        </div>

                        <div class="mt-6 grid gap-4 sm:grid-cols-2">
                            <label class="delivery-option flex cursor-pointer items-start gap-3 rounded-2xl border-2 border-gray-200 p-4 transition hover:border-agri-primary/50">
                                <input
                                    type="radio"
                                    name="delivery_method"
                                    value="PICKUP"
                                    class="mt-1 h-4 w-4 accent-agri-primary"
                                    required
                                    {{ old('delivery_method', 'DELIVERY') === 'PICKUP' ? 'checked' : '' }}
                                >

                                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gray-100 text-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M3 10l9-7 9 7M5 9v11h14V9M9 20v-6h6v6"/>
                                    </svg>
                                </span>

                                <span>
                                    <span class="block font-bold text-gray-900">Retrait sur place</span>
                                    <span class="mt-1 block text-xs leading-5 text-gray-500">
                                        Récupérez votre commande auprès du vendeur.
                                    </span>
                                </span>
                            </label>

                            <label class="delivery-option flex cursor-pointer items-start gap-3 rounded-2xl border-2 border-gray-200 p-4 transition hover:border-agri-primary/50">
                                <input
                                    type="radio"
                                    name="delivery_method"
                                    value="DELIVERY"
                                    class="mt-1 h-4 w-4 accent-agri-primary"
                                    required
                                    {{ old('delivery_method', 'DELIVERY') === 'DELIVERY' ? 'checked' : '' }}
                                >

                                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gray-100 text-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M3 7h11v10H3zM14 10h4l3 3v4h-7zM7 20a2 2 0 100-4 2 2 0 000 4zm11 0a2 2 0 100-4 2 2 0 000 4z"/>
                                    </svg>
                                </span>

                                <span>
                                    <span class="block font-bold text-gray-900">Livraison</span>
                                    <span class="mt-1 block text-xs leading-5 text-gray-500">
                                        Recevez votre commande à l'adresse indiquée.
                                    </span>
                                </span>
                            </label>
                        </div>

                        {{-- Delivery address --}}
                        <div id="delivery-address-section" class="mt-6 rounded-2xl border border-gray-200 bg-gray-50/70 p-4 sm:p-5">
                            <h3 class="font-bold text-gray-900">Adresse de livraison</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Ces informations seront enregistrées avec votre commande.
                            </p>

                            <div class="mt-5 grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label for="recipient" class="mb-2 block text-sm font-semibold text-gray-700">
                                        Nom du destinataire
                                    </label>
                                    <input
                                        id="recipient"
                                        name="delivery_address[recipient]"
                                        type="text"
                                        value="{{ old('delivery_address.recipient', auth()->user()->name) }}"
                                        maxlength="150"
                                        autocomplete="name"
                                        class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-agri-primary focus:ring-4 focus:ring-agri-primary/10"
                                        placeholder="Nom complet"
                                    >
                                    @error('delivery_address.recipient')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="delivery-phone" class="mb-2 block text-sm font-semibold text-gray-700">
                                        Téléphone
                                    </label>
                                    <input
                                        id="delivery-phone"
                                        name="delivery_address[phone]"
                                        type="tel"
                                        value="{{ old('delivery_address.phone', auth()->user()->phone) }}"
                                        maxlength="30"
                                        autocomplete="tel"
                                        class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-agri-primary focus:ring-4 focus:ring-agri-primary/10"
                                        placeholder="Ex. : 77 123 45 67"
                                    >
                                    @error('delivery_address.phone')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="delivery-address" class="mb-2 block text-sm font-semibold text-gray-700">
                                        Adresse complète
                                    </label>
                                    <textarea
                                        id="delivery-address"
                                        name="delivery_address[address]"
                                        rows="2"
                                        maxlength="500"
                                        autocomplete="street-address"
                                        class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-agri-primary focus:ring-4 focus:ring-agri-primary/10"
                                        placeholder="Quartier, rue, repère ou indications utiles"
                                    >{{ old('delivery_address.address') }}</textarea>
                                    @error('delivery_address.address')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="delivery-city" class="mb-2 block text-sm font-semibold text-gray-700">
                                        Ville
                                    </label>
                                    <input
                                        id="delivery-city"
                                        name="delivery_address[city]"
                                        type="text"
                                        value="{{ old('delivery_address.city') }}"
                                        maxlength="100"
                                        autocomplete="address-level2"
                                        class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-agri-primary focus:ring-4 focus:ring-agri-primary/10"
                                        placeholder="Ex. : Dakar, Pikine, Thiès..."
                                    >
                                    @error('delivery_address.city')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Customer note --}}
                        <div class="mt-6">
                            <label for="customer-note" class="mb-2 block text-sm font-semibold text-gray-700">
                                Instructions complémentaires
                                <span class="font-normal text-gray-400">(facultatif)</span>
                            </label>

                            <textarea
                                id="customer-note"
                                name="customer_note"
                                rows="3"
                                maxlength="2000"
                                class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-agri-primary focus:ring-4 focus:ring-agri-primary/10"
                                placeholder="Précisions pour le vendeur ou le livreur..."
                            >{{ old('customer_note') }}</textarea>

                            @error('customer_note')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </section>
                </div>

                {{-- Order summary --}}
                <aside class="lg:sticky lg:top-6">
                    <div class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-sm">
                        <div class="border-b border-gray-100 px-6 py-5">
                            <h2 class="text-xl font-extrabold text-gray-900">
                                Résumé de la commande
                            </h2>
                            <p class="mt-1 text-sm text-gray-500">
                                Le récapitulatif de vos achats.
                            </p>
                        </div>

                        <div class="space-y-4 p-6">
                            <div class="flex items-center justify-between gap-4 text-sm">
                                <span class="text-gray-500">Articles</span>
                                <span class="font-semibold text-gray-900">{{ $count }}</span>
                            </div>

                            <div class="flex items-center justify-between gap-4 text-sm">
                                <span class="text-gray-500">Sous-total</span>
                                <span class="font-semibold text-gray-900">
                                    {{ number_format($subtotal, 0, ',', ' ') }} FCFA
                                </span>
                            </div>

                            <div class="flex items-center justify-between gap-4 text-sm">
                                <span class="text-gray-500">Livraison</span>
                                <span id="delivery-fee-display" class="font-semibold text-gray-900">
                                    {{ ($deliveryFee ?? 0) > 0 ? number_format($deliveryFee, 0, ',', ' ') . ' FCFA' : 'À confirmer' }}
                                </span>
                            </div>

                            <div class="border-t border-dashed border-gray-200 pt-4">
                                <div class="flex items-start justify-between gap-4">
                                    <span class="font-bold text-gray-900">Total estimé</span>
                                    <span class="text-right text-2xl font-extrabold text-agri-primary">
                                        {{ number_format($total, 0, ',', ' ') }}
                                        <span class="text-sm">FCFA</span>
                                    </span>
                                </div>
                                <p class="mt-2 text-xs leading-5 text-gray-500">
                                    Les frais de livraison éventuels doivent être confirmés avant la validation définitive.
                                </p>
                            </div>

                            <button
                                type="submit"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-agri-primary px-5 py-4 font-bold text-white shadow-md transition hover:bg-agri-primary/90 focus:outline-none focus:ring-4 focus:ring-agri-primary/20"
                            >
                                Confirmer la commande

                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M5 12h14m-7-7l7 7-7 7"/>
                                </svg>
                            </button>

                            <a
                                href="{{ route('cart.index') }}"
                                class="inline-flex w-full items-center justify-center rounded-xl border border-gray-200 px-5 py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                            >
                                Retour au panier
                            </a>

                            <div class="flex items-start gap-3 rounded-2xl bg-gray-50 p-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-5 w-5 shrink-0 text-agri-primary"
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 11c0 3-2 5-2 5m2-5c0-3 2-5 2-5m-2 5V3m7 9a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>

                                <p class="text-xs leading-5 text-gray-600">
                                    Vos informations de commande sont utilisées pour traiter vos achats et organiser leur réception.
                                </p>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </form>
    </div>
</div>

{{-- Delivery field visibility and required validation --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('checkout-form');
        const addressSection = document.getElementById('delivery-address-section');
        const deliveryInputs = addressSection.querySelectorAll('input, textarea');
        const deliveryRadios = form.querySelectorAll('input[name="delivery_method"]');

        function updateDeliveryFields() {
            const selected = form.querySelector('input[name="delivery_method"]:checked');
            const requiresDelivery = selected && selected.value === 'DELIVERY';

            addressSection.hidden = !requiresDelivery;

            deliveryInputs.forEach(function (input) {
                input.required = requiresDelivery;
                input.disabled = !requiresDelivery;
            });
        }

        deliveryRadios.forEach(function (radio) {
            radio.addEventListener('change', updateDeliveryFields);
        });

        updateDeliveryFields();

        // Highlight the selected payment and delivery cards.
        function updateSelectedCards() {
            document.querySelectorAll('.payment-option').forEach(function (card) {
                const radio = card.querySelector('input[type="radio"]');
                card.classList.toggle('border-agri-primary', radio.checked);
                card.classList.toggle('bg-agri-primary/5', radio.checked);
                card.classList.toggle('border-gray-200', !radio.checked);
            });

            document.querySelectorAll('.delivery-option').forEach(function (card) {
                const radio = card.querySelector('input[type="radio"]');
                card.classList.toggle('border-agri-primary', radio.checked);
                card.classList.toggle('bg-agri-primary/5', radio.checked);
                card.classList.toggle('border-gray-200', !radio.checked);
            });
        }

        form.querySelectorAll('input[type="radio"]').forEach(function (radio) {
            radio.addEventListener('change', updateSelectedCards);
        });

        updateSelectedCards();
    });
</script>
@endsection
