<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTicketRequest extends FormRequest
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
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:5000'],
            'category_id' => ['required', 'exists:categories,id'],
            'priority' => ['required', Rule::in(['baixa', 'média', 'alta'])],
            'status' => ['required', Rule::in(['aberto', 'em_andamento', 'resolvido', 'fechado'])],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'is_urgent' => ['boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'O título é obrigatório.',
            'title.max' => 'O título não pode ter mais de 255 caracteres.',
            'description.required' => 'A descrição é obrigatória.',
            'description.max' => 'A descrição não pode ter mais de 5000 caracteres.',
            'category_id.required' => 'A categoria é obrigatória.',
            'category_id.exists' => 'A categoria selecionada não existe.',
            'priority.required' => 'A prioridade é obrigatória.',
            'priority.in' => 'A prioridade deve ser: baixa, média ou alta.',
            'status.required' => 'O status é obrigatório.',
            'status.in' => 'O status deve ser: aberto, em_andamento, resolvido ou fechado.',
            'assigned_to.exists' => 'O técnico selecionado não existe.',
        ];
    }
}
