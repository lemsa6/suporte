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
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- Sistema -->
    <x-card class="h-full flex flex-col">
        <div class="flex items-center mb-4">
            <div class="icon-circle-md bg-primary-100 text-primary-600 mr-4">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-cinza">Sistema</h3>
                <p class="text-sm text-cinza-claro">Configurações gerais</p>
            </div>
        </div>
        <p class="text-cinza mb-6 flex-grow">Configure nome do sistema, logo, informações da empresa e configurações básicas.</p>
        <x-button 
            variant="primary" 
            tag="a" 
            href="{{ route('admin.settings.system') }}"
        >
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            Configurar
        </x-button>
    </x-card>

    <!-- Email -->
    <x-card class="h-full flex flex-col">
        <div class="flex items-center mb-4">
            <div class="icon-circle-md bg-success-100 text-success-600 mr-4">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-cinza">Email</h3>
                <p class="text-sm text-cinza-claro">Configurações SMTP</p>
            </div>
        </div>
        <p class="text-cinza mb-6 flex-grow">Configure servidor SMTP, credenciais e configurações de envio de emails.</p>
        <x-button 
            variant="success" 
            tag="a" 
            href="{{ route('admin.settings.email') }}"
        >
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
            Configurar
        </x-button>
    </x-card>

    <!-- Templates -->
    <x-card class="h-full flex flex-col">
        <div class="flex items-center mb-4">
            <div class="icon-circle-md bg-warning-100 text-warning-600 mr-4">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-cinza">Templates</h3>
                <p class="text-sm text-cinza-claro">Templates de notificação</p>
            </div>
        </div>
        <p class="text-cinza mb-6 flex-grow">Edite e personalize os templates de email para notificações do sistema.</p>
        <x-button 
            variant="warning" 
            tag="a" 
            href="{{ route('admin.settings.templates') }}"
        >
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Configurar
        </x-button>
    </x-card>

    <!-- Usuários -->
    <x-card class="h-full flex flex-col">
        <div class="flex items-center mb-4">
            <div class="icon-circle-md bg-info-100 text-info-600 mr-4">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-cinza">Usuários</h3>
                <p class="text-sm text-cinza-claro">Gerenciar usuários</p>
            </div>
        </div>
        <p class="text-cinza mb-6 flex-grow">Gerencie usuários, permissões e configurações de acesso ao sistema.</p>
        <x-button 
            variant="info" 
            tag="a" 
            href="{{ route('settings.users') }}"
        >
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
            </svg>
            Configurar
        </x-button>
    </x-card>

    <!-- Notificações -->
    <x-card class="h-full flex flex-col">
        <div class="flex items-center mb-4">
            <div class="icon-circle-md bg-danger-100 text-danger-600 mr-4">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h6v-6H4v6zM4 5h6V1H4v4zM15 3h5l-5-5v5z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-cinza">Notificações</h3>
                <p class="text-sm text-cinza-claro">Configurações de alertas</p>
            </div>
        </div>
        <p class="text-cinza mb-6 flex-grow">Configure tipos de notificação, frequência e preferências do sistema.</p>
        <x-button 
            variant="danger" 
            tag="a" 
            href="{{ route('admin.settings.notifications') }}"
        >
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h6v-6H4v6zM4 5h6V1H4v4zM15 3h5l-5-5v5z"></path>
            </svg>
            Configurar
        </x-button>
    </x-card>
</div>
@endsection
