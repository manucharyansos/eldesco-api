<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'title_hy', 'title_en', 'title_ru',
        'description_hy', 'description_en', 'description_ru',
        'image_url', 'thumbnail_url', 'category', 'featured', 'order_index'
    ];

    protected $casts = [
        'featured' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function getTranslated($lang = 'en')
    {
        return [
            'id' => $this->id,
            'title' => $this->{"title_$lang"} ?? $this->title_en,
            'description' => $this->{"description_$lang"} ?? $this->description_en,
            'image' => $this->image_url ?: $this->demoImage(),
            'thumbnail' => $this->thumbnail_url,
            'category' => $this->category,
            'featured' => $this->featured,
            'createdAt' => $this->created_at
        ];
    }

    private function demoImage(): ?string
    {
        return match ((int) $this->order_index) {
            1 => '/images/projects/substation.png',
            4 => '/images/projects/led.png',
            5 => '/images/projects/metalworks.png',
            default => null,
        };
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}
