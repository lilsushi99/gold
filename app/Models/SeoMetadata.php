<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class SeoMetadata extends Model
{
    protected $table = 'seo_metadata';

    protected $fillable = ['seoable_type', 'seoable_id', 'title', 'description', 'keywords', 'canonical_url', 'robots_index', 'robots_follow', 'og_title', 'og_description', 'og_image_id', 'og_type', 'twitter_card', 'twitter_title', 'twitter_description', 'twitter_image_id', 'structured_data'];

    protected function casts(): array
    {
        return ['robots_index' => 'boolean', 'robots_follow' => 'boolean', 'structured_data' => 'array'];
    }

    public function ogImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'og_image_id');
    }

    public function twitterImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'twitter_image_id');
    }
}
