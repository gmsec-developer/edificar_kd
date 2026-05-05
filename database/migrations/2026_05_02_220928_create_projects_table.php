<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();

            // Relación con empresa (MULTIEMPRESA)
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();

            // Usuario que crea el proyecto
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Nombre del proyecto
            $table->string('name');

            // Estado del proyecto
            $table->string('status')->default('draft'); // draft, processed

            // Datos del archivo .scn (estructura temporal)
            $table->json('scn_data')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
