<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageSection extends Model
{
    protected $fillable = [
        'page_id',
        'key',
        'type',
        'content',
        'image_url',
        'gallery',
        'settings',
        'sort_order',
        'enabled',
    ];

    protected $casts = [
        'content' => 'array',
        'gallery' => 'array',
        'settings' => 'array',
        'enabled' => 'boolean',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }
}
