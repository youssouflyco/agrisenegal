# AgriSénégal — Marketplace agricole

Plateforme de commerce agricole pour le Sénégal (Laravel 12, Blade, Tailwind CSS 4, Alpine.js, Chart.js).

## Prérequis

- PHP 8.2+
- Composer
- Node.js 18+
- SQLite (par défaut) ou MySQL

## Installation

```bash
cd agri-senegal
composer install
npm install
cp .env.example .env   # si nécessaire
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
npm run build
php artisan serve
```

Ouvrir http://127.0.0.1:8000

## Compte Super Administrateur (unique par défaut)

| Champ | Valeur |
|-------|--------|
| Email | `superadmin@agrisenegal.sn` |
| Mot de passe | `AgriSenegal2026!` |

Les administrateurs sont créés exclusivement par le Super Admin via **Administrateurs**.

## Fonctionnalités (v1)

- Page d'accueil moderne (hero, sections, témoignages, contact)
- Connexion premium (glassmorphism, mot de passe oublié, 2FA)
- Dashboard Super Admin (KPIs, graphiques Chart.js)
- Gestion complète des administrateurs (CRUD, suspension, archivage, reset MDP, activités)
- Journal d'audit (filtres, recherche, export Excel/PDF)
- Sidebar responsive avec tous les menus (modules futurs en placeholder)

## Images

Placer vos photos dans `public/images/agri/` — voir le README du dossier.

## Identité visuelle

- Vert agricole `#2E7D32`
- Vert clair `#66BB6A`
- Jaune récolte `#F9A825`
- Orange terre `#EF6C00`
- Police Poppins

## Licence

MIT
