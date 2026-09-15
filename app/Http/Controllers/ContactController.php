<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(): View
    {
        return view('contact');
    }

    public function submit(): View
    {
        return view('contact')->withMessage('success', 'Votre message a été envoyé avec succès !');
    }
}


