<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $t) {
            $t->id();
            $t->string('name', 80);
            $t->string('location', 60)->unique();     // primary, footer, footer_legal ...
            $t->timestamps();
        });

        Schema::create('menu_items', function (Blueprint $t) {
            $t->id();
            $t->foreignId('menu_id')->constrained()->cascadeOnDelete();
            $t->foreignId('parent_id')->nullable()->constrained('menu_items')->cascadeOnDelete();
            $t->string('label', 120);
            $t->string('type', 20)->default('url');   // page | url
            $t->foreignId('page_id')->nullable()->constrained('pages')->nullOnDelete();
            $t->string('url', 500)->nullable();
            $t->string('target', 10)->default('_self');
            $t->string('css_class', 120)->nullable();
            $t->unsignedInteger('position')->default(0);
            $t->boolean('is_active')->default(true);
            $t->timestamps();
            $t->index(['menu_id', 'parent_id', 'position']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_items');
        Schema::dropIfExists('menus');
    }
};
