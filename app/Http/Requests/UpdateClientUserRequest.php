<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Autorização será feita via Policy
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $contact = $this->route('contact');
        $userId = null;

        // Buscar o user_id do contato para validação de email único
        if (is_numeric($contact)) {
            $contactModel = \App\Models\ClientContact::find($contact);
            $userId = $contactModel?->user_id;
        } elseif ($contact instanceof \App\Models\ClientContact) {
            $userId = $contact->user_id;
        }

        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . ($userId ?? 'NULL'),
            'position' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'user_type' => 'required|in:cliente_funcionario,cliente_gestor',
            'receive_notifications' => 'sometimes|boolean',
            'new_password' => 'nullable|string|min:8',
            'new_password_confirmation' => 'nullable|string|min:8|same:new_password',
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
            'email.unique' => 'Este email já está sendo usado por outro usuário.',
            'user_type.required' => 'O tipo de usuário é obrigatório.',
            'user_type.in' => 'O tipo de usuário deve ser Funcionário ou Gestor.',
            'phone.max' => 'O telefone não pode ter mais de 20 caracteres.',
            'position.max' => 'O cargo não pode ter mais de 255 caracteres.',
            'department.max' => 'O departamento não pode ter mais de 255 caracteres.',
            'new_password.min' => 'A nova senha deve ter pelo menos 8 caracteres.',
            'new_password_confirmation.same' => 'A confirmação da nova senha não confere.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Garantir que receive_notifications seja boolean
        $this->merge([
            'receive_notifications' => $this->has('receive_notifications') && $this->receive_notifications == '1',
        ]);
    }
}
