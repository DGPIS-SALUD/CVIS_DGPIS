<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Institution;
use App\Models\Researcher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Poblamos los catálogos base primero
        $this->call([
            InstitutionSeeder::class,
        ]);

        // 2. Crear un usuario ADMINISTRADOR (DGPIS) para pruebas
        User::factory()->create([
            'name' => 'Admin CVIS',
            'curp' => 'ADMIN000000XXXX00', // CURP de prueba
            'email' => 'admin@cvis.gob.mx',
            'password' => Hash::make('password'),
            'user_group' => 'DGPIS',
            'role' => 'administrador',
        ]);

        // 3. Crear 10 Investigadores de prueba (EXTERNAL)
        // El ResearcherFactory que definimos se encarga de crear el User y el Perfil
        Researcher::factory(10)->create();
    }
}