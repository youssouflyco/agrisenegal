<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>@yield('title', 'Connexion') — {{ config('agri.name') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>[x-cloak]{display:none !important;}</style>
    @stack('styles')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="relative min-h-screen overflow-x-hidden bg-off-white" data-page-enter>
    <div class="pointer-events-none fixed inset-0 -z-20 bg-cover bg-fixed bg-center" style="background-image: url('{{ agri_image('fields') }}');"></div>
    <div class="pointer-events-none fixed inset-0 -z-10 bg-[rgba(34,85,34,0.55)]"></div>

    <div class="relative z-10">
        @yield('content')
        @include('partials.prevent-back-cache')
    </div>

    @stack('scripts')
</body>
</html>
