<div class="rounded-2xl bg-gradient-to-br from-agri-primary to-agri-light p-8 text-white shadow-lg">
    <h1 class="text-2xl font-bold md:text-3xl">{{ $title }}</h1>
    <p class="mt-2 text-white/90">Chaque produit peut pointer vers un champ, une boutique ou un dépôt.</p>
</div>

<div class="mt-8 grid gap-6 lg:grid-cols-3">
    <div class="kpi-card lg:col-span-2">
        <form method="POST" action="{{ $action }}" class="space-y-5">
            @csrf
            @if(($method ?? 'POST') !== 'POST')
                @method($method)
            @endif

            <div class="grid gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="mb-1 block text-sm font-medium">Nom *</label>
                    <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" required class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
                </div>
                <div class="sm:col-span-2">
                    <label class="mb-1 block text-sm font-medium">Description</label>
                    <textarea name="description" rows="4" class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">{{ old('description', $product->description ?? '') }}</textarea>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">Unité *</label>
                    <input type="text" name="unit" value="{{ old('unit', $product->unit ?? 'kg') }}" required class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">Prix</label>
                    <input type="number" name="price" step="0.01" min="0" value="{{ old('price', $product->price ?? '') }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">Quantité *</label>
                    <input type="number" name="quantity" min="0" value="{{ old('quantity', $product->quantity ?? 0) }}" required class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">Localisation</label>
                    <select name="user_location_id" class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
                        <option value="">Aucune</option>
                        @foreach($locations as $location)
                            <option value="{{ $location->id }}" @selected((string) old('user_location_id', $product->user_location_id ?? '') === (string) $location->id)>
                                {{ $location->label }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active ?? true)) class="rounded border-gray-300 text-agri-primary focus:ring-agri-primary">
                Produit actif
            </label>

            <div class="flex flex-wrap gap-3">
                <button type="submit" class="btn-primary">{{ $buttonLabel }}</button>
                <a href="{{ route('products.index') }}" class="btn-secondary">Retour à la liste</a>
            </div>
        </form>
    </div>

    <div class="kpi-card">
        <h2 class="text-lg font-bold text-agri-primary">Conseils</h2>
        <div class="mt-4 space-y-3 text-sm text-gray-600">
            <p>• Rattachez le produit à la localisation la plus pertinente.</p>
            <p>• Utilisez une unité simple : kg, botte, sac, caisse.</p>
            <p>• Un produit actif peut apparaître dans la carte et les pages vendeurs.</p>
        </div>
    </div>
</div>