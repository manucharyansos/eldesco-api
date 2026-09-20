<?php

namespace App\Support;

class Media
{
    /**
     * Resolve a stored asset path to something the browser can load.
     * - absolute URLs are returned untouched
     * - uploaded files (/storage/...) are served by this API
     * - everything else (/images/...) is a static asset of the web client
     */
    public static function url(?string $path): ?string
    {
        if ($path === null || $path === '') {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        if (str_starts_with($path, '/storage/')) {
            return url($path);
        }

        return $path;
    }
}
