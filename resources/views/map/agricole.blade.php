@extends($layout)

@section('title', 'Carte Agricole')
@section('page-title', 'Carte Agricole')

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (!window.L) {
                console.error('Leaflet n\'est pas chargé.');
                return;
            }

            const vendorCards = Array.from(document.querySelectorAll('[data-vendor-card]'));
            const searchInput = document.querySelector('[data-vendor-search]');
            const markers = @json($vendors->values());
            const map = L.map('agri-map').setView([{{ $mapCenter['latitude'] }}, {{ $mapCenter['longitude'] }}], 8);

            const markerGroup = L.markerClusterGroup({
                showCoverageOnHover: false,
                zoomToBoundsOnClick: true,
                spiderfyOnMaxZoom: true,
            });

            const markerEntries = markers.map((vendor) => {
                const marker = L.marker([vendor.coordinates.latitude, vendor.coordinates.longitude], {
                    icon: L.divIcon({
                        className: 'agri-marker',
                        html: `<span class="agri-marker__dot ${vendor.vendor_role === 'producer' ? 'agri-marker__dot--producer' : 'agri-marker__dot--distributor'}"></span>`,
                        iconSize: [16, 16],
                        iconAnchor: [8, 8],
                    }),
                });

                const region = vendor.region ? `<p class="text-xs text-gray-500">Région: ${vendor.region}</p>` : '';
                const distance = vendor.distance_label ? `<p class="text-xs text-gray-500">Distance: ${vendor.distance_label}</p>` : '';
                const products = vendor.product_count ? `<p class="text-xs text-gray-500">Produits actifs: ${vendor.product_count}</p>` : '';

                marker.bindPopup(`
                    <div class="min-w-56 space-y-2 text-sm">
                        <div class="flex items-center gap-3">
                            <img src="${vendor.vendor_photo}" alt="" class="h-10 w-10 rounded-full object-cover">
                            <div>
                                <p class="font-semibold text-agri-primary">${vendor.vendor_name}</p>
                                <p class="text-xs text-gray-500">${vendor.vendor_role_label}</p>
                            </div>
                        </div>
                        <p class="font-medium text-gray-700">${vendor.location_label}</p>
                        <p class="text-xs text-gray-500">Type: ${vendor.kind_label}</p>
                        ${region}
                        ${products}
                        ${distance}
                    </div>
                `);

                return {
                    marker,
                    searchable: [
                        vendor.vendor_name,
                        vendor.vendor_role_label,
                        vendor.kind_label,
                        vendor.location_label,
                        vendor.region,
                        vendor.distance_label,
                    ].filter(Boolean).join(' ').toLowerCase(),
                };
            });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors',
                maxZoom: 18,
            }).addTo(map);

            markerGroup.addTo(map);

            const filterCards = () => {
                const query = (searchInput?.value || '').trim().toLowerCase();

                vendorCards.forEach((card) => {
                    const haystack = `${card.dataset.vendorCard || ''}`.toLowerCase();
                    const match = !query || haystack.includes(query);
                    card.classList.toggle('hidden', !match);
                });

                markerGroup.clearLayers();
                markerEntries.forEach(({ marker, searchable }) => {
                    if (!query || searchable.includes(query)) {
                        markerGroup.addLayer(marker);
                    }
                });
            };

            searchInput?.addEventListener('input', filterCards);
            filterCards();
        });
    </script>
@endpush

@section('content')
<div class="relative overflow-hidden rounded-[2rem] bg-gradient-to-br from-agri-primary via-[#2f8f46] to-[#f6a531] p-8 text-white shadow-2xl">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(255,255,255,0.22),_transparent_35%),radial-gradient(circle_at_bottom_left,_rgba(255,255,255,0.15),_transparent_30%)]"></div>
    <div class="relative flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">
        <div class="max-w-3xl">
            <p class="text-sm font-semibold uppercase tracking-[0.25em] text-white/75">Carte interactive premium</p>
            <h1 class="mt-3 text-3xl font-bold md:text-5xl">Trouver un champ, une boutique ou un point de vente</h1>
            <p class="mt-4 max-w-2xl text-base text-white/90 md:text-lg">Une vue claire pour localiser les producteurs et distributeurs, repérer leurs champs, boutiques et dépôts, puis accéder au point qui vous intéresse en quelques secondes.</p>
        </div>

        <div class="grid gap-3 sm:grid-cols-3 lg:min-w-[28rem]">
            <div class="rounded-2xl border border-white/20 bg-white/12 p-4 backdrop-blur">
                <p class="text-xs uppercase tracking-[0.2em] text-white/70">Points affichés</p>
                <p class="mt-2 text-3xl font-bold">{{ number_format($vendors->count()) }}</p>
            </div>
            <div class="rounded-2xl border border-white/20 bg-white/12 p-4 backdrop-blur">
                <p class="text-xs uppercase tracking-[0.2em] text-white/70">Producteurs</p>
                <p class="mt-2 text-3xl font-bold">{{ number_format($vendors->where('vendor_role', 'producer')->count()) }}</p>
            </div>
            <div class="rounded-2xl border border-white/20 bg-white/12 p-4 backdrop-blur">
                <p class="text-xs uppercase tracking-[0.2em] text-white/70">Distributeurs</p>
                <p class="mt-2 text-3xl font-bold">{{ number_format($vendors->where('vendor_role', 'distributor')->count()) }}</p>
            </div>
        </div>
    </div>
