@extends('layouts.app')

@section('title', 'Editar Perfil')

@section('header')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between">
    <div class="flex-grow-1">
        <h2 class="fs-2 fw-bold text-dark mb-1">
            Editar Perfil
        </h2>
        <p class="text-muted">
            Atualize suas informações pessoais e configurações da conta
        </p>
    </div>
</div>
@endsection

@section('content')
<div class="d-flex flex-column gap-4">
    <!-- Navegação por Abas -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0">
            <ul class="nav nav-tabs nav-tabs-custom border-0" id="profileTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ !request('tab') || request('tab') === 'profile' ? 'active' : '' }}" 
                            id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab">
                        <svg class="me-2" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Perfil
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ request('tab') === 'notifications' ? 'active' : '' }}" 
                            id="notifications-tab" data-bs-toggle="tab" data-bs-target="#notifications" type="button" role="tab">
                        <svg class="me-2" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h6v-6H4v6zM4 5h6V1H4v4zM15 3h5l-5-5v5z"></path>
                        </svg>
                        Notificações
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ request('tab') === 'security' ? 'active' : '' }}" 
                            id="security-tab" data-bs-toggle="tab" data-bs-target="#security" type="button" role="tab">
                        <svg class="me-2" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        Segurança
                    </button>
                </li>
            </ul>
        </div>
    </div>

    <!-- Conteúdo das Abas -->
    <div class="tab-content" id="profileTabsContent">
        <!-- Aba Perfil -->
        <div class="tab-pane fade {{ !request('tab') || request('tab') === 'profile' ? 'show active' : '' }}" 
             id="profile" role="tabpanel">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0">
            <h5 class="mb-0 fw-semibold">Informações do Perfil</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('profile.update') }}" class="d-flex flex-column gap-4">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <!-- Nome -->
                    <div class="col-12 col-sm-6">
                        <label for="name" class="form-label fw-medium text-dark">Nome Completo</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" 
                            class="form-control @error('name') is-invalid @enderror" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="col-12 col-sm-6">
                        <label for="email" class="form-label fw-medium text-dark">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" 
                            class="form-control @error('email') is-invalid @enderror" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                            <!-- Fuso Horário -->
                    <div class="col-12 col-sm-6">
                                <label for="timezone" class="form-label fw-medium text-dark">Fuso Horário</label>
                                <select name="timezone" id="timezone" class="form-select @error('timezone') is-invalid @enderror">
                                    @foreach($timezones as $value => $label)
                                        <option value="{{ $value }}" {{ old('timezone', $user->timezone) == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('timezone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                            <!-- Idioma -->
                    <div class="col-12 col-sm-6">
                                <label for="language" class="form-label fw-medium text-dark">Idioma</label>
                                <select name="language" id="language" class="form-select @error('language') is-invalid @enderror">
                                    <option value="pt_BR" {{ old('language', $user->language) == 'pt_BR' ? 'selected' : '' }}>Português (Brasil)</option>
                                    <option value="en" {{ old('language', $user->language) == 'en' ? 'selected' : '' }}>English</option>
                                    <option value="es" {{ old('language', $user->language) == 'es' ? 'selected' : '' }}>Español</option>
                                </select>
                                @error('language')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary d-inline-flex align-items-center">
                                <svg class="me-2" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Salvar Perfil
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Aba Notificações -->
        <div class="tab-pane fade {{ request('tab') === 'notifications' ? 'show active' : '' }}" 
             id="notifications" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0 fw-semibold">Preferências de Notificação</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.notifications.update') }}" class="d-flex flex-column gap-4">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <!-- Notificações de Ticket Criado -->
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="notify_ticket_created" id="notify_ticket_created" 
                                        value="1" {{ old('notify_ticket_created', $user->notify_ticket_created) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-medium text-dark" for="notify_ticket_created">
                                        <strong>Novos Tickets</strong>
                                    </label>
                                    <div class="form-text">Receba notificação quando um novo ticket for criado.</div>
                                </div>
                            </div>

                            <!-- Notificações de Resposta -->
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="notify_ticket_replied" id="notify_ticket_replied" 
                                        value="1" {{ old('notify_ticket_replied', $user->notify_ticket_replied) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-medium text-dark" for="notify_ticket_replied">
                                        <strong>Respostas em Tickets</strong>
                                    </label>
                                    <div class="form-text">Receba notificação quando alguém responder a um ticket.</div>
                                </div>
                            </div>

                            <!-- Notificações de Mudança de Status -->
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="notify_ticket_status_changed" id="notify_ticket_status_changed" 
                                        value="1" {{ old('notify_ticket_status_changed', $user->notify_ticket_status_changed) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-medium text-dark" for="notify_ticket_status_changed">
                                        <strong>Mudança de Status</strong>
                                    </label>
                                    <div class="form-text">Receba notificação quando o status de um ticket for alterado.</div>
                                </div>
                            </div>

                            <!-- Notificações de Ticket Fechado -->
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="notify_ticket_closed" id="notify_ticket_closed" 
                                        value="1" {{ old('notify_ticket_closed', $user->notify_ticket_closed) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-medium text-dark" for="notify_ticket_closed">
                                        <strong>Tickets Fechados</strong>
                                    </label>
                                    <div class="form-text">Receba notificação quando um ticket for fechado.</div>
                                </div>
                    </div>

                            <!-- Notificações de Mudança de Prioridade -->
                    <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="notify_ticket_priority_changed" id="notify_ticket_priority_changed" 
                                        value="1" {{ old('notify_ticket_priority_changed', $user->notify_ticket_priority_changed) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-medium text-dark" for="notify_ticket_priority_changed">
                                        <strong>Mudança de Prioridade</strong>
                                    </label>
                                    <div class="form-text">Receba notificação quando a prioridade de um ticket for alterada.</div>
                                </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary d-inline-flex align-items-center">
                        <svg class="me-2" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                                Salvar Preferências
                    </button>
                </div>
            </form>
                </div>
        </div>
    </div>

        <!-- Aba Segurança -->
        <div class="tab-pane fade {{ request('tab') === 'security' ? 'show active' : '' }}" 
             id="security" role="tabpanel">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0">
            <h5 class="mb-0 fw-semibold">Alterar Senha</h5>
        </div>
        <div class="card-body">
                    <form method="POST" action="{{ route('profile.password.update') }}" class="d-flex flex-column gap-4">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <!-- Senha Atual -->
                            <div class="col-12">
                        <label for="current_password" class="form-label fw-medium text-dark">Senha Atual</label>
                        <div class="input-group">
                            <input type="password" name="current_password" id="current_password" 
                                class="form-control @error('current_password') is-invalid @enderror" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('current_password')">
                                        <svg class="password-icon" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Nova Senha -->
                    <div class="col-12 col-sm-6">
                        <label for="password" class="form-label fw-medium text-dark">Nova Senha</label>
                        <div class="input-group">
                            <input type="password" name="password" id="password" 
                                class="form-control @error('password') is-invalid @enderror" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                        <svg class="password-icon" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Confirmar Nova Senha -->
                    <div class="col-12 col-sm-6">
                        <label for="password_confirmation" class="form-label fw-medium text-dark">Confirmar Nova Senha</label>
                        <div class="input-group">
                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                class="form-control @error('password_confirmation') is-invalid @enderror" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation')">
                                        <svg class="password-icon" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary d-inline-flex align-items-center">
                        <svg class="me-2" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        Alterar Senha
                    </button>
                </div>
            </form>
        </div>
    </div>

            <!-- Zona de Perigo -->
            <div class="card border-danger border-opacity-25 mt-4">
        <div class="card-header bg-danger bg-opacity-10 border-danger border-opacity-25">
            <h5 class="mb-0 fw-semibold text-danger">Zona de Perigo</h5>
        </div>
        <div class="card-body">
            <div class="d-flex flex-column gap-3">
                <div>
                    <h6 class="fw-medium text-dark">Excluir Conta</h6>
                    <p class="text-muted mb-0">
                        Uma vez que sua conta for excluída, todos os seus recursos e dados serão permanentemente apagados. 
                        Antes de excluir sua conta, faça o download de quaisquer dados ou informações que deseja manter.
                    </p>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                        <svg class="me-2" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Excluir Conta
                    </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmação para Excluir Conta -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title text-danger" id="deleteAccountModalLabel">Excluir Conta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-3">
                    Tem certeza que deseja excluir sua conta? Esta ação não pode ser desfeita.
                </p>
                <form method="POST" action="{{ route('profile.destroy') }}" class="d-flex flex-column gap-3">
                    @csrf
                    @method('DELETE')
                    
                    <div>
                        <label for="delete_confirmation" class="form-label fw-medium text-dark">
                            Digite "DELETE" para confirmar
                        </label>
                        <input type="text" name="delete_confirmation" id="delete_confirmation" 
                            class="form-control" placeholder="DELETE" required>
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Excluir Conta</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.nav-tabs-custom .nav-link {
    border: none;
    border-bottom: 2px solid transparent;
    color: #6c757d;
    font-weight: 500;
    padding: 12px 20px;
    transition: all 0.3s ease;
}

.nav-tabs-custom .nav-link:hover {
    border-color: #dee2e6;
    color: #495057;
}

.nav-tabs-custom .nav-link.active {
    border-color: #667eea;
    color: #667eea;
    background: none;
}

.nav-tabs-custom .nav-link svg {
    transition: transform 0.3s ease;
}

.nav-tabs-custom .nav-link:hover svg {
    transform: scale(1.1);
}
</style>
@endpush

@push('scripts')
<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = field.nextElementSibling.querySelector('svg');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>';
    } else {
        field.type = 'password';
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
    }
}
</script>
@endpush