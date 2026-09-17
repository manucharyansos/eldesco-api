<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('eldesco:status', function () {
    $this->info('ELDESCO API is configured.');
})->purpose('Check ELDESCO application bootstrap');
