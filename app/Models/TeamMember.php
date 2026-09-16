<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    protected $fillable = [
        'name_hy', 'name_en', 'name_ru',
        'position_hy', 'position_en', 'position_ru',
        'image_url', 'email', 'order_index'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function getTranslated($lang = 'en')
    {
        return [
            'id' => $this->id,
            'name' => $this->{"name_$lang"} ?? $this->name_en,
            'position' => $this->{"position_$lang"} ?? $this->position_en,
            'image' => $this->image_url,
            'email' => $this->email,
            'order' => $this->order_index
        ];
    }
}
