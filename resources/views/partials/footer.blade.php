<footer class="bg-agri-primary text-white">
    <div class="mx-auto max-w-7xl px-4 py-12 md:px-6">
        <div class="grid gap-8 md:grid-cols-4">
            <div class="md:col-span-2">
                <h3 class="text-2xl font-bold">{{ config('agri.name') }}</h3>
                <p class="mt-3 max-w-md text-white/80">{{ config('agri.tagline') }}</p>
            </div>
            <div>
                <h4 class="font-semibold text-harvest">Liens</h4>
                <ul class="mt-3 space-y-2 text-sm text-white/80">
                    <li><a href="#presentation" class="hover:text-white">Présentation</a></li>
                    <li><a href="{{ route('catalog.products') }}" class="hover:text-white">Produits</a></li>
                    <li><a href="#producteurs" class="hover:text-white">Producteurs</a></li>
                    <li><a href="#distributeurs" class="hover:text-white">Distributeurs</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold text-harvest">Contacts</h4>
                <ul class="mt-3 space-y-2 text-sm text-white/80">
                    <li>Dakar, Sénégal</li>
                    <li>{{ config('agri.support_email') }}</li>
                    <li>{{ config('agri.support_phone') }}</li>
                   <li><a href="{{ route('contact') }}" class="hover:text-white">Contactez-nous</a></li>
                </ul>
            </div>
        </div>
        <div class="mt-10 border-t border-white/20 pt-6 text-center text-sm text-white/70">
            &copy; {{ date('Y') }} {{ config('agri.name') }}. Tous droits réservés.
        </div>
    </div>
</footer>
