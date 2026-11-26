<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_submissions', function (Blueprint $t) {
            $t->id();
            $t->string('form', 40)->default('contact')->index();
            $t->string('name');
            $t->string('email');
            $t->string('phone', 40)->nullable();
            $t->string('subject')->nullable();
            $t->text('message');
            $t->json('extra')->nullable();
            $t->string('status', 20)->default('new')->index();   // new | read | archived
            $t->string('ip_address', 45)->nullable();
            $t->string('user_agent', 255)->nullable();
            $t->string('source_url', 500)->nullable();
            $t->timestamp('read_at')->nullable();
            $t->timestamps();
            $t->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_submissions');
    }
};
