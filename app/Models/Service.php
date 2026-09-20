<?php

namespace App\Models;

use App\Support\Media;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'slug',
        'title_hy', 'title_en', 'title_ru',
        'description_hy', 'description_en', 'description_ru',
        'icon', 'image_url', 'order_index'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function getTranslated($lang = 'en')
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->translated('title', $lang),
            'description' => $this->translated('description', $lang),
            'icon' => $this->icon,
            'image' => Media::url($this->image_url),
            'order' => $this->order_index
        ];
    }

    /** Requested language, then Armenian, then English - never an empty string. */
    private function translated(string $field, string $lang): ?string
    {
        foreach ([$lang, 'hy', 'en', 'ru'] as $candidate) {
            $value = $this->{"{$field}_{$candidate}"} ?? null;
            if ($value !== null && $value !== '') {
                return $value;
            }
        }

        return null;
    }
}
