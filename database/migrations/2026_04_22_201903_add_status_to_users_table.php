<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // PENDING: Recién registrado, esperando aprobación.
            // ACTIVE: Aprobado por el enlace, puede entrar.
            // REJECTED: Rechazado por el enlace.
            // SUSPENDED: Bloqueado temporalmente por la DGPIS.
            $table->enum('status', ['PENDING', 'ACTIVE', 'REJECTED', 'SUSPENDED'])
                  ->default('PENDING')
                  ->after('role'); // Lo ponemos después del rol por orden visual
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};