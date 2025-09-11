@extends('layouts.app')

@section('title', 'Configurações de Notificações')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div class="mb-4 sm:mb-0">
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('admin.settings.index') }}" class="text-cinza-claro hover:text-cinza">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <span class="text-sm text-cinza-claro">Configurações</span>
        </div>
        <h1 class="page-title mt-1">Configurações de Notificações</h1>
        <p class="text-cinza mt-2">Configure tipos de notificação, frequência e preferências do sistema</p>
    </div>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <form action="{{ route('admin.settings.notifications.update') }}" method="POST">
        @csrf
        @method('PUT')
        
        <!-- Configurações Gerais -->
        <h2 class="section-title mb-4">Configurações Gerais</h2>
        <x-card>
            <div class="space-y-6">
                <!-- Notificações Habilitadas -->
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-medium text-cinza">Notificações Habilitadas</h3>
                        <p class="text-sm text-cinza-claro">Habilitar ou desabilitar todas as notificações do sistema</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="notifications_enabled" value="1" class="sr-only peer" {{ old('notifications_enabled', $settings['notifications_enabled']) ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-cinza-claro-2 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-roxo/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-roxo"></div>
                    </label>
                </div>

                <!-- Frequência de Notificações -->
                <div>
                    <label for="notification_frequency" class="block text-sm font-medium text-cinza mb-2">Frequência de Notificações</label>
                    <select name="notification_frequency" id="notification_frequency" class="form-select">
                        <option value="immediate" {{ old('notification_frequency', $settings['notification_frequency']) === 'immediate' ? 'selected' : '' }}>Imediata</option>
                        <option value="hourly" {{ old('notification_frequency', $settings['notification_frequency']) === 'hourly' ? 'selected' : '' }}>A cada hora</option>
                        <option value="daily" {{ old('notification_frequency', $settings['notification_frequency']) === 'daily' ? 'selected' : '' }}>Diariamente</option>
                    </select>
                    <p class="mt-1 text-sm text-cinza-claro">Define com que frequência as notificações são enviadas</p>
                </div>
            </div>
        </x-card>

        <!-- Canais de Notificação -->
        <h2 class="section-title mb-4">Canais de Notificação</h2>
        <x-card>
            <div class="space-y-6">
                <!-- Email -->
                <div class="flex items-center justify-between">
                    <div class="flex items-start space-x-3">
                        <div class="w-10 h-10 bg-verde bg-opacity-10 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-verde" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-cinza">Notificações por Email</h3>
                            <p class="text-sm text-cinza-claro">Enviar notificações via email para usuários</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="email_notifications" value="1" class="sr-only peer" {{ old('email_notifications', $settings['email_notifications']) ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-cinza-claro-2 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-roxo/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-roxo"></div>
                    </label>
                </div>

                <!-- SMS -->
                <div class="flex items-center justify-between">
                    <div class="flex items-start space-x-3">
                        <div class="w-10 h-10 bg-amarelo bg-opacity-10 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-amarelo" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-cinza">Notificações por SMS</h3>
                            <p class="text-sm text-cinza-claro">Enviar notificações via SMS (requer configuração adicional)</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="sms_notifications" value="1" class="sr-only peer" {{ old('sms_notifications', $settings['sms_notifications']) ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-cinza-claro-2 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-roxo/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-roxo"></div>
                    </label>
                </div>

                <!-- Push -->
                <div class="flex items-center justify-between">
                    <div class="flex items-start space-x-3">
                        <div class="w-10 h-10 bg-roxo-det bg-opacity-10 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-roxo-det" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 19h6v-6H4v6z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5h6V1H4v4z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 3h5l-5-5v5z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-cinza">Notificações Push</h3>
                            <p class="text-sm text-cinza-claro">Enviar notificações push no navegador</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="push_notifications" value="1" class="sr-only peer" {{ old('push_notifications', $settings['push_notifications']) ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-cinza-claro-2 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-roxo/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-roxo"></div>
                    </label>
                </div>
            </div>
        </x-card>

        <!-- Eventos de Notificação -->
        <h2 class="section-title mb-4">Eventos de Notificação</h2>
        <x-card>
            <div class="space-y-6">
                <!-- Ticket Criado -->
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-medium text-cinza">Ticket Criado</h3>
                        <p class="text-sm text-cinza-claro">Notificar quando um novo ticket é criado</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="notify_ticket_created" value="1" class="sr-only peer" {{ old('notify_ticket_created', $settings['notify_ticket_created']) ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-cinza-claro-2 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-roxo/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-roxo"></div>
                    </label>
                </div>

                <!-- Ticket Atualizado -->
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-medium text-cinza">Ticket Atualizado</h3>
                        <p class="text-sm text-cinza-claro">Notificar quando um ticket é atualizado</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="notify_ticket_updated" value="1" class="sr-only peer" {{ old('notify_ticket_updated', $settings['notify_ticket_updated']) ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-cinza-claro-2 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-roxo/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-roxo"></div>
                    </label>
                </div>

                <!-- Ticket Fechado -->
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-medium text-cinza">Ticket Fechado</h3>
                        <p class="text-sm text-cinza-claro">Notificar quando um ticket é fechado</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="notify_ticket_closed" value="1" class="sr-only peer" {{ old('notify_ticket_closed', $settings['notify_ticket_closed']) ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-cinza-claro-2 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-roxo/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-roxo"></div>
                    </label>
                </div>

                <!-- Atribuição de Ticket -->
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-medium text-cinza">Atribuição de Ticket</h3>
                        <p class="text-sm text-cinza-claro">Notificar quando um ticket é atribuído a um usuário</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="notify_assignment" value="1" class="sr-only peer" {{ old('notify_assignment', $settings['notify_assignment']) ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-cinza-claro-2 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-roxo/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-roxo"></div>
                    </label>
                </div>
            </div>
        </x-card>

        <!-- Botões -->
        <div class="flex justify-end gap-3">
            <x-button variant="outline" tag="a" href="{{ route('admin.settings.index') }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Cancelar
            </x-button>
            
            <x-button variant="primary" type="submit">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Salvar Configurações
            </x-button>
        </div>
    </form>
</div>
@endsection