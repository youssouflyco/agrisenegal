<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('home', [
            'faqs' => [
                ['question' => 'Le visiteur peut-il acheter sans compte ?', 'answer' => 'Oui. Le catalogue est public et le panier peut être conservé avant inscription.'],
                ['question' => 'Comment les vendeurs sont-ils validés ?', 'answer' => 'Les comptes producteurs et distributeurs passent par une validation Super Admin avant publication.'],
                ['question' => 'La plateforme prend-elle en compte la localisation ?', 'answer' => 'Oui. Les vendeurs peuvent renseigner une localisation et la carte agricole est déjà intégrée.'],
            ],
        ]);
    }
}
