<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InstitutionSeeder extends Seeder
{
    public function run(): void
{
    // Nivel 1: Dependencia
    $ssa = \App\Models\Institution::create([
        'name' => 'Secretaría de Salud',
        'short_name' => 'SSA',
        'sector' => 'PÚBLICO',
        'slug' => 'secretaria-de-salud'
    ]);

    // Nivel 2: Institución de Adscripción vinculada a la Dependencia
    $institutos = [
        ['name' => 'Instituto Nacional de Cancerología', 'short_name' => 'INCan'],
        ['name' => 'Instituto Nacional de Cardiología Ignacio Chávez', 'short_name' => 'INCICH'],
        ['name' => 'Instituto Nacional de Ciencias Médicas y Nutrición Salvador Zubirán', 'short_name' => 'INCMNSZ'],
    ];

    foreach ($institutos as $inst) {
        \App\Models\Institution::create([
            'parent_id' => $ssa->id,
            'name' => $inst['name'],
            'short_name' => $inst['short_name'],
            'sector' => 'PÚBLICO',
            'slug' => \Illuminate\Support\Str::slug($inst['short_name'])
        ]);
    }
}
}
