@extends('layouts.super-admin')

@section('title', $title)
@section('page-title', $title)

@section('content')
<div class="flex min-h-[50vh] flex-col items-center justify-center rounded-2xl bg-white p-12 text-center shadow-md">
    <span class="text-6xl">🌱</span>
    <h2 class="mt-6 text-2xl font-bold text-agri-primary">{{ $title }}</h2>
    <p class="mt-3 max-w-md text-gray-600">Ce module sera disponible dans une prochaine version de la plateforme.</p>

    <a href="{{ route('super-admin.dashboard') }}" class="btn-secondary mt-8 text-sm">Retour au dashboard</a>
</div>
@endsection
