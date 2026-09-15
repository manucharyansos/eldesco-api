<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Service extends Model { protected $guarded=[]; protected function casts(): array { return ['title'=>'array','excerpt'=>'array','body'=>'array','gallery'=>'array','seo'=>'array','is_published'=>'boolean']; } }
