<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;

class ActivityLogger
{
    /** Keys that must never be written to the log. */
    private const SENSITIVE = ['password', 'password_confirmation', 'current_password', 'token', 'remember_token', '_token'];

    public static function log(string $action, ?Model $subject = null, ?string $description = null, array $properties = []): void
    {
        $properties = array_diff_key($properties, array_flip(self::SENSITIVE));

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'subject_type' => $subject ? class_basename($subject) : null,
            'subject_id' => $subject?->getKey(),
            'description' => $description,
            'properties' => $properties ?: null,
            'ip_address' => request()?->ip(),
            'created_at' => now(),
        ]);
    }
}
