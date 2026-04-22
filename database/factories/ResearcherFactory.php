<?php

namespace Database\Factories;

use App\Models\Institution;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResearcherFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'cvu' => $this->faker->unique()->numerify('##########'),
            'orcid' => $this->faker->unique()->regexify('\d{4}-\d{4}-\d{4}-\d{4}'),
            'birth_date' => $this->faker->date('Y-m-d', '-30 years'),
            'gender' => $this->faker->randomElement(['M', 'F', 'X']),
            'birth_state' => $this->faker->state(),
            'academic_level' => $this->faker->randomElement(['Maestría', 'Doctorado', 'Postdoctorado']),
            'degree_title' => $this->faker->jobTitle(),
            'degree_institution' => $this->faker->company(),
            'knowledge_area' => 'CIENCIAS DE LA SALUD',
            'field' => 'MEDICINA',
            'discipline' => 'INMUNOLOGÍA',
            'institution_id' => \App\Models\Institution::inRandomOrder()->first()?->id ?? \App\Models\Institution::factory(),
            'assignment_start_date' => now()->subYears(2),
        ];
    }
}