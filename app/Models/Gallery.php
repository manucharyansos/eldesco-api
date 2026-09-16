<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $fillable = [
        'title_hy', 'title_en', 'title_ru',
        'image_url', 'thumbnail_url', 'category', 'order_index'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function getTranslated($lang = 'en')
    {
        return [
            'id' => $this->id,
            'title' => $this->{"title_$lang"} ?? $this->title_en ?? 'Image',
            'image' => $this->image_url,
            'thumbnail' => $this->thumbnail_url,
            'category' => $this->category,
            'order' => $this->order_index
        ];
    }

    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}
