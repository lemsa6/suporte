@extends('layouts.app')

@section('title', 'Configurações de Notificações')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div class="mb-4 sm:mb-0">
        <span class="text-sm text-cinza-claro">Administração</span>
        <h1 class="page-title mt-1">Configurações de Notificações</h1>
        <p class="text-cinza mt-2">Configure as notificações do sistema</p>
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
<div class="space-y-6">
    <!-- Notificações de Email -->
    <x-card title="Notificações de Email">
        <form method="POST" action="{{ route('admin.settings.notifications.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Notificações de Tickets -->
                <div>
                    <h3 class="text-lg font-medium text-cinza mb-4">Notificações de Tickets</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 border border-cinza-claro-2 rounded-lg">
                            <div>
                                <label class="text-sm font-medium text-cinza" for="notify_ticket_created">
                                    Novo Ticket Criado
                                </label>
                                <p class="text-xs text-cinza-claro mt-1">Notificar quando um novo ticket for criado</p>
                            </div>
                            <input type="checkbox" id="notify_ticket_created" name="notify_ticket_created" value="1" 
                                class="w-4 h-4 text-roxo border-cinza-claro-2 rounded focus:ring-roxo"
                                {{ old('notify_ticket_created', $settings['notify_ticket_created'] ?? false) ? 'checked' : '' }}>
                        </div>

                        <div class="flex items-center justify-between p-4 border border-cinza-claro-2 rounded-lg">
                            <div>
                                <label class="text-sm font-medium text-cinza" for="notify_ticket_replied">
                                    Ticket Respondido
                                </label>
                                <p class="text-xs text-cinza-claro mt-1">Notificar quando um ticket receber uma resposta</p>
                            </div>
                            <input type="checkbox" id="notify_ticket_replied" name="notify_ticket_replied" value="1" 
                                class="w-4 h-4 text-roxo border-cinza-claro-2 rounded focus:ring-roxo"
                                {{ old('notify_ticket_replied', $settings['notify_ticket_replied'] ?? false) ? 'checked' : '' }}>
                        </div>

                        <div class="flex items-center justify-between p-4 border border-cinza-claro-2 rounded-lg">
                            <div>
                                <label class="text-sm font-medium text-cinza" for="notify_ticket_status_changed">
                                    Status Alterado
                                </label>
                                <p class="text-xs text-cinza-claro mt-1">Notificar quando o status de um ticket for alterado</p>
                            </div>
                            <input type="checkbox" id="notify_ticket_status_changed" name="notify_ticket_status_changed" value="1" 
                                class="w-4 h-4 text-roxo border-cinza-claro-2 rounded focus:ring-roxo"
                                {{ old('notify_ticket_status_changed', $settings['notify_ticket_status_changed'] ?? false) ? 'checked' : '' }}>
                        </div>

                        <div class="flex items-center justify-between p-4 border border-cinza-claro-2 rounded-lg">
                            <div>
                                <label class="text-sm font-medium text-cinza" for="notify_ticket_assigned">
                                    Ticket Atribuído
                                </label>
                                <p class="text-xs text-cinza-claro mt-1">Notificar quando um ticket for atribuído a um técnico</p>
                            </div>
                            <input type="checkbox" id="notify_ticket_assigned" name="notify_ticket_assigned" value="1" 
                                class="w-4 h-4 text-roxo border-cinza-claro-2 rounded focus:ring-roxo"
                                {{ old('notify_ticket_assigned', $settings['notify_ticket_assigned'] ?? false) ? 'checked' : '' }}>
                        </div>
                    </div>
                </div>

                <!-- Notificações de Sistema -->
                <div>
                    <h3 class="text-lg font-medium text-cinza mb-4">Notificações de Sistema</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 border border-cinza-claro-2 rounded-lg">
                            <div>
                                <label class="text-sm font-medium text-cinza" for="notify_system_maintenance">
                                    Manutenção do Sistema
                                </label>
                                <p class="text-xs text-cinza-claro mt-1">Notificar sobre manutenções programadas</p>
                            </div>
                            <input type="checkbox" id="notify_system_maintenance" name="notify_system_maintenance" value="1" 
                                class="w-4 h-4 text-roxo border-cinza-claro-2 rounded focus:ring-roxo"
                                {{ old('notify_system_maintenance', $settings['notify_system_maintenance'] ?? false) ? 'checked' : '' }}>
                        </div>

                        <div class="flex items-center justify-between p-4 border border-cinza-claro-2 rounded-lg">
                            <div>
                                <label class="text-sm font-medium text-cinza" for="notify_security_alerts">
                                    Alertas de Segurança
                                </label>
                                <p class="text-xs text-cinza-claro mt-1">Notificar sobre eventos de segurança</p>
                            </div>
                            <input type="checkbox" id="notify_security_alerts" name="notify_security_alerts" value="1" 
                                class="w-4 h-4 text-roxo border-cinza-claro-2 rounded focus:ring-roxo"
                                {{ old('notify_security_alerts', $settings['notify_security_alerts'] ?? false) ? 'checked' : '' }}>
                        </div>

                        <div class="flex items-center justify-between p-4 border border-cinza-claro-2 rounded-lg">
                            <div>
                                <label class="text-sm font-medium text-cinza" for="notify_backup_status">
                                    Status de Backup
                                </label>
                                <p class="text-xs text-cinza-claro mt-1">Notificar sobre o status dos backups</p>
                            </div>
                            <input type="checkbox" id="notify_backup_status" name="notify_backup_status" value="1" 
                                class="w-4 h-4 text-roxo border-cinza-claro-2 rounded focus:ring-roxo"
                                {{ old('notify_backup_status', $settings['notify_backup_status'] ?? false) ? 'checked' : '' }}>
                        </div>
                    </div>
                </div>

                <!-- Configurações de Frequência -->
                <div>
                    <h3 class="text-lg font-medium text-cinza mb-4">Configurações de Frequência</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="notification_frequency" class="block text-sm font-medium text-cinza mb-2">Frequência de Notificações</label>
                            <select id="notification_frequency" name="notification_frequency" 
                                class="w-full px-3 py-2 border border-cinza-claro-2 rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent">
                                <option value="immediate" {{ old('notification_frequency', $settings['notification_frequency'] ?? 'immediate') == 'immediate' ? 'selected' : '' }}>Imediata</option>
                                <option value="hourly" {{ old('notification_frequency', $settings['notification_frequency'] ?? 'immediate') == 'hourly' ? 'selected' : '' }}>A cada hora</option>
                                <option value="daily" {{ old('notification_frequency', $settings['notification_frequency'] ?? 'immediate') == 'daily' ? 'selected' : '' }}>Diária</option>
                                <option value="weekly" {{ old('notification_frequency', $settings['notification_frequency'] ?? 'immediate') == 'weekly' ? 'selected' : '' }}>Semanal</option>
                            </select>
                        </div>

                        <div>
                            <label for="notification_time" class="block text-sm font-medium text-cinza mb-2">Horário de Notificação</label>
                            <input type="time" id="notification_time" name="notification_time" 
                                class="w-full px-3 py-2 border border-cinza-claro-2 rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent"
                                value="{{ old('notification_time', $settings['notification_time'] ?? '09:00') }}">
                        </div>
                    </div>
                </div>

                <!-- Botões -->
                <div class="flex justify-end gap-3 pt-6 border-t border-cinza-claro-2">
                    <x-button variant="outline" type="button" onclick="resetNotifications()">
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
            </div>
        </form>
    </x-card>

    <!-- Teste de Notificações -->
    <x-card title="Teste de Notificações">
        <div class="space-y-4">
            <p class="text-cinza-claro">Teste as configurações de notificação enviando um email de teste.</p>
            
            <div class="flex gap-3">
                <x-button variant="outline" onclick="testNotification('ticket')">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Testar Notificação de Ticket
                </x-button>
                
                <x-button variant="outline" onclick="testNotification('system')">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Testar Notificação de Sistema
                </x-button>
            </div>
        </div>
    </x-card>
</div>

<script>
function resetNotifications() {
    if (confirm('Tem certeza que deseja resetar todas as configurações de notificação para os valores padrão?')) {
        // Implementar reset das notificações
        console.log('Resetando configurações de notificação');
    }
}

function testNotification(type) {
    if (confirm(`Tem certeza que deseja enviar um email de teste de notificação ${type}?`)) {
        // Implementar teste de notificação
        console.log('Enviando teste de notificação:', type);
    }
}
</script>
@endsection