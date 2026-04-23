<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;

#[Fillable([
    'name', 
    'email', 
    'password', 
    'curp', 
    'user_group', 
    'role', 
    'status'
])]
#[Hidden([
    'password', 
    'two_factor_secret', 
    'two_factor_recovery_codes', 
    'remember_token'
])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
            'status' => 'string', // Para manejar los estados PENDING, ACTIVE, etc.
        ];
    }

    /**
     * Relación con el perfil de Investigador
     * (Solo si el usuario es de grupo EXTERNAL y rol persona investigadora)
     */
    public function researcher()
    {
        return $this->hasOne(Researcher::class);
    }
    
    /**
     * Helper para saber si el usuario está activo
     */
    public function isActive(): bool
    {
        return $this->status === 'ACTIVE';
    }
}