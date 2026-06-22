<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>@yield('title', 'Dashboard') — {{ config('agri.name') }}</title>
    @stack('head')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-off-white min-h-screen">
    <header class="border-b border-soft-gray bg-white shadow-sm">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 md:px-6">
            <a href="{{ auth()->user()->homeUrl() }}" class="flex items-center gap-2">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-agri-primary font-bold text-white">A</span>
                <span class="font-bold text-agri-primary">{{ config('agri.name') }}</span>
            </a>
            <div class="flex items-center gap-4">
                <div class="hidden text-right sm:block">
                    <p class="text-sm font-semibold">{{ auth()->user()->full_name }}</p>
                    <p class="text-xs text-gray-500">{{ auth()->user()->role->label() }}</p>
                </div>
                <img src="{{ auth()->user()->photo_url }}" alt="" class="h-10 w-10 rounded-full border-2 border-agri-light object-cover">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="rounded-lg px-3 py-2 text-sm text-gray-600 hover:bg-soft-gray hover:text-red-600">Déconnexion</button>
                </form>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-8 md:px-6">
        @include('partials.flash')
        @yield('content')
    </main>
    @include('partials.prevent-back-cache')
    @stack('scripts')
</body>
</html>
