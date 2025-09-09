<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $clientId = $this->route('client')->id ?? $this->route('client');
        
        return [
            'cnpj' => [
                'required',
                'string',
                Rule::unique('clients', 'cnpj')->ignore($clientId),
                'regex:/^\d{2}\.\d{3}\.\d{3}\/\d{4}-\d{2}$/'
            ],
            'company_name' => ['required', 'string', 'max:255'],
            'trade_name' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'cnpj.required' => 'O CNPJ é obrigatório.',
            'cnpj.unique' => 'Este CNPJ já está cadastrado.',
            'cnpj.regex' => 'O CNPJ deve estar no formato 00.000.000/0000-00.',
            'company_name.required' => 'O nome da empresa é obrigatório.',
            'company_name.max' => 'O nome da empresa não pode ter mais de 255 caracteres.',
            'trade_name.max' => 'O nome fantasia não pode ter mais de 255 caracteres.',
            'address.max' => 'O endereço não pode ter mais de 500 caracteres.',
            'phone.max' => 'O telefone não pode ter mais de 20 caracteres.',
            'email.email' => 'O email deve ter um formato válido.',
            'email.max' => 'O email não pode ter mais de 255 caracteres.',
            'notes.max' => 'As observações não podem ter mais de 1000 caracteres.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Limpar CNPJ antes de validar (remover formatação)
        if ($this->has('cnpj')) {
            $this->merge([
                'cnpj' => preg_replace('/[^0-9]/', '', $this->cnpj)
            ]);
        }
    }
}
