<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')
                  ->nullable()
                  ->constrained('categories')
                  ->nullOnDelete();

            $table->string('title', 200);
            $table->string('slug', 210)->unique();
            $table->text('description');
            $table->text('short_description')->nullable();   // for cards (~160 chars)

            $table->datetime('start_date');
            $table->datetime('end_date')->nullable();        // null = single-day event
            $table->boolean('all_day')->default(false);

            $table->string('location', 200)->nullable();     // venue name
            $table->text('address')->nullable();
            $table->string('maps_url', 500)->nullable();

            $table->string('image', 255)->nullable();
            $table->unsignedSmallInteger('capacity')->nullable(); // null = unlimited

            $table->boolean('published')->default(false);
            $table->boolean('featured')->default(false);         // show in hero banner
            $table->boolean('requires_registration')->default(false);

            $table->timestamps();
            $table->softDeletes();                              // soft-delete for cancelled events

            $table->index(['published', 'start_date']);
            $table->index('featured');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
