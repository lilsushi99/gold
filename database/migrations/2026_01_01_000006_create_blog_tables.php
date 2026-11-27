<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $t) {
            $t->id();
            $t->string('name', 120);
            $t->string('slug', 140)->unique();
            $t->text('description')->nullable();
            $t->timestamps();
        });

        Schema::create('tags', function (Blueprint $t) {
            $t->id();
            $t->string('name', 120);
            $t->string('slug', 140)->unique();
            $t->timestamps();
        });

        Schema::create('posts', function (Blueprint $t) {
            $t->id();
            $t->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $t->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $t->string('title');
            $t->string('slug')->unique();
            $t->text('excerpt')->nullable();
            $t->longText('content')->nullable();
            $t->foreignId('featured_media_id')->nullable()->constrained('media')->nullOnDelete();
            $t->string('featured_alt')->nullable();
            $t->string('status', 20)->default('draft')->index(); // draft | published | scheduled
            $t->dateTime('published_at')->nullable()->index();
            $t->unsignedInteger('reading_minutes')->nullable();
            $t->timestamps();
        });

        Schema::create('post_tag', function (Blueprint $t) {
            $t->foreignId('post_id')->constrained()->cascadeOnDelete();
            $t->foreignId('tag_id')->constrained()->cascadeOnDelete();
            $t->primary(['post_id', 'tag_id']);
        });
    }

    public function down(): void
    {
        foreach (['post_tag', 'posts', 'tags', 'categories'] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
