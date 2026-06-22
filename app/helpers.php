<?php

use App\Helpers\AgriHelper;

if (! function_exists('agri_image')) {
    function agri_image(string $key): string
    {
        return AgriHelper::image($key);
    }
}
