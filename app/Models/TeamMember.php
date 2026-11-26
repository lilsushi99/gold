<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class TeamMember extends Model
{
    protected $table = 'team_members';

    protected $fillable = ['name', 'slug', 'position_title', 'bio', 'media_id', 'socials', 'position', 'is_active'];

    protected function casts(): array
    {
        return ['socials' => 'array', 'is_active' => 'boolean'];
    }

    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }
}
