<?php

declare(strict_types=1);

namespace App\Modules\Auth\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginFormRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email'      => ['required', 'string', 'max:255', 'email', 'min:1'],
            'password'   => ['required', 'string', 'max:255', 'min:8'],
            'remember'   => ['nullable'],
        ];
    }

    public function messages(): array
    {
        return [
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
