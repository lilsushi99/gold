<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Page extends Model
{
    protected $fillable = ['parent_id', 'title', 'slug', 'template', 'excerpt', 'featured_media_id', 'status', 'publish_at', 'is_home', 'is_system', 'position', 'created_by', 'updated_by'];

    protected function casts(): array
    {
        return ['publish_at' => 'datetime', 'is_home' => 'boolean', 'is_system' => 'boolean'];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Page::class, 'parent_id')->orderBy('position');
    }

    public function sections(): HasMany
    {
        return $this->hasMany(PageSection::class, 'page_id')->orderBy('position');
    }

    public function revisions(): HasMany
    {
        return $this->hasMany(PageRevision::class, 'page_id');
    }

    public function featuredMedia(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'featured_media_id');
    }

    public function seo(): MorphOne
    {
        return $this->morphOne(SeoMetadata::class, 'seoable', 'seoable_type', 'seoable_id');
    }

    public function scopePublic($q)
    {
        return $q->where(function ($q) {
            $q->where('status', 'published')
              ->orWhere(fn ($q) => $q->where('status', 'scheduled')->where('publish_at', '<=', now()));
        });
    }

    public function isPublic(): bool
    {
        return $this->status === 'published'
            || ($this->status === 'scheduled' && $this->publish_at && $this->publish_at->lte(now()));
    }

    public function path(): string
    {
        return $this->is_home ? '/' : '/' . $this->slug;
    }
}
