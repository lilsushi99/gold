<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Redirect extends Model
{
    protected $fillable = ['source', 'source_hash', 'destination', 'status_code', 'is_active', 'hits'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public static function hashFor(string $source): string
    {
        return hash('sha256', '/' . trim(strtolower($source), '/'));
    }
}
