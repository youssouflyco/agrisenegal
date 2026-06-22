<header class="sticky top-0 z-30 flex h-16 items-center justify-between border-b border-soft-gray bg-white/95 px-4 backdrop-blur md:px-6">
    <button @click="sidebarOpen = true" class="rounded-lg p-2 hover:bg-soft-gray lg:hidden">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
    </button>

    <h1 class="text-lg font-semibold text-agri-primary md:text-xl">@yield('page-title', 'Dashboard')</h1>

    <div class="flex items-center gap-4" x-data="{ open: false }">
        <div class="hidden text-right sm:block">
            <p class="text-sm font-semibold">{{ auth()->user()->full_name }}</p>
            <p class="text-xs text-gray-500">Super Administrateur</p>
        </div>
        <img src="{{ auth()->user()->photo_url }}" alt="" class="h-10 w-10 rounded-full border-2 border-agri-light object-cover">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="rounded-lg px-3 py-2 text-sm text-gray-600 hover:bg-soft-gray hover:text-red-600">Déconnexion</button>
        </form>
    </div>
</header>
