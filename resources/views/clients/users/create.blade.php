@extends('layouts.app')

@section('title', 'Novo Usuário - ' . $client->display_name)

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div class="mb-4 sm:mb-0">
        <div class="flex items-center">
            <x-button variant="outline" size="sm" tag="a" href="{{ route('clients.users.index', $client) }}" class="mr-3">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Voltar
            </x-button>
            <div>
                <span class="text-sm text-cinza-claro">{{ $client->display_name }}</span>
                <h1 class="page-title mt-1">Novo Usuário</h1>
                <p class="text-cinza mt-2">Adicione um novo usuário para a empresa</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <form action="{{ route('clients.users.store', $client) }}" method="POST" class="space-y-6">
        @csrf
        
        <!-- Informações Pessoais -->
        <x-card>
            <x-slot name="header">
                <h2 class="section-title">Informações Pessoais</h2>
            </x-slot>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-input 
                    label="Nome *"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    error="{{ $errors->first('name') }}"
                    placeholder="Nome completo do usuário"
                />
                
                <x-input 
                    label="Email *"
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
                    required
                    error="{{ $errors->first('email') }}"
                    placeholder="email@exemplo.com"
                />
                
                <x-input 
                    label="Telefone"
                    name="phone"
                    type="tel"
                    value="{{ old('phone') }}"
                    error="{{ $errors->first('phone') }}"
                    placeholder="(11) 99999-9999"
                />
                
                <x-input 
                    label="Cargo"
                    name="position"
                    value="{{ old('position') }}"
                    error="{{ $errors->first('position') }}"
                    placeholder="Cargo na empresa"
                />
                
                <x-input 
                    label="Departamento"
                    name="department"
                    value="{{ old('department') }}"
                    error="{{ $errors->first('department') }}"
                    placeholder="Departamento"
                />
                
                <x-select 
                    label="Tipo de Usuário *"
                    name="user_type"
                    :options="[
                        'cliente_funcionario' => 'Funcionário da Empresa',
                        'cliente_gestor' => 'Gestor da Empresa'
                    ]"
                    value="{{ old('user_type', 'cliente_funcionario') }}"
                    required
                    error="{{ $errors->first('user_type') }}"
                />
            </div>
            
            <div class="mt-6">
                <div class="flex items-center">
                    <input type="hidden" name="receive_notifications" value="0">
                    <input type="checkbox" id="receive_notifications" name="receive_notifications" value="1" 
                           {{ old('receive_notifications', true) ? 'checked' : '' }}
                           class="h-4 w-4 text-roxo focus:ring-roxo border-cinza-claro rounded">
                    <label for="receive_notifications" class="ml-2 text-sm text-cinza">Receber notificações por email</label>
                </div>
                @error('receive_notifications')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="bg-creme p-4 rounded-lg border border-padrao mt-6">
                <div class="text-sm text-cinza-claro">
                    <strong class="text-cinza">Gestor:</strong> Pode criar usuários e ver todos os tickets da empresa<br>
                    <strong class="text-cinza">Funcionário:</strong> Apenas seus próprios tickets
                </div>
            </div>
        </x-card>
        
        <!-- Senha -->
        <x-card>
            <x-slot name="header">
                <h2 class="section-title">Senha de Acesso</h2>
            </x-slot>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-input 
                    label="Senha *"
                    name="password"
                    type="password"
                    required
                    error="{{ $errors->first('password') }}"
                    help="Mínimo 8 caracteres"
                />
                
                <x-input 
                    label="Confirmar Senha *"
                    name="password_confirmation"
                    type="password"
                    required
                    error="{{ $errors->first('password_confirmation') }}"
                    help="Repita a senha"
                />
            </div>
        </x-card>
        
        <!-- Botões -->
        <div class="flex justify-end gap-3">
            <x-button variant="outline" tag="a" href="{{ route('clients.users.index', $client) }}">
                Cancelar
            </x-button>
            
            <x-button variant="primary" type="submit">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Criar Usuário
            </x-button>
        </div>
    </form>
</div>
@endsection
