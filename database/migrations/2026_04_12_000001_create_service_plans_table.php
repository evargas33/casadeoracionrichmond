<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_plans', function (Blueprint $table) {
            $table->id();

            $table->date('date');
            $table->string('title', 200)->default('Servicio Dominical');
            $table->enum('service_type', ['domingo', 'sabado', 'viernes', 'especial'])->default('domingo');
            $table->enum('status', ['borrador', 'publicado'])->default('borrador');

            // Predicación — filled by pastor
            $table->string('sermon_topic', 300)->nullable();
            $table->string('bible_passage', 200)->nullable();
            $table->string('sermon_notes_path', 500)->nullable();       // pastor notes file (PDF/DOCX)
            $table->string('bible_citations_path', 500)->nullable();    // citations file for ProPresenter operator

            // Alabanza — filled by lider_alabanza
            $table->string('worship_uniform_color', 100)->nullable();
            $table->text('worship_uniform_notes')->nullable();

            // Ujieres — filled by lider_ujieres
            $table->string('usher_uniform_color', 100)->nullable();
            $table->text('usher_uniform_notes')->nullable();

            $table->timestamps();

            $table->index(['status', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_plans');
    }
};
