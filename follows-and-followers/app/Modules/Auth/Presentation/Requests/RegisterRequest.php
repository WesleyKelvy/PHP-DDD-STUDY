<?php

declare(strict_types=1);

namespace App\Modules\Auth\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Password;

class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:300'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => [
                'required',
                'confirmed',
                'min:8',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(), // checks HaveIBeenPwned API
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'        => 'Nome é obrigatório.',
            'name.string'          => 'Nome inválido.',
            'name.max'             => 'Limite de caracteres atingindo.',
            'email.required'       => 'O tipo de documento é obrigatório.',
            'email.email'          => 'Email inválido',
            'email.string'         => 'Email  inválido.',
            'email.max'            => 'Limite de caracteres atingindo.',
            'email.min'            => 'Email é obrigatório.',
            'password.required'    => 'Senha é obrigatório.',
            'password.string'      => 'Senha inválida.',
            'password.max'         => 'Limite de caracteres atingindo.',
            'password.min'         => 'Senha deve ter no mínimo 8 caractéres.',
        ];
    }
}
