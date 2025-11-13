@extends('layouts.app')

@section('title', 'Editar Cliente - ' . $client->company_name)

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div class="mb-4 sm:mb-0">
        <div class="flex items-center">
            <x-button variant="outline" size="sm" tag="a" href="{{ route('clients.show', $client) }}" class="mr-3">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Voltar
            </x-button>
            <div>
                <h1 class="page-title mb-0">Editar Cliente</h1>
                <p class="text-cinza-claro mb-0">{{ $client->company_name }}</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <form action="{{ route('clients.update', $client) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        <!-- Informações da Empresa -->
        <h2 class="section-title mb-4">Informações da Empresa</h2>
        <x-card>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nome da Empresa -->
                <div class="md:col-span-2">
                    <x-input 
                        label="Nome da Empresa"
                        name="company_name"
                        value="{{ old('company_name', $client->company_name) }}"
                        placeholder="Nome completo da empresa"
                        required
                        error="{{ $errors->first('company_name') }}"
                    />
                </div>

                <!-- Nome Fantasia -->
                <div>
                    <x-input 
                        label="Nome Fantasia"
                        name="trade_name"
                        value="{{ old('trade_name', $client->trade_name) }}"
                        placeholder="Nome fantasia (se houver)"
                        error="{{ $errors->first('trade_name') }}"
                    />
                </div>

                <!-- CNPJ -->
                <div>
                    <x-input 
                        label="CNPJ"
                        name="cnpj"
                        value="{{ old('cnpj', $client->cnpj) }}"
                        placeholder="00.000.000/0000-00"
                        maxlength="18"
                        required
                        error="{{ $errors->first('cnpj') }}"
                    />
                </div>

                <!-- Email -->
                <div>
                    <x-input 
                        label="Email"
                        name="email"
                        type="email"
                        value="{{ old('email', $client->email) }}"
                        placeholder="email@empresa.com"
                        error="{{ $errors->first('email') }}"
                    />
                </div>

                <!-- Telefone -->
                <div>
                    <x-input 
                        label="Telefone"
                        name="phone"
                        type="tel"
                        value="{{ old('phone', $client->phone) }}"
                        placeholder="(11) 99999-9999"
                        error="{{ $errors->first('phone') }}"
                    />
                </div>

                <!-- Endereço -->
                <div class="md:col-span-2">
                    <x-textarea 
                        label="Endereço"
                        name="address"
                        rows="3"
                        placeholder="Endereço completo da empresa"
                        error="{{ $errors->first('address') }}"
                    >
                        {{ old('address', $client->address) }}
                    </x-textarea>
                </div>

                <!-- Observações -->
                <div class="md:col-span-2">
                    <x-textarea 
                        label="Observações"
                        name="notes"
                        rows="3"
                        placeholder="Informações adicionais sobre a empresa"
                        error="{{ $errors->first('notes') }}"
                    >
                        {{ old('notes', $client->notes) }}
                    </x-textarea>
                </div>

                <!-- Status -->
                <div class="md:col-span-2">
                    <div class="flex items-center">
                        <div class="flex items-center h-5">
                            <input type="checkbox" id="is_active" name="is_active" value="1" 
                                   class="h-4 w-4 text-roxo focus:ring-roxo border-cinza-claro rounded"
                                   {{ old('is_active', $client->is_active) ? 'checked' : '' }}>
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="is_active" class="font-medium text-cinza">
                                Cliente ativo
                            </label>
                        </div>
                    </div>
                    @error('is_active')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </x-card>

        <!-- Botão para Gerenciamento de Usuários -->
        @if(auth()->user()->isAdmin() || auth()->user()->isTecnico() || 
            (auth()->user()->isClienteGestor() && $client->contacts->where('email', auth()->user()->email)->where('user_type', 'cliente_gestor')->first()))
        <div class="flex justify-center">
            <x-button variant="outline" tag="a" href="{{ route('clients.users.index', $client) }}">
                Gerenciar Usuários
            </x-button>
        </div>
        @endif

        <!-- Botões de Ação -->
        <div class="flex justify-end gap-3">
            <x-button variant="outline" tag="a" href="{{ route('clients.show', $client) }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Cancelar
            </x-button>
            
            <x-button variant="primary" type="submit">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Atualizar Cliente
            </x-button>
        </div>
    </form>
</div>

@endsection