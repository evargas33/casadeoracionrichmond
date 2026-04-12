<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sermons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('series_id')
                  ->nullable()
                  ->constrained('series')
                  ->nullOnDelete();               // sermon survives if series is deleted

            $table->string('title', 200);
            $table->string('slug', 210)->unique();
            $table->string('speaker', 100);
            $table->date('date');
            $table->text('description')->nullable();
            $table->string('bible_passage', 100)->nullable(); // e.g. "John 3:16-17"

            $table->string('audio_url', 500)->nullable();
            $table->string('video_url', 500)->nullable();
            $table->string('image', 255)->nullable();

            $table->unsignedSmallInteger('duration_minutes')->nullable();
            $table->unsignedBigInteger('views')->default(0);

            $table->boolean('published')->default(false);
            $table->timestamp('published_at')->nullable();  // scheduled publish date
            $table->unsignedSmallInteger('order')->default(0);  // order within series

            $table->timestamps();

            $table->index(['published', 'date']);
            $table->index('series_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sermons');
    }
};
