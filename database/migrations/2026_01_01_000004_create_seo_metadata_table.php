<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seo_metadata', function (Blueprint $t) {
            $t->id();
            $t->string('seoable_type', 60);
            $t->unsignedBigInteger('seoable_id');
            $t->string('title')->nullable();
            $t->string('description', 500)->nullable();
            $t->string('keywords', 500)->nullable();
            $t->string('canonical_url', 500)->nullable();
            $t->boolean('robots_index')->default(true);
            $t->boolean('robots_follow')->default(true);
            $t->string('og_title')->nullable();
            $t->string('og_description', 500)->nullable();
            $t->foreignId('og_image_id')->nullable()->constrained('media')->nullOnDelete();
            $t->string('og_type', 30)->nullable();
            $t->string('twitter_card', 30)->nullable();
            $t->string('twitter_title')->nullable();
            $t->string('twitter_description', 500)->nullable();
            $t->foreignId('twitter_image_id')->nullable()->constrained('media')->nullOnDelete();
            $t->json('structured_data')->nullable();
            $t->timestamps();
            $t->unique(['seoable_type', 'seoable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_metadata');
    }
};
