@extends('layouts.super-admin')

@section('title', 'Modifier administrateur')
@section('page-title', 'Modifier : ' . $admin->full_name)

@section('content')
<div class="mx-auto max-w-2xl rounded-2xl bg-white p-6 shadow-md md:p-8">
    <form method="POST" action="{{ route('super-admin.admins.update', $admin) }}" enctype="multipart/form-data" class="space-y-5">
        @csrf @method('PUT')
        @include('super-admin.admins._form', ['admin' => $admin])
        <div class="flex gap-3 pt-4">
            <button type="submit" class="btn-primary">Enregistrer</button>
            <a href="{{ route('super-admin.admins.index') }}" class="rounded-xl border px-6 py-3 text-gray-600 hover:bg-soft-gray">Annuler</a>
        </div>
    </form>
</div>
@endsection
