@extends('layouts.app')

@section('title', 'Configurações do Sistema')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div class="mb-4 sm:mb-0">
        <span class="text-sm text-cinza-claro">Administração</span>
        <h1 class="page-title mt-1">Configurações do Sistema</h1>
        <p class="text-cinza mt-2">Gerencie as configurações gerais do sistema de tickets</p>
    </div>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Configurações Principais -->
    <h2 class="section-title mb-4">Configurações Principais</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Sistema -->
        <x-card>
            <div class="flex items-start space-x-4 mb-4">
                <div class="flex-shrink-0 w-12 h-12 bg-roxo bg-opacity-10 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-roxo" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-medium text-cinza mb-1">Sistema</h3>
                    <p class="text-sm text-cinza-claro">Configurações gerais</p>
                </div>
            </div>
            
            <p class="text-cinza mb-6">Configure nome do sistema, logo, informações da empresa e configurações básicas.</p>
            
            <div class="flex flex-wrap gap-2 mb-6">
                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-cinza-claro-2 text-cinza">Nome do Sistema</span>
                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-cinza-claro-2 text-cinza">Logo</span>
                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-cinza-claro-2 text-cinza">Empresa</span>
            </div>
            
            <x-button variant="primary" tag="a" href="{{ route('admin.settings.system') }}" class="w-full">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Configurar Sistema
            </x-button>
        </x-card>

        <!-- Email -->
        <x-card>
            <div class="flex items-start space-x-4 mb-4">
                <div class="flex-shrink-0 w-12 h-12 bg-verde bg-opacity-10 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-verde" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-medium text-cinza mb-1">Email</h3>
                    <p class="text-sm text-cinza-claro">Configurações SMTP</p>
                </div>
            </div>
            
            <p class="text-cinza mb-6">Configure servidor SMTP, credenciais e configurações de envio de emails.</p>
            
            <div class="flex flex-wrap gap-2 mb-6">
                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-cinza-claro-2 text-cinza">SMTP</span>
                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-cinza-claro-2 text-cinza">Credenciais</span>
                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-cinza-claro-2 text-cinza">Templates</span>
            </div>
            
            <x-button variant="outline" tag="a" href="{{ route('admin.settings.email') }}" class="w-full">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                Configurar Email
            </x-button>
        </x-card>

        <!-- Templates -->
        <x-card>
            <div class="flex items-start space-x-4 mb-4">
                <div class="flex-shrink-0 w-12 h-12 bg-amarelo bg-opacity-10 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-amarelo" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-medium text-cinza mb-1">Templates</h3>
                    <p class="text-sm text-cinza-claro">Templates de notificação</p>
                </div>
            </div>
            
            <p class="text-cinza mb-6">Edite e personalize os templates de email para notificações do sistema.</p>
            
            <div class="flex flex-wrap gap-2 mb-6">
                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-cinza-claro-2 text-cinza">Email HTML</span>
                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-cinza-claro-2 text-cinza">Variáveis</span>
                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-cinza-claro-2 text-cinza">Preview</span>
            </div>
            
            <x-button variant="outline" tag="a" href="{{ route('admin.settings.templates') }}" class="w-full">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Configurar Templates
            </x-button>
        </x-card>
    </div>

    <!-- Gerenciamento -->
    <h2 class="section-title mb-4">Gerenciamento</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Usuários -->
        <x-card>
            <div class="flex items-start space-x-4 mb-4">
                <div class="flex-shrink-0 w-12 h-12 bg-roxo-det bg-opacity-10 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-roxo-det" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-medium text-cinza mb-1">Usuários</h3>
                    <p class="text-sm text-cinza-claro">Gerenciar usuários</p>
                </div>
            </div>
            
            <p class="text-cinza mb-6">Gerencie usuários, permissões e configurações de acesso ao sistema.</p>
            
            <div class="flex flex-wrap gap-2 mb-6">
                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-cinza-claro-2 text-cinza">Permissões</span>
                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-cinza-claro-2 text-cinza">Funções</span>
                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-cinza-claro-2 text-cinza">Acesso</span>
            </div>
            
            <x-button variant="outline" tag="a" href="{{ route('settings.users') }}" class="w-full">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
                Gerenciar Usuários
            </x-button>
        </x-card>

        <!-- Notificações -->
        <x-card>
            <div class="flex items-start space-x-4 mb-4">
                <div class="flex-shrink-0 w-12 h-12 bg-vermelho bg-opacity-10 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-vermelho" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 19h6v-6H4v6z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5h6V1H4v4z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 3h5l-5-5v5z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-medium text-cinza mb-1">Notificações</h3>
                    <p class="text-sm text-cinza-claro">Configurações de alertas</p>
                </div>
            </div>
            
            <p class="text-cinza mb-6">Configure tipos de notificação, frequência e preferências do sistema.</p>
            
            <div class="flex flex-wrap gap-2 mb-6">
                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-cinza-claro-2 text-cinza">Email</span>
                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-cinza-claro-2 text-cinza">SMS</span>
                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-cinza-claro-2 text-cinza">Push</span>
            </div>
            
            <x-button variant="outline" tag="a" href="{{ route('admin.settings.notifications') }}" class="w-full">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 19h6v-6H4v6z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5h6V1H4v4z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 3h5l-5-5v5z"></path>
                </svg>
                Configurar Notificações
            </x-button>
        </x-card>
    </div>

    <!-- Informações do Sistema -->
    <h2 class="section-title mb-4">Informações do Sistema</h2>
    <x-card>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="text-center">
                <div class="w-12 h-12 bg-roxo bg-opacity-10 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-roxo" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <p class="text-sm text-cinza-claro mb-1">Versão</p>
                <p class="text-lg font-medium text-cinza">v1.2.7</p>
            </div>
            
            <div class="text-center">
                <div class="w-12 h-12 bg-verde bg-opacity-10 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-verde" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <p class="text-sm text-cinza-claro mb-1">Status</p>
                <p class="text-lg font-medium text-verde">Online</p>
            </div>
            
            <div class="text-center">
                <div class="w-12 h-12 bg-amarelo bg-opacity-10 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-amarelo" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                    </svg>
                </div>
                <p class="text-sm text-cinza-claro mb-1">Banco de Dados</p>
                <p class="text-lg font-medium text-cinza">MySQL</p>
            </div>
            
            <div class="text-center">
                <div class="w-12 h-12 bg-roxo-det bg-opacity-10 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-roxo-det" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <p class="text-sm text-cinza-claro mb-1">Uptime</p>
                <p class="text-lg font-medium text-cinza">99.9%</p>
            </div>
        </div>
    </x-card>
</div>
@endsection