<?php

return [
    'default' => env('FILESYSTEM_DISK', 'local'),
    'disks' => [
        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'serve' => true,
            'throw' => false,
        ],
        'public' => [
            'driver' => 'local',
            // On shared hosting (no symlinks) set PUBLIC_DISK_ROOT=public/storage: files are then written
            // straight into the web root and `php artisan storage:link` is not needed.
            'root' => env('PUBLIC_DISK_ROOT') ? base_path(env('PUBLIC_DISK_ROOT')) : storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
            'throw' => false,
        ],
    ],
    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],
];
