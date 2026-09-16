<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Page extends Model
{
    protected $fillable = [
        'slug', 'title', 'seo', 'is_published', 'show_in_nav', 'sort_order',
    ];

    protected $casts = [
        'title' => 'array',
        'seo' => 'array',
        'is_published' => 'boolean',
        'show_in_nav' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class)->orderBy('sort_order');
    }
}
