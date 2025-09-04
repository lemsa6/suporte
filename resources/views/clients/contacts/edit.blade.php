@extends('layouts.app')

@section('title', 'Editar Contato')

@section('header')
<div class="md:d-flex md:align-items-center md:justify-content-between">
    <div class="min-w-0 d-flex-grow-1">
        <div class="d-flex align-items-center space-x-3">
            <a href="{{ route('clients.show', $client) }}" class="text-muted hover:text-muted">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h2 class="fs-2 fw-bold leading-7 text-dark sm:truncate sm:fs-1 sm:tracking-tight">
                Editar Contato
            </h2>
        </div>
        <div class="mt-1 d-flex align-items-center space-x-4">
            <span class="fs-6 text-muted">{{ $client->company_name }}</span>
            <span class="fs-6 text-muted">{{ $contact->name }}</span>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white shadow-sm rounded">
        <form action="{{ route('clients.contacts.update', ['client' => $client, 'contact' => $contact]) }}" method="POST" class="space-y-6 p-6">
            @csrf
            @method('PUT')
            
            <!-- Informações Pessoais -->
            <div>
                <h3 class="fs-4 fw-medium text-dark mb-4">Informações Pessoais</h3>
                <div class="grid row-cols-1 gap-4 sm:row-cols-2">
                    <div>
                        <label for="name" class="block fs-6 fw-medium text-dark">Nome Completo *</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $contact->name) }}" required
                            class="mt-1 block w-full rounded border-light shadow-sm-sm focus:border-brand-500 focus:ring-brand-500 sm:fs-6 @error('name') border-red-300 @enderror">
                        @error('name')
                            <p class="mt-1 fs-6 text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="email" class="block fs-6 fw-medium text-dark">E-mail *</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $contact->email) }}" required
                            class="mt-1 block w-full rounded border-light shadow-sm-sm focus:border-brand-500 focus:ring-brand-500 sm:fs-6 @error('email') border-red-300 @enderror">
                        @error('email')
                            <p class="mt-1 fs-6 text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Informações Profissionais -->
            <div>
                <h3 class="fs-4 fw-medium text-dark mb-4">Informações Profissionais</h3>
                <div class="grid row-cols-1 gap-4 sm:row-cols-2">
                    <div>
                        <label for="position" class="block fs-6 fw-medium text-dark">Cargo</label>
                        <input type="text" id="position" name="position" value="{{ old('position', $contact->position) }}"
                            class="mt-1 block w-full rounded border-light shadow-sm-sm focus:border-brand-500 focus:ring-brand-500 sm:fs-6 @error('position') border-red-300 @enderror">
                        @error('position')
                            <p class="mt-1 fs-6 text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="department" class="block fs-6 fw-medium text-dark">Departamento</label>
                        <input type="text" id="department" name="department" value="{{ old('department', $contact->department) }}"
                            class="mt-1 block w-full rounded border-light shadow-sm-sm focus:border-brand-500 focus:ring-brand-500 sm:fs-6 @error('department') border-red-300 @enderror">
                        @error('department')
                            <p class="mt-1 fs-6 text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Contato -->
            <div>
                <h3 class="fs-4 fw-medium text-dark mb-4">Contato</h3>
                <div>
                    <label for="phone" class="block fs-6 fw-medium text-dark">Telefone</label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone', $contact->phone) }}"
                        class="mt-1 block w-full rounded border-light shadow-sm-sm focus:border-brand-500 focus:ring-brand-500 sm:fs-6 @error('phone') border-red-300 @enderror" placeholder="(11) 99999-9999">
                    @error('phone')
                        <p class="mt-1 fs-6 text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Configurações -->
            <div>
                <h3 class="fs-4 fw-medium text-dark mb-4">Configurações</h3>
                <div class="space-y-4">
                    <div class="d-flex align-items-center">
                        <input type="checkbox" id="is_primary" name="is_primary" value="1" 
                            {{ old('is_primary', $contact->is_primary) ? 'checked' : '' }}
                            class="h-4 w-4 text-primary focus:ring-brand-500 border-light rounded">
                        <label for="is_primary" class="ml-2 block fs-6 text-dark">Contato principal</label>
                    </div>
                    
                    <div>
                        <label for="user_type" class="block fs-6 fw-medium text-dark">Tipo de Usuário *</label>
                        <select id="user_type" name="user_type" required
                            class="mt-1 block w-full rounded border-light shadow-sm-sm focus:border-brand-500 focus:ring-brand-500 sm:fs-6 @error('user_type') border-red-300 @enderror">
                            <option value="cliente_funcionario" {{ old('user_type', $contact->user_type) === 'cliente_funcionario' ? 'selected' : '' }}>
                                Funcionário da Empresa
                            </option>
                            <option value="cliente_gestor" {{ old('user_type', $contact->user_type) === 'cliente_gestor' ? 'selected' : '' }}>
                                Gestor da Empresa
                            </option>
                        </select>
                        <p class="mt-1 fs-6 text-muted">
                            <strong>Gestor:</strong> Pode criar usuários e ver todos os tickets da empresa<br>
                            <strong>Funcionário:</strong> Apenas seus próprios tickets
                        </p>
                        @error('user_type')
                            <p class="mt-1 fs-6 text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Redefinir Senha -->
            <div class="border-t border-light pt-6">
                <h3 class="fs-4 fw-medium text-dark mb-4">Redefinir Senha</h3>
                <div class="bg-blue-50 border border-blue-200 rounded p-4">
                    <div class="d-flex">
                        <div class="d-flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h4 class="fs-6 fw-medium text-blue-800">Informações sobre senha</h4>
                            <div class="mt-2 fs-6 text-blue-700">
                                <p>Deixe os campos de senha em branco se não quiser alterar a senha atual.</p>
                                <p class="mt-1">Se preencher, uma nova senha será definida para este usuário.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4 grid row-cols-1 gap-4 sm:row-cols-2">
                    <div>
                        <label for="new_password" class="block fs-6 fw-medium text-dark">Nova Senha</label>
                        <input type="password" id="new_password" name="new_password" minlength="8"
                            class="mt-1 block w-full rounded border-light shadow-sm-sm focus:border-brand-500 focus:ring-brand-500 sm:fs-6 @error('new_password') border-red-300 @enderror">
                        <p class="mt-1 fs-6 text-muted">Mínimo de 8 caracteres</p>
                        @error('new_password')
                            <p class="mt-1 fs-6 text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="new_password_confirmation" class="block fs-6 fw-medium text-dark">Confirmar Nova Senha</label>
                        <input type="password" id="new_password_confirmation" name="new_password_confirmation" minlength="8"
                            class="mt-1 block w-full rounded border-light shadow-sm-sm focus:border-brand-500 focus:ring-brand-500 sm:fs-6 @error('new_password_confirmation') border-red-300 @enderror">
                        @error('new_password_confirmation')
                            <p class="mt-1 fs-6 text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Botões -->
            <div class="d-flex justify-content-end space-x-3 pt-6 border-t border-light">
                <a href="{{ route('clients.show', $client) }}" 
                    class="inline-d-flex align-items-center px-4 py-2 border border-light shadow-sm-sm fs-6 fw-medium rounded text-dark bg-white hover:bg-light focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500">
                    Cancelar
                </a>
                <button type="submit" 
                    class="inline-d-flex align-items-center px-4 py-2 border border-transparent fs-6 fw-medium rounded shadow-sm-sm text-white bg-primary hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Atualizar Contato
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
