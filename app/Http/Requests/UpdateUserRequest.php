<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
        $userId = $this->route('user')->id ?? $this->route('user');
        
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId)
            ],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'role' => ['required', Rule::in(['admin', 'tecnico', 'cliente_gestor', 'cliente_funcionario'])],
            'is_active' => ['boolean'],
            'notify_ticket_created' => ['boolean'],
            'notify_ticket_replied' => ['boolean'],
            'notify_ticket_status_changed' => ['boolean'],
            'notify_ticket_closed' => ['boolean'],
            'notify_ticket_priority_changed' => ['boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'O nome é obrigatório.',
            'name.max' => 'O nome não pode ter mais de 255 caracteres.',
            'email.required' => 'O email é obrigatório.',
            'email.email' => 'O email deve ter um formato válido.',
            'email.unique' => 'Este email já está cadastrado.',
            'password.confirmed' => 'A confirmação da senha não confere.',
            'role.required' => 'O perfil é obrigatório.',
            'role.in' => 'O perfil deve ser: admin, tecnico, cliente_gestor ou cliente_funcionario.',
        ];
    }
}
