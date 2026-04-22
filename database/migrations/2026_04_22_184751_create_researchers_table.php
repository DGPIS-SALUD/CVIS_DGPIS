<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('researchers', function (Blueprint $table) {
            $table->id();
            
            // Relación con el Usuario (CURP / Acceso)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // IDENTIFICACIÓN ADICIONAL
            $table->string('cvu')->unique()->index();
            $table->string('orcid')->nullable()->unique();
            
            // DEMOGRAFÍA (Datos que se extraen de la CURP)
            $table->date('birth_date');
            $table->enum('gender', ['M', 'F', 'X']);
            $table->string('birth_state');

            // FORMACIÓN ACADÉMICA
            $table->string('academic_level'); 
            $table->string('degree_title');
            $table->string('degree_institution');
            
            // CLASIFICACIÓN CIENTÍFICA (Taxonomía SECIHTI)
            $table->string('knowledge_area');
            $table->string('field');
            $table->string('discipline');
            $table->string('subdiscipline')->nullable();

            // TRAYECTORIA (Relación con la tabla que ya arreglamos)
            $table->foreignId('institution_id')->constrained('institutions');
            $table->string('unit_administrative')->nullable(); // Nivel 3: Unidad
            $table->string('appointment')->nullable();         // Puesto/Nombramiento
            $table->date('assignment_start_date');

            $table->timestamps();
            $table->softDeletes(); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('researchers');
    }
};