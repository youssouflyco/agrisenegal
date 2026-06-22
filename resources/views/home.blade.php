@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
    {{-- Hero --}}
    <section class="relative flex min-h-[58vh] items-start justify-center pt-16">
        <div class="absolute inset-0 pointer-events-none">
            <img src="{{ agri_image('hero') }}" alt="Cultivateurs sénégalais dans les champs" class="h-full w-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-agri-primary/90 via-agri-primary/70 to-transparent"></div>
        </div>
        <div class="relative z-10 mx-auto max-w-7xl px-4 py-8 md:px-6 md:py-12">
            <div class="max-w-2xl text-white">
                <span class="inline-block rounded-full bg-harvest/90 px-4 py-1 text-sm font-semibold text-agri-primary">🇸🇳 Agriculture sénégalaise</span>
                <h1 class="mt-4 text-3xl font-bold leading-tight md:text-5xl">Connectons le champ au marché</h1>
                <p class="mt-4 text-base text-white/90 md:text-lg">La marketplace qui rapproche producteurs, distributeurs et clients pour une agriculture moderne, prospère et de confiance.</p>
                <div class="mt-4 flex flex-wrap gap-4">
                    @auth
                        <a href="{{ auth()->user()->homeUrl() }}" class="relative z-20 rounded-xl bg-harvest px-8 py-4 font-bold text-agri-primary">Aller au dashboard</a>
                        <form method="POST" action="{{ route('logout') }}" class="relative z-20">
                            @csrf
                            <button type="submit" class="rounded-xl border-2 border-white px-8 py-4 font-bold text-white">Se déconnecter</button>
                        </form>
                    @else
                        <a href="{{ url('/inscription') }}" class="relative z-20 rounded-xl bg-harvest px-8 py-4 font-bold text-agri-primary">S'inscrire</a>
                        <a href="{{ url('/connexion') }}" class="relative z-20 rounded-xl border-2 border-white px-8 py-4 font-bold text-white">Se connecter</a>
                    @endauth
                </div>
            </div>
        </div>
    </section>

    {{-- Présentation --}}
    <section id="presentation" class="py-20">
        <div class="mx-auto grid max-w-7xl items-center gap-12 px-4 md:grid-cols-2 md:px-6">
            <div>
                <h2 class="section-title">Une plateforme au service du rural</h2>
                <p class="mt-6 text-lg text-gray-600 leading-relaxed">
                    {{ config('agri.name') }} facilite la commercialisation des produits agricoles sénégalais en créant un lien direct entre les acteurs de la chaîne de valeur : du maraîchage aux grandes cultures.
                </p>
                <ul class="mt-8 space-y-4">
                    @foreach(['Paiements sécurisés', 'Traçabilité des produits', 'Accompagnement des producteurs'] as $item)
                        <li class="flex items-center gap-3">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-agri-light/30 text-agri-primary">✓</span>
                            <span class="font-medium">{{ $item }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
            <img src="{{ agri_image('presentation') }}" alt="Agriculture au Sénégal" class="rounded-3xl shadow-2xl">
        </div>
    </section>

    {{-- Fonctionnalités --}}
    <section id="fonctionnalites" class="bg-soft-gray py-20">
        <div class="mx-auto max-w-7xl px-4 md:px-6">
            <div class="text-center">
                <h2 class="section-title">Fonctionnalités clés</h2>
                <p class="mt-4 text-gray-600">Tout ce dont vous avez besoin pour digitaliser votre activité agricole</p>
            </div>
            <div class="mt-14 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                @foreach([
                    ['icon' => '🌾', 'title' => 'Catalogue produits', 'desc' => 'Mettez en valeur vos récoltes avec photos et descriptions détaillées.'],
                    ['icon' => '📦', 'title' => 'Commandes en ligne', 'desc' => 'Gérez vos commandes de A à Z avec suivi en temps réel.'],
                    ['icon' => '💳', 'title' => 'Paiements mobiles', 'desc' => 'Orange Money, Wave et virements intégrés.'],
                    ['icon' => '🚚', 'title' => 'Logistique', 'desc' => 'Coordonnez livraisons et points de collecte.'],
                    ['icon' => '📊', 'title' => 'Tableaux de bord', 'desc' => 'Statistiques et indicateurs pour piloter votre activité.'],
                    ['icon' => '🛡️', 'title' => 'Sécurité', 'desc' => 'Vérification des acteurs et gestion des réclamations.'],
                ] as $feature)
                    <div class="rounded-2xl bg-white p-8 shadow-md transition hover:-translate-y-1 hover:shadow-xl">
                        <span class="text-4xl">{{ $feature['icon'] }}</span>
                        <h3 class="mt-4 text-xl font-bold text-agri-primary">{{ $feature['title'] }}</h3>
                        <p class="mt-2 text-gray-600">{{ $feature['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Producteurs --}}
    <section id="producteurs" class="py-20">
        <div class="mx-auto grid max-w-7xl items-center gap-12 px-4 md:grid-cols-2 md:px-6">
            <img src="{{ agri_image('producer') }}" alt="Producteur agricole sénégalais" class="order-2 rounded-3xl shadow-2xl md:order-1">
            <div class="order-1 md:order-2">
                <h2 class="section-title">Pour les producteurs</h2>
                <p class="mt-6 text-lg text-gray-600">Vendez directement vos récoltes, fixez vos prix et développez votre clientèle sans intermédiaires abusifs.</p>
                <a href="{{ route('register.form', 'producteur') }}" class="btn-primary mt-8">Rejoindre la plateforme</a>
            </div>
        </div>
    </section>

    {{-- Distributeurs --}}
    <section id="distributeurs" class="bg-agri-primary/5 py-20">
        <div class="mx-auto grid max-w-7xl items-center gap-12 px-4 md:grid-cols-2 md:px-6">
            <div>
                <h2 class="section-title">Pour les distributeurs</h2>
                <p class="mt-6 text-lg text-gray-600">Accédez à un réseau fiable de producteurs locaux et optimisez votre chaîne d'approvisionnement.</p>
                <a href="{{ route('register.form', 'distributeur') }}" class="btn-secondary mt-8">Devenir distributeur</a>
            </div>
            <img src="{{ agri_image('distributor') }}" alt="Distribution agricole" class="rounded-3xl shadow-2xl">
        </div>
    </section>

    {{-- Statistiques --}}
    <section class="py-20">
        <div class="mx-auto max-w-7xl px-4 md:px-6">
            <div class="rounded-3xl bg-gradient-to-br from-agri-primary to-agri-light p-12 text-white">
                <div class="grid gap-8 text-center sm:grid-cols-2 lg:grid-cols-4">
                    @foreach([['500+', 'Producteurs'], ['1200+', 'Clients'], ['85', 'Distributeurs'], ['15M+', 'FCFA de CA']] as [$num, $label])
                        <div>
                            <p class="text-4xl font-bold text-harvest md:text-5xl">{{ $num }}</p>
                            <p class="mt-2 text-white/90">{{ $label }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- Témoignages --}}
    <section class="bg-soft-gray py-20">
        <div class="mx-auto max-w-7xl px-4 md:px-6">
            <h2 class="section-title text-center">Ils nous font confiance</h2>
            <div class="mt-14 grid gap-8 md:grid-cols-3">
                @foreach([
                    ['name' => 'Amadou Sow', 'role' => 'Producteur, Thiès', 'text' => 'Grâce à cette plateforme, je vends mes légumes directement aux restaurants de Dakar.'],
                    ['name' => 'Fatou Diop', 'role' => 'Distributrice', 'text' => 'La traçabilité et la qualité des producteurs m\'ont convaincue.'],
                    ['name' => 'Moussa Gueye', 'role' => 'Client professionnel', 'text' => 'Commandes simples, livraisons fiables. Un vrai gain de temps.'],
                ] as $t)
                    <div class="rounded-2xl bg-white p-8 shadow-md">
                        <p class="text-gray-600 italic">"{{ $t['text'] }}"</p>
                        <div class="mt-6 flex items-center gap-4">
                            <img src="{{ agri_image('testimonial') }}" alt="" class="h-12 w-12 rounded-full object-cover">
                            <div>
                                <p class="font-bold text-agri-primary">{{ $t['name'] }}</p>
                                <p class="text-sm text-gray-500">{{ $t['role'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Contact --}}
    <section id="contact" class="py-20">
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
