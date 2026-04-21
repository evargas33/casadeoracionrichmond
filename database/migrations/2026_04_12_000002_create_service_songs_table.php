<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_songs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('service_plan_id')
                  ->constrained('service_plans')
                  ->cascadeOnDelete();

            $table->string('title', 200);
            $table->string('artist', 200)->nullable();
            $table->string('song_key', 10)->nullable();          // musical key: C, D, G, etc.
            $table->unsignedTinyInteger('order')->default(0);
            $table->text('notes')->nullable();

            $table->string('onsong_path', 500)->nullable();      // .onsong file
            $table->string('pdf_path', 500)->nullable();         // PDF file

            $table->timestamps();

            $table->index('service_plan_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_songs');
    }
};
