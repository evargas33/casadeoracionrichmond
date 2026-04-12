<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')                        // author / last editor
                  ->constrained('users')
                  ->restrictOnDelete();
            $table->foreignId('category_id')
                  ->nullable()
                  ->constrained('categories')
                  ->nullOnDelete();

            $table->string('title', 200);
            $table->string('slug', 210)->unique();              // e.g. "about-us"
            $table->longText('content');                        // HTML from TinyMCE

            // SEO
            $table->string('meta_title', 70)->nullable();
            $table->string('meta_description', 160)->nullable();
            $table->string('og_image', 255)->nullable();        // Open Graph image

            $table->boolean('published')->default(false);
            $table->boolean('in_menu')->default(false);         // show in main navigation
            $table->unsignedSmallInteger('order')->default(0);  // menu order

            // Layout hint for Blade views
            $table->string('template', 50)->default('default'); // default|contact|about|visit-us

            $table->timestamps();
            $table->softDeletes();

            $table->index(['published', 'slug']);
            $table->index('in_menu');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
