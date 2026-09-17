<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = ['slug','title','seo_title','seo_description','is_published','sort_order'];
    protected $casts = ['title'=>'array','seo_title'=>'array','seo_description'=>'array','is_published'=>'boolean'];

    public function sections()
    {
        return $this->hasMany(PageSection::class)->orderBy('sort_order');
    }
}
