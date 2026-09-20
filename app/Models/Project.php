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
            'image' => $this->publicImageUrl(),
            'thumbnail' => $this->publicAssetUrl($this->thumbnail_url),
            'category' => $this->category,
            'featured' => $this->featured,
            'createdAt' => $this->created_at
        ];
    }

    private function publicImageUrl(): ?string
    {
        if (! $this->image_url) {
            return $this->demoImage();
        }

        return $this->publicAssetUrl($this->image_url);
    }

    private function publicAssetUrl(?string $path): ?string
    {
        if (! $path) {
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

    private function demoImage(): ?string
    {
        return match ((int) $this->order_index) {
            1 => '/images/deck/power/switchboard-copper-3200kw.webp',
            2 => '/images/deck/industrial/pumping-station.webp',
            3 => '/images/deck/refrigeration/compressor-rack.webp',
            4 => '/images/deck/led/concert-hall-screen.webp',
            5 => '/images/deck/metalworks/laser-cutting.webp',
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
