<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['group', 'key', 'value', 'type'];

    /** Cached key => value map (invalidated whenever a setting is saved). */
    public static function map(): array
    {
        return Cache::rememberForever('cms.settings', fn () => static::pluck('value', 'key')->all());
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $v = static::map()[$key] ?? null;
        return ($v === null || $v === '') ? $default : $v;
    }

    public static function put(string $key, mixed $value): void
    {
        static::where('key', $key)->update(['value' => $value]);
        Cache::forget('cms.settings');
    }

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('cms.settings'));
        static::deleted(fn () => Cache::forget('cms.settings'));
    }
}
