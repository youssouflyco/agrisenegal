<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>@yield('title', 'Dashboard') — {{ config('agri.name') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @stack('head')
    @stack('styles')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="relative min-h-screen overflow-x-hidden bg-off-white" data-page-enter>
    <div class="pointer-events-none fixed inset-0 -z-20 bg-cover bg-fixed bg-center" style="background-image: url('{{ agri_image('fields') }}');"></div>
    <div class="pointer-events-none fixed inset-0 -z-10 bg-[rgba(34,85,34,0.55)]"></div>

    <div class="relative z-10">
        <header class="border-b border-white/20 bg-white/92 shadow-sm backdrop-blur-md">
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
    </div>
</body>
</html>
