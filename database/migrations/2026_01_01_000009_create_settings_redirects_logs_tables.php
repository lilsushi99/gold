<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $t) {
            $t->id();
            $t->string('group', 40)->index();
            $t->string('key', 100)->unique();
            $t->longText('value')->nullable();
            $t->string('type', 20)->default('string');   // string | text | bool | int | json | media
            $t->timestamps();
        });

        Schema::create('redirects', function (Blueprint $t) {
            $t->id();
            $t->string('source', 500);
            $t->string('source_hash', 64)->unique();      // sha256 of normalised source (indexable)
            $t->string('destination', 500);
            $t->unsignedSmallInteger('status_code')->default(301);
            $t->boolean('is_active')->default(true);
            $t->unsignedInteger('hits')->default(0);
            $t->timestamps();
        });

        Schema::create('activity_logs', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $t->string('action', 60)->index();
            $t->string('subject_type', 60)->nullable();
            $t->unsignedBigInteger('subject_id')->nullable();
            $t->string('description')->nullable();
            $t->json('properties')->nullable();
            $t->string('ip_address', 45)->nullable();
            $t->timestamp('created_at')->nullable()->index();
            $t->index(['subject_type', 'subject_id']);
        });
    }

    public function down(): void
    {
        foreach (['activity_logs', 'redirects', 'settings'] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
