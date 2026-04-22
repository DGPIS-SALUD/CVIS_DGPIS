<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Researcher extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'cvu',
        'orcid',
        'snii_level',
        'sii_level',
        'birth_date',
        'gender',
        'birth_state',
        'academic_level',
        'degree_title',
        'degree_institution',
        'knowledge_area',
        'field',
        'discipline',
        'subdiscipline',
        'institution_id',
        'unit_administrative',
        'appointment',
        'assignment_start_date',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'assignment_start_date' => 'date',
    ];

    /**
     * Relación con el Usuario (CURP/Acceso)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con la Institución de adscripción
     */
    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }
}