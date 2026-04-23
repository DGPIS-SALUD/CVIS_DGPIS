<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\User;
use Illuminate\Support\Facades\Hash; // Importante para encriptar la clave
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules, ProfileValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        // 1. Validamos los campos. 
        // Nota: He añadido curp e institution_id directamente aquí por claridad
        Validator::make($input, [
            ...$this->profileRules(),
            'curp' => ['required', 'string', 'size:18', 'unique:users,curp'],
            'institution_id' => ['required', 'exists:institutions,id'],
            'password' => $this->passwordRules(),
        ])->validate();

        // 2. Creamos el usuario con la lógica del CVIS
        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'curp' => strtoupper($input['curp']), // Siempre en mayúsculas
            'institution_id' => $input['institution_id'],
            'password' => Hash::make($input['password']), // Encriptamos la contraseña
            'user_group' => 'EXTERNAL',
            'role' => 'persona investigadora',
            'status' => 'PENDING', // Opción B: Entra en espera de aprobación
        ]);
    }
}