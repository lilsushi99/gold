<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Project extends Model
{
    protected $fillable = ['title', 'slug', 'client', 'category', 'year', 'role', 'summary', 'content', 'cover_media_id', 'gallery', 'project_url', 'position', 'is_featured', 'status'];

    protected function casts(): array
    {
        return ['gallery' => 'array', 'is_featured' => 'boolean'];
    }

    public function cover(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'cover_media_id');
    }
}
