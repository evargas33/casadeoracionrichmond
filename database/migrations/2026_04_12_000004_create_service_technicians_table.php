<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_technicians', function (Blueprint $table) {
            $table->id();

            $table->foreignId('service_plan_id')
                  ->constrained('service_plans')
                  ->cascadeOnDelete();

            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->string('name', 200);
            $table->enum('position', ['mixer', 'proyeccion', 'streaming', 'apoyo'])->default('apoyo');
            $table->string('notes', 300)->nullable();

            $table->timestamps();

            $table->index('service_plan_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_technicians');
    }
};
