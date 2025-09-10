@extends('layouts.app')

@section('title', 'Configurações do Sistema')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div class="mb-4 sm:mb-0">
        <span class="text-sm text-cinza-claro">Administração</span>
        <h1 class="page-title mt-1">Configurações do Sistema</h1>
        <p class="text-cinza mt-2">Configure as informações básicas do sistema e da empresa</p>
    </div>
    <x-button variant="outline" tag="a" href="{{ route('admin.settings.index') }}">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Voltar
    </x-button>
</div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <h2 class="section-title mb-4">Informações Básicas</h2>
        <x-card>
            <form method="POST" action="{{ route('admin.settings.system.update') }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Nome do Sistema -->
                    <div>
                        <label for="app_name" class="block text-sm font-medium text-cinza mb-2">Nome do Sistema *</label>
                        <input type="text" id="app_name" name="app_name" 
                            class="w-full px-3 py-2 border border-cinza-claro-2 rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent @error('app_name') border-vermelho @enderror"
                            value="{{ old('app_name', $settings['app_name']) }}" required>
                        @error('app_name')
                            <div class="text-vermelho text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- URL do Sistema -->
                    <div>
                        <label for="app_url" class="block text-sm font-medium text-cinza mb-2">URL do Sistema *</label>
                        <input type="url" id="app_url" name="app_url" 
                            class="w-full px-3 py-2 border border-cinza-claro-2 rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent @error('app_url') border-vermelho @enderror"
                            value="{{ old('app_url', $settings['app_url']) }}" required>
                        @error('app_url')
                            <div class="text-vermelho text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Nome da Empresa -->
                    <div>
                        <label for="company_name" class="block text-sm font-medium text-cinza mb-2">Nome da Empresa *</label>
                        <input type="text" id="company_name" name="company_name" 
                            class="w-full px-3 py-2 border border-cinza-claro-2 rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent @error('company_name') border-vermelho @enderror"
                            value="{{ old('company_name', $settings['company_name']) }}" required>
                        @error('company_name')
                            <div class="text-vermelho text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- CNPJ da Empresa -->
                    <div>
                        <label for="company_cnpj" class="block text-sm font-medium text-cinza mb-2">CNPJ da Empresa</label>
                        <input type="text" id="company_cnpj" name="company_cnpj" 
                            class="w-full px-3 py-2 border border-cinza-claro-2 rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent @error('company_cnpj') border-vermelho @enderror"
                            value="{{ old('company_cnpj', $settings['company_cnpj']) }}"
                            placeholder="00.000.000/0000-00">
                        @error('company_cnpj')
                            <div class="text-vermelho text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email de Contato -->
                    <div>
                        <label for="contact_email" class="block text-sm font-medium text-cinza mb-2">Email de Contato *</label>
                        <input type="email" id="contact_email" name="contact_email" 
                            class="w-full px-3 py-2 border border-cinza-claro-2 rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent @error('contact_email') border-vermelho @enderror"
                            value="{{ old('contact_email', $settings['contact_email']) }}" required>
                        @error('contact_email')
                            <div class="text-vermelho text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Telefone de Contato -->
                    <div>
                        <label for="contact_phone" class="block text-sm font-medium text-cinza mb-2">Telefone de Contato</label>
                        <input type="tel" id="contact_phone" name="contact_phone" 
                            class="w-full px-3 py-2 border border-cinza-claro-2 rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent @error('contact_phone') border-vermelho @enderror"
                            value="{{ old('contact_phone', $settings['contact_phone']) }}"
                            placeholder="(11) 99999-9999">
                        @error('contact_phone')
                            <div class="text-vermelho text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Endereço -->
                <div>
                    <label for="company_address" class="block text-sm font-medium text-cinza mb-2">Endereço da Empresa</label>
                    <textarea id="company_address" name="company_address" rows="3"
                        class="w-full px-3 py-2 border border-cinza-claro-2 rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent @error('company_address') border-vermelho @enderror"
                        placeholder="Endereço completo da empresa">{{ old('company_address', $settings['company_address']) }}</textarea>
                    @error('company_address')
                        <div class="text-vermelho text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Botões -->
                <div class="flex justify-end gap-3 pt-6 border-t border-cinza-claro-2">
                    <x-button variant="outline" type="button" onclick="resetForm()">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Resetar
                    </x-button>
                    <x-button variant="primary" type="submit">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Salvar Configurações
                    </x-button>
                </div>
            </form>
        </x-card>
    </div>

    <!-- Sidebar com informações do sistema -->
    <div class="space-y-6">
        <x-card title="Informações do Sistema">
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-cinza-claro">Versão do Laravel:</span>
                    <span class="text-sm font-medium text-cinza">{{ app()->version() }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-cinza-claro">Versão do PHP:</span>
                    <span class="text-sm font-medium text-cinza">{{ PHP_VERSION }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-cinza-claro">Ambiente:</span>
                    <x-badge variant="{{ app()->environment('production') ? 'success' : 'warning' }}">
                        {{ app()->environment() }}
                    </x-badge>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-cinza-claro">Debug:</span>
                    <x-badge variant="{{ config('app.debug') ? 'danger' : 'success' }}">
                        {{ config('app.debug') ? 'Ativado' : 'Desativado' }}
                    </x-badge>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-cinza-claro">Cache:</span>
                    <x-badge variant="{{ config('cache.default') !== 'file' ? 'success' : 'warning' }}">
                        {{ config('cache.default') }}
                    </x-badge>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-cinza-claro">Sessão:</span>
                    <x-badge variant="{{ config('session.driver') !== 'file' ? 'success' : 'warning' }}">
                        {{ config('session.driver') }}
                    </x-badge>
                </div>
            </div>
        </x-card>

        <x-card title="Recursos do Sistema">
            <div class="space-y-3">
                <div class="flex items-center">
                    <svg class="w-4 h-4 text-verde mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="text-sm text-cinza">Sistema de Tickets</span>
                </div>
                <div class="flex items-center">
                    <svg class="w-4 h-4 text-verde mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="text-sm text-cinza">Gestão de Clientes</span>
                </div>
                <div class="flex items-center">
                    <svg class="w-4 h-4 text-verde mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="text-sm text-cinza">Relatórios</span>
                </div>
                <div class="flex items-center">
                    <svg class="w-4 h-4 text-verde mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="text-sm text-cinza">Auditoria</span>
                </div>
            </div>
        </x-card>
    </div>
</div>

<script>
function resetForm() {
    if (confirm('Tem certeza que deseja resetar o formulário?')) {
        document.querySelector('form').reset();
    }
}
</script>
@endsection