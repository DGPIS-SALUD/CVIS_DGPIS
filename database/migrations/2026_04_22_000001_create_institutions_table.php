<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('institutions', function (Blueprint $table) {
            $table->id();
            
            // Jerarquía de 3 niveles: 
            // - Si parent_id es NULL: Es una Dependencia (Nivel 1: ej. SSA)
            // - Si parent_id tiene ID: Es una Institución de adscripción (Nivel 2: ej. INCan)
            // - El Nivel 3 (Unidad) lo manejamos como string en la tabla de investigadores para no saturar.
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('institutions')
                ->onDelete('cascade'); // Si borras la Dependencia, se borran sus hijos.

            $table->string('name')->index();
            $table->string('short_name')->nullable();
            
            // Clasificación según tu Excel
            $table->enum('sector', ['PÚBLICO', 'ACADEMIA', 'PRIVADO', 'OTRO'])->default('PÚBLICO');
            
            // Diferenciador: Catálogo oficial vs Captura manual de externo
            $table->boolean('is_official')->default(false);
            
            $table->string('slug')->unique();
            $table->timestamps();
            $table->softDeletes(); // Recomendado para evitar perder historial institucional
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('institutions');
    }
};