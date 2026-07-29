@extends('layouts.super-admin')

@section('title', 'Configuration 2FA')
@section('page-title', 'Double authentification')

@section('content')
<div class="mx-auto max-w-lg rounded-2xl bg-white p-8 shadow-md">
    <p class="text-gray-600">Scannez ce secret dans Google Authenticator ou une application compatible :</p>
    <p class="mt-4 rounded-xl bg-soft-gray p-4 font-mono text-sm break-all">{{ $secret }}</p>
    <p class="mt-2 text-xs text-gray-500">URL OTP : {{ $qrUrl }}</p>

    <form method="POST" action="{{ route('super-admin.two-factor.confirm') }}" class="mt-8 space-y-4" data-auth-form>
        @csrf
        <div data-auth-field>
            <input type="text" name="code" maxlength="6" required placeholder="Code à 6 chiffres"
                   class="w-full rounded-xl border px-4 py-3 text-center text-xl tracking-widest">
        </div>
        <button type="submit" class="btn-primary w-full">Confirmer et activer</button>
    </form>
</div>
@endsection
