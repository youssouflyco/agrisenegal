<?php

namespace App\Helpers;

class AgriHelper
{
    public static function image(string $key): string
    {
        $path = config("agri.images.{$key}");
        $fullPath = public_path($path);

        if (file_exists($fullPath) && filesize($fullPath) > 500) {
            return asset($path);
        }

        return config("agri.unsplash_fallbacks.{$key}", asset('images/agri/placeholders/default.svg'));
    }
}
