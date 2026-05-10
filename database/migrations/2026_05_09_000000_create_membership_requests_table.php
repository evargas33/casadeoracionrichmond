<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membership_requests', function (Blueprint $table) {
            $table->id();

            // Información personal
            $table->string('full_name', 200);
            $table->string('address', 300);
            $table->string('city', 100);
            $table->string('zip_code', 20);
            $table->date('birth_date');
            $table->string('phone', 30);
            $table->string('email', 200);

            // Estado civil
            $table->enum('marital_status', ['casado', 'soltero']);
            $table->string('spouse_name', 150)->nullable();

            // Hijos
            $table->boolean('has_children')->default(false);
            $table->text('children_names')->nullable();

            // Información espiritual
            $table->boolean('received_jesus')->default(false);
            $table->boolean('baptized_water')->default(false);
            $table->string('baptism_church', 200)->nullable();

            // Servicio ministerial
            $table->boolean('has_served_ministry')->default(false);
            $table->boolean('wants_serve_ministry')->default(false);

            // Contacto de emergencia
            $table->string('emergency_contact_name', 200);
            $table->string('emergency_contact_phone', 30);

            // Compromiso y firma
            $table->boolean('commitment_accepted')->default(false);
            $table->string('signature', 200);
            $table->date('submission_date');

            // Gestión de solicitud
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('rejection_reason')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membership_requests');
    }
};
