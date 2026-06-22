<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('agri.name')) — Marketplace agricole</title>
    <style>[x-cloak]{display:none !important;}</style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-off-white">
    @include('partials.navbar')
    <main>@yield('content')</main>
    @include('partials.footer')
</body>
</html>
