<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = ['key', 'group', 'value'];

    protected $casts = [
        'value' => 'array',
    ];
}
