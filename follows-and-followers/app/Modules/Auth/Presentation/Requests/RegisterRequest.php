<?php

declare(strict_types=1);

namespace App\Modules\Auth\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'                  => ['required', 'string', 'max:300'],
            'email'                 => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'              => [
                'required',
                'confirmed',
                'min:8',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    // ->uncompromised(), // checks HaveIBeenPwned API
            ],
            'password_confirmation' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'                  => 'Nome é obrigatório.',
            'name.string'                    => 'Nome inválido.',
            'name.max'                       => 'Limite de caracteres atingindo.',
            'email.required'                 => 'Email é obrigatório.',
            'email.email'                    => 'Email inválido',
            'email.string'                   => 'Email inválido.',
            'email.max'                      => 'Limite de caracteres atingindo.',
            'email.unique'                   => 'Este email já está cadastrado.',
            'password.required'              => 'Senha é obrigatório.',
            'password.min'                   => 'Senha deve ter no mínimo 8 caractéres.',
            'password.confirmed'             => 'A confirmação de senha não confere.',
            'password_confirmation.required' => 'Confirmação de senha é obrigatória.',
        ];
    }
}
