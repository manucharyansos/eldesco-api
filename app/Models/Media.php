<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    protected $table = 'media';

    protected $fillable = [
        'disk', 'path', 'file_name', 'mime_type', 'size', 'alt',
    ];

    protected $appends = ['url'];

    protected $casts = [
        'size' => 'integer',
        'alt' => 'array',
    ];

    public function getUrlAttribute(): string
    {
        return Storage::disk($this->disk)->url($this->path);
    }
}
