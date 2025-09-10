@extends('layouts.app')

@section('title', 'Editar Perfil')

@section('header')
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div class="mb-4 sm:mb-0">
            <span class="text-sm text-cinza-claro">Perfil</span>
            <h1 class="page-title mt-1">Editar Perfil</h1>
            <p class="text-cinza mt-2">Atualize suas informações pessoais e configurações da conta</p>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="space-y-8">
    <!-- Navegação por Abas -->
    <x-card class="overflow-hidden">
        <div class="border-b border-cinza-claro">
            <nav class="-mb-px flex space-x-8 px-6">
                <a href="{{ route('profile.edit', ['tab' => 'profile']) }}" 
                   class="tab-link {{ !request('tab') || request('tab') === 'profile' ? 'active' : '' }}">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Perfil
                </a>
                <a href="{{ route('profile.edit', ['tab' => 'notifications']) }}" 
                   class="tab-link {{ request('tab') === 'notifications' ? 'active' : '' }}">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h6v-6H4v6zM4 5h6V1H4v4zM15 3h5l-5-5v5z"></path>
                    </svg>
                    Notificações
                </a>
                <a href="{{ route('profile.edit', ['tab' => 'security']) }}" 
                   class="tab-link {{ request('tab') === 'security' ? 'active' : '' }}">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    Segurança
                </a>
            </nav>
        </div>
    </x-card>

    <!-- Conteúdo das Abas -->
    <div class="tab-content" id="profileTabsContent">
        <!-- Aba Perfil -->
        <div class="tab-pane fade {{ !request('tab') || request('tab') === 'profile' ? 'show active' : '' }}" 
             id="profile" role="tabpanel">
            <div class="space-y-6">
                <!-- Informações Básicas -->
                <x-card title="Informações Básicas">
                    <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nome -->
                            <div>
                                <x-input 
                                    label="Nome Completo"
                                    name="name"
                                    value="{{ old('name', $user->name) }}"
                                    required
                                    error="{{ $errors->first('name') }}"
                                />
                            </div>

                            <!-- Email -->
                            <div>
                                <x-input 
                                    label="Email"
                                    name="email"
                                    type="email"
                                    value="{{ old('email', $user->email) }}"
                                    required
                                    error="{{ $errors->first('email') }}"
                                />
                            </div>

                            <!-- Fuso Horário -->
                            <div>
                                <x-select 
                                    label="Fuso Horário"
                                    name="timezone"
                                    :options="$timezones"
                                    :selected="old('timezone', $user->timezone)"
                                    error="{{ $errors->first('timezone') }}"
                                />
                            </div>

                            <!-- Idioma -->
                            <div>
                                <x-select 
                                    label="Idioma"
                                    name="language"
                                    :options="[
                                        'pt_BR' => 'Português (Brasil)',
                                        'en' => 'English',
                                        'es' => 'Español'
                                    ]"
                                    :selected="old('language', $user->language)"
                                    error="{{ $errors->first('language') }}"
                                />
                            </div>
                        </div>

                        <div class="flex justify-end pt-4 border-t border-cinza-claro">
                            <x-button variant="primary" type="submit">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Salvar Perfil
                            </x-button>
                        </div>
                    </form>
                </x-card>

                <!-- Informações da Conta -->
                <x-card title="Informações da Conta">
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-cinza mb-2">Membro desde</label>
                                <p class="text-cinza-claro">{{ $user->created_at->format('d/m/Y') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-cinza mb-2">Última atualização</label>
                                <p class="text-cinza-claro">{{ $user->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                </x-card>
            </div>
        </div>

        <!-- Aba Notificações -->
        <div class="tab-pane fade {{ request('tab') === 'notifications' ? 'show active' : '' }}" 
             id="notifications" role="tabpanel">
            <div class="space-y-6">
                <!-- Preferências de Notificação -->
                <x-card title="Preferências de Notificação">
                    <form method="POST" action="{{ route('profile.notifications.update') }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="space-y-4">
                            <!-- Notificações de Ticket Criado -->
                            <div class="notification-item">
                                <div class="notification-content">
                                    <div class="notification-icon">
                                        <svg class="w-5 h-5 text-roxo" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                    </div>
                                    <div class="notification-text">
                                        <h6 class="notification-title">Novos Tickets</h6>
                                        <p class="notification-description">Receba notificação quando um novo ticket for criado.</p>
                                    </div>
                                </div>
                                <div class="notification-toggle">
                                    <input type="checkbox" name="notify_ticket_created" id="notify_ticket_created" 
                                        value="1" {{ old('notify_ticket_created', $user->notify_ticket_created) ? 'checked' : '' }}
                                        class="toggle-input">
                                    <label for="notify_ticket_created" class="toggle-label"></label>
                                </div>
                            </div>

                            <!-- Notificações de Resposta -->
                            <div class="notification-item">
                                <div class="notification-content">
                                    <div class="notification-icon">
                                        <svg class="w-5 h-5 text-roxo" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                        </svg>
                                    </div>
                                    <div class="notification-text">
                                        <h6 class="notification-title">Respostas em Tickets</h6>
                                        <p class="notification-description">Receba notificação quando alguém responder a um ticket.</p>
                                    </div>
                                </div>
                                <div class="notification-toggle">
                                    <input type="checkbox" name="notify_ticket_replied" id="notify_ticket_replied" 
                                        value="1" {{ old('notify_ticket_replied', $user->notify_ticket_replied) ? 'checked' : '' }}
                                        class="toggle-input">
                                    <label for="notify_ticket_replied" class="toggle-label"></label>
                                </div>
                            </div>

                            <!-- Notificações de Mudança de Status -->
                            <div class="notification-item">
                                <div class="notification-content">
                                    <div class="notification-icon">
                                        <svg class="w-5 h-5 text-roxo" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="notification-text">
                                        <h6 class="notification-title">Mudança de Status</h6>
                                        <p class="notification-description">Receba notificação quando o status de um ticket for alterado.</p>
                                    </div>
                                </div>
                                <div class="notification-toggle">
                                    <input type="checkbox" name="notify_ticket_status_changed" id="notify_ticket_status_changed" 
                                        value="1" {{ old('notify_ticket_status_changed', $user->notify_ticket_status_changed) ? 'checked' : '' }}
                                        class="toggle-input">
                                    <label for="notify_ticket_status_changed" class="toggle-label"></label>
                                </div>
                            </div>

                            <!-- Notificações de Ticket Fechado -->
                            <div class="notification-item">
                                <div class="notification-content">
                                    <div class="notification-icon">
                                        <svg class="w-5 h-5 text-roxo" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <div class="notification-text">
                                        <h6 class="notification-title">Tickets Fechados</h6>
                                        <p class="notification-description">Receba notificação quando um ticket for fechado.</p>
                                    </div>
                                </div>
                                <div class="notification-toggle">
                                    <input type="checkbox" name="notify_ticket_closed" id="notify_ticket_closed" 
                                        value="1" {{ old('notify_ticket_closed', $user->notify_ticket_closed) ? 'checked' : '' }}
                                        class="toggle-input">
                                    <label for="notify_ticket_closed" class="toggle-label"></label>
                                </div>
                            </div>

                            <!-- Notificações de Mudança de Prioridade -->
                            <div class="notification-item">
                                <div class="notification-content">
                                    <div class="notification-icon">
                                        <svg class="w-5 h-5 text-roxo" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                    </div>
                                    <div class="notification-text">
                                        <h6 class="notification-title">Mudança de Prioridade</h6>
                                        <p class="notification-description">Receba notificação quando a prioridade de um ticket for alterada.</p>
                                    </div>
                                </div>
                                <div class="notification-toggle">
                                    <input type="checkbox" name="notify_ticket_priority_changed" id="notify_ticket_priority_changed" 
                                        value="1" {{ old('notify_ticket_priority_changed', $user->notify_ticket_priority_changed) ? 'checked' : '' }}
                                        class="toggle-input">
                                    <label for="notify_ticket_priority_changed" class="toggle-label"></label>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end pt-4 border-t border-cinza-claro">
                            <x-button variant="primary" type="submit">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Salvar Preferências
                            </x-button>
                        </div>
                    </form>
                </x-card>
            </div>
        </div>

        <!-- Aba Segurança -->
        <div class="tab-pane fade {{ request('tab') === 'security' ? 'show active' : '' }}" 
             id="security" role="tabpanel">
            <div class="space-y-6">
                <!-- Alterar Senha -->
                <x-card title="Alterar Senha">
                    <form method="POST" action="{{ route('profile.password') }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            <!-- Senha Atual -->
                            <div>
                                <x-input 
                                    label="Senha Atual"
                                    name="current_password"
                                    type="password"
                                    required
                                    error="{{ $errors->first('current_password') }}"
                                />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Nova Senha -->
                                <div>
                                    <x-input 
                                        label="Nova Senha"
                                        name="password"
                                        type="password"
                                        required
                                        error="{{ $errors->first('password') }}"
                                    />
                                </div>

                                <!-- Confirmar Nova Senha -->
                                <div>
                                    <x-input 
                                        label="Confirmar Nova Senha"
                                        name="password_confirmation"
                                        type="password"
                                        required
                                        error="{{ $errors->first('password_confirmation') }}"
                                    />
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end pt-4 border-t border-cinza-claro">
                            <x-button variant="primary" type="submit">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                Alterar Senha
                            </x-button>
                        </div>
                    </form>
                </x-card>

                <!-- Zona de Perigo -->
                <x-card class="border-vermelho border-opacity-25">
                    <div class="danger-zone-header">
                        <div class="danger-zone-icon">
                            <svg class="w-6 h-6 text-vermelho" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <div>
                            <h5 class="danger-zone-title">Zona de Perigo</h5>
                            <p class="danger-zone-subtitle">Ações irreversíveis que afetam sua conta</p>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="danger-zone-content">
                            <h6 class="font-medium text-cinza mb-2">Excluir Conta</h6>
                            <p class="text-cinza-claro mb-4">
                                Uma vez que sua conta for excluída, todos os seus recursos e dados serão permanentemente apagados. 
                                Antes de excluir sua conta, faça o download de quaisquer dados ou informações que deseja manter.
                            </p>
                            <div class="flex justify-end">
                                <x-button variant="outline" type="button" onclick="showDeleteModal()" class="danger-button">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Excluir Conta
                                </x-button>
                            </div>
                        </div>
                    </div>
                </x-card>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmação para Excluir Conta -->
<div id="deleteAccountModal" class="fixed inset-0 bg-cinza-escuro bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
        <div class="flex items-center justify-between mb-4">
            <h5 class="text-lg font-semibold text-vermelho">Excluir Conta</h5>
            <button type="button" onclick="hideDeleteModal()" class="text-cinza-claro hover:text-cinza">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <div class="space-y-4">
            <p class="text-cinza">
                Tem certeza que deseja excluir sua conta? Esta ação não pode ser desfeita.
            </p>
            
            <form method="POST" action="{{ route('profile.destroy') }}" class="space-y-4">
                @csrf
                @method('DELETE')
                
                <div>
                    <x-input 
                        label="Digite 'DELETE' para confirmar"
                        name="delete_confirmation"
                        placeholder="DELETE"
                        required
                    />
                </div>
                
                <div class="flex justify-end gap-3">
                    <x-button variant="outline" type="button" onclick="hideDeleteModal()">Cancelar</x-button>
                    <x-button variant="danger" type="submit">Excluir Conta</x-button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Tab Navigation */
.tab-link {
    @apply flex items-center px-1 py-4 text-sm font-medium border-b-2 border-transparent text-cinza-claro hover:text-cinza hover:border-cinza-claro transition-colors duration-200;
}

.tab-link.active {
    @apply text-roxo border-roxo;
}

.tab-link svg {
    @apply transition-transform duration-200;
}

.tab-link:hover svg {
    @apply scale-110;
}

/* Notification Items */
.notification-item {
    @apply flex items-center justify-between p-4 bg-cinza-claro-2 rounded-lg hover:bg-cinza-claro-2/80 transition-colors duration-200;
}

.notification-content {
    @apply flex items-center space-x-4 flex-1;
}

.notification-icon {
    @apply flex-shrink-0 w-10 h-10 bg-roxo/10 rounded-lg flex items-center justify-center;
}

.notification-text {
    @apply flex-1;
}

.notification-title {
    @apply font-medium text-cinza mb-1;
}

.notification-description {
    @apply text-sm text-cinza-claro;
}

/* Toggle Switch */
.notification-toggle {
    @apply flex items-center;
}

.toggle-input {
    @apply sr-only;
}

.toggle-label {
    @apply relative inline-flex h-6 w-11 items-center rounded-full bg-cinza-claro transition-colors duration-200 cursor-pointer;
}

.toggle-label:before {
    @apply absolute left-1 top-1 h-4 w-4 rounded-full bg-white shadow-sm transition-transform duration-200;
    content: '';
}

.toggle-input:checked + .toggle-label {
    @apply bg-roxo;
}

.toggle-input:checked + .toggle-label:before {
    @apply transform translate-x-5;
}

/* Danger Zone */
.danger-zone-header {
    @apply flex items-center space-x-3 p-4 bg-vermelho/10 border border-vermelho/25 rounded-lg mb-4;
}

.danger-zone-icon {
    @apply flex-shrink-0;
}

.danger-zone-title {
    @apply font-semibold text-vermelho text-lg;
}

.danger-zone-subtitle {
    @apply text-sm text-vermelho/80;
}

.danger-zone-content {
    @apply p-4;
}

.danger-button {
    @apply text-vermelho border-vermelho hover:bg-vermelho hover:text-white transition-colors duration-200;
}

/* Modal Improvements */
#deleteAccountModal {
    @apply backdrop-blur-sm;
}

#deleteAccountModal .bg-white {
    @apply shadow-2xl border border-cinza-claro;
}
</style>
@endpush

@push('scripts')
<script>
function showDeleteModal() {
    document.getElementById('deleteAccountModal').classList.remove('hidden');
    document.getElementById('deleteAccountModal').classList.add('flex');
}

function hideDeleteModal() {
    document.getElementById('deleteAccountModal').classList.add('hidden');
    document.getElementById('deleteAccountModal').classList.remove('flex');
}
</script>
@endpush