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
    <!-- Informações do Perfil -->
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

                    <!-- Telefone -->
                    <div class="col-12 col-sm-6">
                        <label for="phone" class="form-label fw-medium text-dark">Telefone</label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" 
                            class="form-control @error('phone') is-invalid @enderror">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Cargo -->
                    <div class="col-12 col-sm-6">
                        <label for="position" class="form-label fw-medium text-dark">Cargo</label>
                        <input type="text" name="position" id="position" value="{{ old('position', $user->position) }}" 
                            class="form-control @error('position') is-invalid @enderror">
                        @error('position')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Departamento -->
                    <div class="col-12 col-sm-6">
                        <label for="department" class="form-label fw-medium text-dark">Departamento</label>
                        <input type="text" name="department" id="department" value="{{ old('department', $user->department) }}" 
                            class="form-control @error('department') is-invalid @enderror">
                        @error('department')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Bio -->
                    <div class="col-12">
                        <label for="bio" class="form-label fw-medium text-dark">Biografia</label>
                        <textarea name="bio" id="bio" rows="3" 
                            class="form-control @error('bio') is-invalid @enderror">{{ old('bio', $user->bio) }}</textarea>
                        @error('bio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Uma breve descrição sobre você e suas responsabilidades.</div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary d-inline-flex align-items-center">
                        <svg class="me-2" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Atualizar Perfil
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Alterar Senha -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0">
            <h5 class="mb-0 fw-semibold">Alterar Senha</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('profile.password') }}" class="d-flex flex-column gap-4">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <!-- Senha Atual -->
                    <div class="col-12 col-sm-6">
                        <label for="current_password" class="form-label fw-medium text-dark">Senha Atual</label>
                        <div class="input-group">
                            <input type="password" name="current_password" id="current_password" 
                                class="form-control @error('current_password') is-invalid @enderror" required>
                            <button class="btn btn-outline-secondary" type="button" id="toggleCurrentPassword">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                            <button class="btn btn-outline-secondary" type="button" id="toggleNewPassword">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                            <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    <button type="submit" class="btn btn-warning d-inline-flex align-items-center">
                        <svg class="me-2" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        Alterar Senha
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Configurações da Conta -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0">
            <h5 class="mb-0 fw-semibold">Configurações da Conta</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('profile.settings') }}" class="d-flex flex-column gap-4">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <!-- Notificações por Email -->
                    <div class="col-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="email_notifications" id="email_notifications" 
                                value="1" {{ old('email_notifications', $user->email_notifications) ? 'checked' : '' }}>
                            <label class="form-check-label fw-medium text-dark" for="email_notifications">
                                Receber notificações por email
                            </label>
                            <div class="form-text">Receba atualizações sobre tickets e atividades por email.</div>
                        </div>
                    </div>

                    <!-- Notificações Push -->
                    <div class="col-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="push_notifications" id="push_notifications" 
                                value="1" {{ old('push_notifications', $user->push_notifications) ? 'checked' : '' }}>
                            <label class="form-check-label fw-medium text-dark" for="push_notifications">
                                Receber notificações push
                            </label>
                            <div class="form-text">Receba notificações em tempo real no navegador.</div>
                        </div>
                    </div>

                    <!-- Fuso Horário -->
                    <div class="col-12 col-sm-6">
                        <label for="timezone" class="form-label fw-medium text-dark">Fuso Horário</label>
                        <select name="timezone" id="timezone" class="form-select @error('timezone') is-invalid @enderror">
                            @foreach($timezones as $timezone)
                                <option value="{{ $timezone }}" {{ old('timezone', $user->timezone) == $timezone ? 'selected' : '' }}>
                                    {{ $timezone }}
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
                    <button type="submit" class="btn btn-info d-inline-flex align-items-center">
                        <svg class="me-2" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Salvar Configurações
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Excluir Conta -->
    <div class="card border-danger border-opacity-25">
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

@push('scripts')
<script>
// Toggle password visibility
document.getElementById('toggleCurrentPassword').addEventListener('click', function() {
    togglePasswordVisibility('current_password', this);
});

document.getElementById('toggleNewPassword').addEventListener('click', function() {
    togglePasswordVisibility('password', this);
});

document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
    togglePasswordVisibility('password_confirmation', this);
});

function togglePasswordVisibility(inputId, button) {
    const input = document.getElementById(inputId);
    const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
    input.setAttribute('type', type);
    
    // Change icon
    const icon = button.querySelector('svg');
    if (type === 'text') {
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>';
    } else {
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
    }
}
</script>
@endpush
@endsection