</div>

<div class="mt-8 grid gap-6 xl:grid-cols-[1.7fr_0.95fr]">
    <div class="space-y-6">
        <div class="rounded-[2rem] bg-white p-4 shadow-xl ring-1 ring-black/5">
            <div id="agri-map" class="agri-map"></div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
            @foreach([
                ['label' => 'Tout', 'value' => 'all'],
                ['label' => 'Producteurs', 'value' => 'producers'],
                ['label' => 'Distributeurs', 'value' => 'distributors'],
                ['label' => 'Champs', 'value' => 'fields'],
                ['label' => 'Boutiques', 'value' => 'boutiques'],
            ] as $item)
                <a href="{{ route('agri.map', array_filter(['filter' => $item['value'], 'radius' => $currentRadius])) }}" class="rounded-2xl border p-4 text-center transition {{ $currentFilter === $item['value'] ? 'border-agri-primary bg-agri-primary text-white shadow-lg' : 'border-soft-gray bg-white hover:border-agri-primary/40' }}">
                    <span class="block text-sm font-semibold">{{ $item['label'] }}</span>
                </a>
            @endforeach
        </div>
    </div>

    <div class="space-y-6">
        <div class="kpi-card space-y-4">
            <div>
                <h2 class="text-lg font-bold text-agri-primary">Recherche rapide</h2>
                <p class="mt-2 text-sm text-gray-500">Tapez un nom, une région, un champ ou une boutique.</p>
            </div>

            <div class="relative">
                <input type="search" data-vendor-search placeholder="Rechercher un champ, une boutique, une région..." class="w-full rounded-2xl border border-soft-gray bg-white px-4 py-3 pl-11 text-sm outline-none transition focus:border-agri-primary focus:ring-4 focus:ring-agri-light/20">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">⌕</div>
            </div>

            <div>
                <p class="text-sm font-semibold text-gray-700">Rayon</p>
                <div class="mt-3 flex flex-wrap gap-2">
                    @foreach([5, 10, 20, 50] as $radius)
                        <a href="{{ route('agri.map', array_filter(['filter' => $currentFilter, 'radius' => $radius])) }}" class="rounded-full border px-4 py-2 text-sm {{ (int) $currentRadius === $radius ? 'border-agri-primary bg-agri-primary text-white' : 'border-soft-gray text-gray-600 hover:border-agri-primary/40' }}">&lt; {{ $radius }} km</a>
                    @endforeach
                    <a href="{{ route('agri.map', array_filter(['filter' => $currentFilter])) }}" class="rounded-full border border-soft-gray px-4 py-2 text-sm text-gray-600 hover:border-agri-primary/40">Tous</a>
                </div>
            </div>

            <div class="space-y-3">
                @forelse($vendors->sortBy('distance_km') as $vendor)
                    <div data-vendor-card="{{ strtolower(implode(' ', array_filter([$vendor['vendor_name'], $vendor['location_label'], $vendor['kind_label'], $vendor['region'], $vendor['vendor_role_label'], $vendor['distance_label'] ?? '']))) }}" class="rounded-[1.5rem] border border-soft-gray bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg">
                        <div class="flex items-start gap-3">
                            <img src="{{ $vendor['vendor_photo'] }}" alt="" class="h-12 w-12 rounded-full object-cover">
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $vendor['vendor_name'] }}</p>
                                        <p class="text-xs text-gray-500">{{ $vendor['vendor_role_label'] }} · {{ $vendor['kind_label'] }}</p>
                                    </div>
                                    @if($vendor['distance_label'])
                                        <span class="rounded-full bg-agri-primary/10 px-3 py-1 text-xs font-semibold text-agri-primary">{{ $vendor['distance_label'] }}</span>
                                    @endif
                                </div>
                                <p class="mt-2 text-sm text-gray-600">{{ $vendor['location_label'] }}</p>
                                <p class="text-xs text-gray-500">Produits actifs : {{ number_format($vendor['product_count']) }}</p>
                                @if($vendor['region'])
                                    <p class="text-xs text-gray-500">{{ $vendor['region'] }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="rounded-2xl border border-dashed border-soft-gray p-4 text-sm text-gray-500">Aucun point ne correspond à ce filtre.</p>
                @endforelse
            </div>
        </div>

        <div class="kpi-card">
            <h2 class="text-lg font-bold text-agri-primary">Répartition régionale</h2>
            <div class="mt-4 space-y-3">
                @forelse($regionStats as $region)
                    <div class="flex items-center justify-between rounded-xl bg-soft-gray/60 px-4 py-3 text-sm">
                        <span class="font-medium text-gray-700">{{ $region->region }}</span>
                        <span class="font-semibold text-agri-primary">{{ number_format($region->total) }}</span>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">Aucune statistique régionale disponible.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection