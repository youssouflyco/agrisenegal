@extends('layouts.app')

@section('content')
   {{-- Contact --}}
    <section id="contact" class="relative flex min-h-screen items-center justify-center py-20" data-reveal>
        @include('partials.flash')
        <div class="glass-card max-w-5xl rounded-3xl p-10 md:p-16 lg:p-20">
             <div class="mb-6">
                <h1 class="text-2xl font-bold text-agri-primary">Contactez-nous</h1>
                <p class="mt-4 text-gray-600">Notre équipe vous répond sous 24h.</p>
            </div>

            <form class="mt-10 space-y-4 text-left" action="{{ route('contact.submit') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid gap-4 md:grid-cols-2">
                    <input type="text" placeholder="Nom complet" required class="w-full rounded-xl border border-soft-black px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
                    <input type="email" placeholder="Email" required class="w-full rounded-xl border border-soft-black px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
                </div>
                <textarea rows="4" placeholder="Votre message" required class="w-full rounded-xl border border-soft-black px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30"></textarea>
                <button type="submit" class="btn-primary w-full md:w-auto">Envoyer</button>
            </form>
        </div>
    </section>
@endsection
