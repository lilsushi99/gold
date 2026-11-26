<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class ContactSubmission extends Model
{
    protected $table = 'contact_submissions';

    protected $fillable = ['form', 'name', 'email', 'phone', 'subject', 'message', 'extra', 'status', 'ip_address', 'user_agent', 'source_url', 'read_at'];

    protected function casts(): array
    {
        return ['extra' => 'array', 'read_at' => 'datetime'];
    }
}
