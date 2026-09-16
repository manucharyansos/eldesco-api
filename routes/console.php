<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('eldesco:about', function () {
    $this->info('ELDESCO CMS API');
})->purpose('Display ELDESCO application information');
