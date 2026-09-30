<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileUploader
{
    public function uploadFile(UploadedFile $file, string $directory = 'agrisen'): string
    {
        $path = Storage::disk('s3')->putFile(
            $directory,
            $file,
            'public'
        );

        return Storage::disk('s3')->url($path);
    }
}
