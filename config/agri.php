<?php

return [
    'name' => env('APP_NAME', 'AgriSénégal'),
    'tagline' => 'La marketplace agricole du Sénégal rural moderne',
    'support_email' => env('AGRI_SUPPORT_EMAIL', 'youssouf@agrisenegal.sn'),
    'support_phone' => env('AGRI_SUPPORT_PHONE', '+221 77 551 12 59'),

    /*
    | Images libres de droits (Unsplash) — remplacez par vos fichiers dans public/images/agri/
    | Voir public/images/agri/README.md
    */
    'images' => [
        'hero' => 'images/agri/hero.jpg',
        'login_bg' => 'images/agri/login-bg.jpg',
        'presentation' => 'images/agri/presentation.jpg',
        'producer' => 'images/agri/producer.jpg',
        'distributor' => 'images/agri/distributor.jpg',
        'harvest' => 'images/agri/harvest.jpg',
        'fields' => 'images/agri/fields.jpg',
        'testimonial' => 'images/agri/testimonial.jpg',
    ],

    'unsplash_fallbacks' => [
        'hero' => 'https://images.unsplash.com/photo-1574943320219-553eb213f72d?w=1920&q=80',
        'login_bg' => 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=1920&q=80',
        'presentation' => 'https://images.unsplash.com/photo-1625246333195-78d9c38ad449?w=800&q=80',
        'producer' => 'https://images.unsplash.com/photo-1592982537447-6e54fdad0ded?w=800&q=80',
        'distributor' => 'https://images.unsplash.com/photo-1464226184884-fa80b87dee72?w=800&q=80',
        'harvest' => 'https://images.unsplash.com/photo-1595278069441-2cf29f0105a1?w=800&q=80',
        'fields' => 'https://images.unsplash.com/photo-1500937386664-56d1dfef3854?w=800&q=80',
        'testimonial' => 'https://images.unsplash.com/photo-1559839734-2b71ea197ec2?w=400&q=80',
    ],
];
