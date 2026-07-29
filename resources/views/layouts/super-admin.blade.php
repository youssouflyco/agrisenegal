<!DOCTYPE html>
<html lang="fr" x-data="{ sidebarOpen: false }">
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
<body class="relative min-h-screen overflow-x-hidden bg-soft-gray/50">
    <div class="pointer-events-none fixed inset-0 -z-20 bg-cover bg-fixed bg-center" style="background-image: url('{{ agri_image('fields') }}');"></div>
    <div class="pointer-events-none fixed inset-0 -z-10 bg-[rgba(34,85,34,0.55)]"></div>

    <div class="relative z-10 flex min-h-screen">
        @include('super-admin.partials.sidebar')

        <div class="flex flex-1 flex-col lg:ml-72">
            @include('super-admin.partials.header')

            <main class="flex-1 p-4 md:p-6 lg:p-8">
                @include('partials.flash')
                @yield('content')
            </main>
        </div>
    </div>

    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
         class="fixed inset-0 z-40 bg-black/50 lg:hidden"></div>
    @include('partials.prevent-back-cache')
    @stack('scripts')
</body>
</html>
