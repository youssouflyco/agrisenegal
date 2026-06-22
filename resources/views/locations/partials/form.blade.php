<div class="rounded-2xl bg-gradient-to-br from-agri-primary to-agri-light p-8 text-white shadow-lg">
    <h1 class="text-2xl font-bold md:text-3xl">{{ $title }}</h1>
    <p class="mt-2 text-white/90">Renseignez un lieu précis pour améliorer la carte et les recherches de proximité.</p>
</div>

<div class="mt-8 grid gap-6 lg:grid-cols-3">
    <div class="kpi-card lg:col-span-2">
        <form method="POST" action="{{ $action }}" class="space-y-5">
            @csrf
            @if(($method ?? 'POST') !== 'POST')
                @method($method)
            @endif

            @if($errors->any())
                <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    Vérifiez les champs du formulaire et réessayez.
                </div>
            @endif

            <div class="grid gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="mb-1 block text-sm font-medium">Nom du lieu *</label>
                    <input type="text" name="label" value="{{ old('label', $location->label ?? '') }}" required class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
                    @error('label')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">Région</label>
                    <select name="region" class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
                        <option value="">Sélectionner</option>
                        @foreach($regionOptions as $region)
                            <option value="{{ $region }}" @selected(old('region', $location->region ?? '') === $region)>{{ $region }}</option>
                        @endforeach
                    </select>
                    @error('region')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">Type *</label>
                    <select name="kind" required class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
                        <option value="">Sélectionner</option>
                        @foreach($locationTypes as $locationType)
                            <option value="{{ $locationType }}" @selected(old('kind', $location->kind ?? '') === $locationType)>{{ ucfirst($locationType) }}</option>
                        @endforeach
                    </select>
                    @error('kind')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">Latitude *</label>
                    <div class="flex items-center gap-2">
                        <input type="number" name="latitude" step="0.000001" min="-90" max="90" value="{{ old('latitude', $location->latitude ?? '') }}" required class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
                        <button type="button" onclick="fillLocationFromDevice(this)" class="btn-secondary">Utiliser ma position</button>
                    </div>
                    @error('latitude')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">Longitude *</label>
                    <input type="number" name="longitude" step="0.000001" min="-180" max="180" value="{{ old('longitude', $location->longitude ?? '') }}" required class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
                    @error('longitude')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-4 rounded-2xl bg-soft-gray/40 px-4 py-4">
                <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                    <input type="checkbox" name="publicly_visible" value="1" @checked(old('publicly_visible', $location->publicly_visible ?? false)) class="rounded border-gray-300 text-agri-primary focus:ring-agri-primary">
                    Visible publiquement sur la carte
                </label>
                <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                    <input type="checkbox" name="is_primary" value="1" @checked(old('is_primary', $location->is_primary ?? false)) class="rounded border-gray-300 text-agri-primary focus:ring-agri-primary">
                    Définir comme localisation principale
                </label>
            </div>

            <div class="flex flex-wrap gap-3">
                <button type="submit" class="btn-primary">{{ $buttonLabel }}</button>
                <a href="{{ route('locations.index') }}" class="btn-secondary">Retour à la liste</a>
            </div>
        </form>
    </div>

    <div class="kpi-card">
        <h2 class="text-lg font-bold text-agri-primary">Conseils</h2>
        <div class="mt-4 space-y-3 text-sm text-gray-600">
            <p>• Utilisez un nom clair : champ, boutique, maison ou dépôt.</p>
            <p>• Pour les vendeurs, une localisation visible améliore la découverte.</p>
            <p>• Vous pouvez changer la localisation principale à tout moment.</p>
        </div>
    </div>
</div>