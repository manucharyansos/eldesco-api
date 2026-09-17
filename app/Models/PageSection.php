<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageSection extends Model
{
    protected $fillable = ['page_id','type','key','content','settings','is_enabled','sort_order'];
    protected $casts = ['content'=>'array','settings'=>'array','is_enabled'=>'boolean'];

    public function page() { return $this->belongsTo(Page::class); }
}
