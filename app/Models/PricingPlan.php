<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class PricingPlan extends Model
{
    protected $table = 'pricing_plans';

    protected $fillable = ['name', 'price', 'period', 'description', 'features', 'cta_label', 'cta_url', 'is_featured', 'position', 'is_active'];

    protected function casts(): array
    {
        return ['features' => 'array', 'is_featured' => 'boolean', 'is_active' => 'boolean'];
    }
}
