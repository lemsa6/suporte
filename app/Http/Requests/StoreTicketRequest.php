<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTicketRequest extends FormRequest
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
        $user = auth()->user();
        
        return [
            'client_id' => [
                $user->isCliente() ? 'nullable' : 'required',
                'exists:clients,id'
            ],
            'category_id' => ['required', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:5000'],
            'priority' => ['required', Rule::in(['baixa', 'média', 'alta'])],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'is_urgent' => ['nullable', 'boolean'],
            'attachments.*' => [
                'nullable',
                'file',
                'max:25000',
                'mimes:pdf,jpg,jpeg,png,zip,txt,log,doc,docx,xls,xlsx'
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'client_id.required' => 'O cliente é obrigatório.',
            'client_id.exists' => 'O cliente selecionado não existe.',
            'category_id.required' => 'A categoria é obrigatória.',
            'category_id.exists' => 'A categoria selecionada não existe.',
            'title.required' => 'O título é obrigatório.',
            'title.max' => 'O título não pode ter mais de 255 caracteres.',
            'description.required' => 'A descrição é obrigatória.',
            'description.max' => 'A descrição não pode ter mais de 5000 caracteres.',
            'priority.required' => 'A prioridade é obrigatória.',
            'priority.in' => 'A prioridade deve ser: baixa, média ou alta.',
            'assigned_to.exists' => 'O técnico selecionado não existe.',
            'attachments.*.file' => 'O arquivo deve ser um arquivo válido.',
            'attachments.*.max' => 'O arquivo não pode ter mais de 25MB.',
            'attachments.*.mimes' => 'O arquivo deve ser do tipo: pdf, jpg, jpeg, png, zip, txt, log, doc, docx, xls, xlsx.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Se o usuário for cliente, definir automaticamente o client_id
        $user = auth()->user();
        if ($user->isCliente() && !$this->has('client_id')) {
            $client = \App\Models\Client::whereHas('contacts', function($query) use ($user) {
                $query->where('email', $user->email);
            })->first();
            
            if ($client) {
                $this->merge(['client_id' => $client->id]);
            }
        }
    }
}
