<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->restrictOnDelete();

            $table->string('name', 200);                    // original filename
            $table->string('disk_name', 200);               // uuid-based filename on disk
            $table->string('path', 500);                    // relative path in storage
            $table->string('url', 500)->nullable();         // public URL for CDN / S3

            $table->string('mime_type', 100);               // image/jpeg, audio/mpeg…
            $table->unsignedBigInteger('size');             // bytes

            // Polymorphic: which model owns this file (Sermon, Event, Page, or none)
            $table->nullableMorphs('mediable');             // mediable_type + mediable_id

            // Optional metadata
            $table->unsignedSmallInteger('width')->nullable();
            $table->unsignedSmallInteger('height')->nullable();
            $table->unsignedInteger('duration_seconds')->nullable();
            $table->string('alt_text', 255)->nullable();

            $table->timestamps();

            $table->index('mime_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
