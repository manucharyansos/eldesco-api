<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class News extends Model
{
    protected $fillable = [
        'title_hy', 'title_en', 'title_ru',
        'slug_hy', 'slug_en', 'slug_ru',
        'content_hy', 'content_en', 'content_ru',
        'excerpt_hy', 'excerpt_en', 'excerpt_ru',
        'image_url', 'published'
    ];

    protected $casts = [
        'published' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function getTranslated($lang = 'en')
    {
        return [
            'id' => $this->id,
            'title' => $this->{"title_$lang"} ?? $this->title_en,
            'slug' => $this->{"slug_$lang"} ?? $this->slug_en,
            'content' => $this->{"content_$lang"} ?? $this->content_en,
            'excerpt' => $this->{"excerpt_$lang"} ?? $this->excerpt_en,
            'image' => $this->image_url,
            'published' => $this->published,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at
        ];
    }

    public function scopePublished($query)
    {
        return $query->where('published', true);
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            foreach (['hy', 'en', 'ru'] as $lang) {
                if (!$model->{"slug_$lang"} && $model->{"title_$lang"}) {
                    $model->{"slug_$lang"} = Str::slug($model->{"title_$lang"});
                }
            }
        });
    }
}
