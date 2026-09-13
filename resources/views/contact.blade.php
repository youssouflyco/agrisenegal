@extends('layouts.app')

@section('content')
   {{-- Contact --}}
    <section id="contact" class="py-20" data-reveal>
        <div class="mx-auto max-w-3xl px-4 text-center md:px-6">
            <h2 class="section-title">Contactez-nous</h2>
            <p class="mt-4 text-gray-600">Une question ? Notre équipe vous répond sous 24h.</p>
            <form class="mt-10 space-y-4 text-left" action="#" method="POST">
                @csrf
                <div class="grid gap-4 md:grid-cols-2">
                    <input type="text" placeholder="Nom complet" class="w-full rounded-xl border border-soft-gray px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
                    <input type="email" placeholder="Email" class="w-full rounded-xl border border-soft-gray px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
                </div>
                <textarea rows="4" placeholder="Votre message" class="w-full rounded-xl border border-soft-gray px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30"></textarea>
                <button type="submit" class="btn-primary w-full md:w-auto">Envoyer</button>
            </form>
        </div>
    </section>
@endsection
