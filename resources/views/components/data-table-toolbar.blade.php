@props(['exportRoute' => null, 'searchPlaceholder' => 'Rechercher...'])

<div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
    <form method="GET" class="flex flex-1 flex-wrap items-end gap-3">
        {{ $filters ?? '' }}
        <div class="min-w-[200px] flex-1">
            <input type="search" name="search" value="{{ request('search') }}" placeholder="{{ $searchPlaceholder }}"
                   class="w-full rounded-xl border border-soft-gray px-4 py-2.5 text-sm focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
        </div>
        <button type="submit" class="rounded-xl bg-agri-primary px-4 py-2.5 text-sm font-medium text-white hover:bg-agri-primary/90">Filtrer</button>
        @if(request()->query())
            <a href="{{ url()->current() }}" class="rounded-xl border px-4 py-2.5 text-sm text-gray-600 hover:bg-soft-gray">Réinitialiser</a>
        @endif
    </form>

    @if($exportRoute)
        <div class="flex gap-2">
            <a href="{{ route($exportRoute, ['format' => 'excel'] + request()->query()) }}"
               class="rounded-xl border border-agri-primary px-4 py-2.5 text-sm font-medium text-agri-primary hover:bg-agri-primary hover:text-white">Excel</a>
            <a href="{{ route($exportRoute, ['format' => 'pdf'] + request()->query()) }}"
               class="rounded-xl bg-earth px-4 py-2.5 text-sm font-medium text-white hover:bg-earth/90">PDF</a>
        </div>
    @endif
</div>
