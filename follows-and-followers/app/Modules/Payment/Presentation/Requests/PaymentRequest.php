<?php

declare(strict_types=1);

namespace App\Modules\Payment\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class PaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'doc_type'   => ['required', 'string', 'in:CPF,CNPJ'],
            'doc_number' => ['required', 'string', 'min:11', 'max:14'],
            'first_name' => ['required', 'string', 'min:1', 'max:50', 'regex:/^[\p{L}]+([ \'-][\p{L}]+)*$/u'],
            'last_name'  => ['required', 'string', 'min:1', 'max:70', 'regex:/^[\p{L}]+([ \'-][\p{L}]+)*$/u'],
        ];
    }

    public function messages(): array
    {
        return [
            'doc_type.required'   => 'O tipo de documento é obrigatório.',
            'doc_type.in'         => 'O documento deve ser CPF ou CNPJ.',
            'doc_number.required' => 'Informe o número do documento.',
            'doc_number.string'   => 'O número do documento deve ser um texto válido.',
            'doc_number.min'      => 'O documento deve ter no mínimo 11 dígitos (CPF).',
            'doc_number.max'      => 'O documento deve ter no máximo 14 dígitos (CNPJ).',
            'first_name.required' => 'O primeiro nome é obrigatório.',
            'first_name.min'      => 'O primeiro nome deve ter pelo menos 1 caractere.',
            'first_name.max'      => 'O primeiro nome não pode ter mais de 50 caracteres.',
            'first_name.regex'    => 'O primeiro nome não pode conter números ou caracteres especiais.',
            'last_name.required'  => 'O sobrenome é obrigatório.',
            'last_name.min'       => 'O sobrenome deve ter pelo menos 1 caractere.',
            'last_name.max'       => 'O sobrenome não pode ter mais de 70 caracteres.',
            'last_name.regex'     => 'O sobrenome não pode conter números ou caracteres especiais.',
        ];
    }
}
