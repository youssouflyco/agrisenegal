<footer class="bg-agri-primary text-white">
    <div class="mx-auto max-w-7xl px-4 py-12 md:px-6">
        <div class="grid gap-8 md:grid-cols-4">
            <div class="md:col-span-2">
                <h3 class="text-2xl font-bold">{{ config('agri.name') }}</h3>
                <p class="mt-3 max-w-md text-agri-light/90">{{ config('agri.tagline') }}</p>
            </div>
            <div>
                <h4 class="font-semibold text-harvest">Liens</h4>
                <ul class="mt-3 space-y-2 text-sm text-white/80">
                    <li><a href="{{ route('catalog.products') }}" class="hover:text-white">Catalogue</a></li>
                    <li><a href="#presentation" class="hover:text-white">Présentation</a></li>
                    <li><a href="#fonctionnalites" class="hover:text-white">Fonctionnalités</a></li>
                    <li><a href="#contact" class="hover:text-white">Contact</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold text-harvest">Contact</h4>
                <ul class="mt-3 space-y-2 text-sm text-white/80">
                    <li>Dakar, Sénégal</li>
                    <li>{{ config('agri.support_email') }}</li>
                    <li>{{ config('agri.support_phone') }}</li>
                </ul>
            </div>
        </div>
        <div class="mt-10 border-t border-white/20 pt-6 text-center text-sm text-white/70">
            &copy; {{ date('Y') }} {{ config('agri.name') }}. Tous droits réservés.
        </div>
    </div>
</footer>
