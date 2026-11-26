<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('slug')->unique();
            $t->string('icon', 80)->nullable();
            $t->text('summary')->nullable();
            $t->longText('content')->nullable();
            $t->foreignId('media_id')->nullable()->constrained('media')->nullOnDelete();
            $t->string('cta_label', 80)->nullable();
            $t->string('cta_url', 500)->nullable();
            $t->unsignedInteger('position')->default(0);
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });

        Schema::create('projects', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('slug')->unique();
            $t->string('client')->nullable();
            $t->string('category', 120)->nullable()->index();
            $t->string('year', 4)->nullable();
            $t->string('role')->nullable();
            $t->text('summary')->nullable();
            $t->longText('content')->nullable();
            $t->foreignId('cover_media_id')->nullable()->constrained('media')->nullOnDelete();
            $t->json('gallery')->nullable();            // array of media ids
            $t->string('project_url', 500)->nullable();
            $t->unsignedInteger('position')->default(0);
            $t->boolean('is_featured')->default(false);
            $t->string('status', 20)->default('published')->index();
            $t->timestamps();
        });

        Schema::create('team_members', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('slug')->unique();
            $t->string('position_title');
            $t->text('bio')->nullable();
            $t->foreignId('media_id')->nullable()->constrained('media')->nullOnDelete();
            $t->json('socials')->nullable();
            $t->unsignedInteger('position')->default(0);
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });

        Schema::create('testimonials', function (Blueprint $t) {
            $t->id();
            $t->string('author_name');
            $t->string('author_role')->nullable();
            $t->string('company')->nullable();
            $t->text('quote');
            $t->unsignedTinyInteger('rating')->nullable();
            $t->foreignId('media_id')->nullable()->constrained('media')->nullOnDelete();
            $t->unsignedInteger('position')->default(0);
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });

        Schema::create('faqs', function (Blueprint $t) {
            $t->id();
            $t->string('group', 80)->default('general')->index();
            $t->string('question', 500);
            $t->text('answer');
            $t->unsignedInteger('position')->default(0);
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });

        Schema::create('pricing_plans', function (Blueprint $t) {
            $t->id();
            $t->string('name', 120);
            $t->string('price', 40);
            $t->string('period', 40)->nullable();
            $t->string('description', 500)->nullable();
            $t->json('features')->nullable();
            $t->string('cta_label', 80)->nullable();
            $t->string('cta_url', 500)->nullable();
            $t->boolean('is_featured')->default(false);
            $t->unsignedInteger('position')->default(0);
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['pricing_plans', 'faqs', 'testimonials', 'team_members', 'projects', 'services'] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
