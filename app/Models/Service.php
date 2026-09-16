<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'title_hy', 'title_en', 'title_ru',
        'description_hy', 'description_en', 'description_ru',
        'icon', 'order_index'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function getTranslated($lang = 'en')
    {
        return [
            'id' => $this->id,
            'title' => $this->{"title_$lang"} ?? $this->title_en,
            'description' => $this->{"description_$lang"} ?? $this->description_en,
            'icon' => $this->icon,
            'order' => $this->order_index
        ];
    }
}
