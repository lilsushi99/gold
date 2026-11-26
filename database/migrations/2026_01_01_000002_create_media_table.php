<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media', function (Blueprint $t) {
            $t->id();
            $t->string('disk', 30)->default('public');
            $t->string('path')->unique();
            $t->string('filename');
            $t->string('original_name');
            $t->string('mime_type', 120);
            $t->string('extension', 12);
            $t->string('kind', 20)->index();          // image, document, video, other
            $t->unsignedBigInteger('size');
            $t->unsignedInteger('width')->nullable();
            $t->unsignedInteger('height')->nullable();
            $t->string('alt_text')->nullable();
            $t->string('title')->nullable();
            $t->text('description')->nullable();
            $t->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamps();
            $t->index('original_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
