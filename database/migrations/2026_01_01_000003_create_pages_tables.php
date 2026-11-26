<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $t) {
            $t->id();
            $t->foreignId('parent_id')->nullable()->constrained('pages')->nullOnDelete();
            $t->string('title');
            $t->string('slug')->unique();             // '/' is stored as 'home'
            $t->string('template', 60)->default('default')->index();
            $t->text('excerpt')->nullable();
            $t->foreignId('featured_media_id')->nullable()->constrained('media')->nullOnDelete();
            $t->string('status', 20)->default('draft')->index(); // draft | published | scheduled
            $t->timestamp('publish_at')->nullable();
            $t->boolean('is_home')->default(false);
            $t->boolean('is_system')->default(false); // system pages (404, home) cannot be deleted
            $t->unsignedInteger('position')->default(0);
            $t->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $t->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamps();
        });

        Schema::create('page_sections', function (Blueprint $t) {
            $t->id();
            $t->foreignId('page_id')->constrained()->cascadeOnDelete();
            $t->string('type', 60);                   // hero, services_grid, stats, ...
            $t->string('label', 120)->nullable();     // admin-facing name
            $t->unsignedInteger('position')->default(0);
            $t->boolean('is_visible')->default(true);
            $t->json('content')->nullable();
            $t->timestamps();
            $t->index(['page_id', 'position']);
        });

        Schema::create('page_revisions', function (Blueprint $t) {
            $t->id();
            $t->foreignId('page_id')->constrained()->cascadeOnDelete();
            $t->json('snapshot');                     // page attributes + sections
            $t->string('note')->nullable();
            $t->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamp('created_at')->nullable();
            $t->index(['page_id', 'created_at']);
        });
    }

    public function down(): void
    {
        foreach (['page_revisions', 'page_sections', 'pages'] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
