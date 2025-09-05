@extends('layouts.app')

@section('title', 'Configurações de Email')

@section('header')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between">
    <div class="flex-grow-1">
        <h2 class="fs-2 fw-bold text-dark mb-1">
            Configurações de Email
        </h2>
        <p class="text-muted">
            Configure o servidor SMTP e configurações de envio de emails
        </p>
    </div>
    <div class="mt-3 mt-md-0">
        <a href="{{ route('admin.settings.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center">
            <svg class="me-2" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Voltar
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="row g-4">
    <div class="col-12 col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0 fw-semibold">Configurações SMTP</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.settings.email.update') }}" class="d-flex flex-column gap-4">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <!-- Email de Remetente -->
                        <div class="col-12 col-md-6">
                            <label for="mail_from_address" class="form-label fw-medium text-dark">Email de Remetente *</label>
                            <input type="email" id="mail_from_address" name="mail_from_address" 
                                class="form-control @error('mail_from_address') is-invalid @enderror"
                                value="{{ old('mail_from_address', $settings['mail_from_address']) }}" required>
                            @error('mail_from_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nome do Remetente -->
                        <div class="col-12 col-md-6">
                            <label for="mail_from_name" class="form-label fw-medium text-dark">Nome do Remetente *</label>
                            <input type="text" id="mail_from_name" name="mail_from_name" 
                                class="form-control @error('mail_from_name') is-invalid @enderror"
                                value="{{ old('mail_from_name', $settings['mail_from_name']) }}" required>
                            @error('mail_from_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Servidor SMTP -->
                        <div class="col-12 col-md-6">
                            <label for="mail_smtp_host" class="form-label fw-medium text-dark">Servidor SMTP *</label>
                            <input type="text" id="mail_smtp_host" name="mail_smtp_host" 
                                class="form-control @error('mail_smtp_host') is-invalid @enderror"
                                value="{{ old('mail_smtp_host', $settings['mail_smtp_host']) }}" required>
                            @error('mail_smtp_host')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Porta SMTP -->
                        <div class="col-12 col-md-6">
                            <label for="mail_smtp_port" class="form-label fw-medium text-dark">Porta SMTP *</label>
                            <input type="number" id="mail_smtp_port" name="mail_smtp_port" 
                                class="form-control @error('mail_smtp_port') is-invalid @enderror"
                                value="{{ old('mail_smtp_port', $settings['mail_smtp_port']) }}" required>
                            @error('mail_smtp_port')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Usuário SMTP -->
                        <div class="col-12 col-md-6">
                            <label for="mail_smtp_username" class="form-label fw-medium text-dark">Usuário SMTP *</label>
                            <input type="text" id="mail_smtp_username" name="mail_smtp_username" 
                                class="form-control @error('mail_smtp_username') is-invalid @enderror"
                                value="{{ old('mail_smtp_username', $settings['mail_smtp_username']) }}" required>
                            @error('mail_smtp_username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Senha/Chave de Aplicativo SMTP -->
                        <div class="col-12 col-md-6">
                            <label for="mail_smtp_password" class="form-label fw-medium text-dark">
                                Senha/Chave de Aplicativo SMTP *
                                <span class="badge bg-info ms-1" data-bs-toggle="tooltip" title="Para Gmail, use uma Chave de Aplicativo em vez da senha normal">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </span>
                            </label>
                            <input type="password" id="mail_smtp_password" name="mail_smtp_password" 
                                class="form-control @error('mail_smtp_password') is-invalid @enderror"
                                value="{{ old('mail_smtp_password', $settings['mail_smtp_password']) }}" required
                                placeholder="Para Gmail: use Chave de Aplicativo (16 caracteres)">
                            <div class="form-text">
                                <strong>Gmail:</strong> Use uma <a href="https://myaccount.google.com/apppasswords" target="_blank" class="text-decoration-none">Chave de Aplicativo</a> (16 caracteres)
                            </div>
                            @error('mail_smtp_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Criptografia -->
                        <div class="col-12">
                            <label for="mail_smtp_encryption" class="form-label fw-medium text-dark">Criptografia *</label>
                            <select id="mail_smtp_encryption" name="mail_smtp_encryption" 
                                class="form-select @error('mail_smtp_encryption') is-invalid @enderror" required>
                                <option value="tls" {{ old('mail_smtp_encryption', $settings['mail_smtp_encryption']) == 'tls' ? 'selected' : '' }}>
                                    TLS (Recomendado)
                                </option>
                                <option value="ssl" {{ old('mail_smtp_encryption', $settings['mail_smtp_encryption']) == 'ssl' ? 'selected' : '' }}>
                                    SSL
                                </option>
                                <option value="none" {{ old('mail_smtp_encryption', $settings['mail_smtp_encryption']) == 'none' ? 'selected' : '' }}>
                                    Nenhuma
                                </option>
                            </select>
                            @error('mail_smtp_encryption')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-outline-info" onclick="testConnection()">
                            <svg class="me-2" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            Testar Conexão
                        </button>
                        <button type="submit" class="btn btn-primary d-inline-flex align-items-center">
                            <svg class="me-2" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Salvar Configurações
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0 fw-semibold">Configurações Populares</h5>
            </div>
            <div class="card-body">
                <div class="d-flex flex-column gap-3">
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="setGmailConfig()">
                        <svg class="me-2" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Configurar Gmail
                    </button>
                    <button type="button" class="btn btn-outline-info btn-sm" onclick="setOutlookConfig()">
                        <svg class="me-2" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Configurar Outlook
                    </button>
                    <button type="button" class="btn btn-outline-warning btn-sm" onclick="setSendGridConfig()">
                        <svg class="me-2" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Configurar SendGrid
                    </button>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0 fw-semibold">Status da Conexão</h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                        <svg class="text-success" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="fw-medium text-dark">Conexão Ativa</div>
                        <small class="text-muted">SMTP configurado corretamente</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0 fw-semibold">Informações Importantes</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h6 class="alert-heading fw-semibold">
                        <svg class="me-2" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Gmail - Chave de Aplicativo
                    </h6>
                    <p class="mb-2">O Google não permite mais usar senhas normais para aplicativos. Você deve usar uma <strong>Chave de Aplicativo</strong>:</p>
                    <ol class="mb-2 small">
                        <li>Acesse <a href="https://myaccount.google.com/apppasswords" target="_blank" class="text-decoration-none">Google App Passwords</a></li>
                        <li>Selecione "Mail" e "Outro (nome personalizado)"</li>
                        <li>Digite "Sistema de Tickets" como nome</li>
                        <li>Copie a chave de 16 caracteres gerada</li>
                        <li>Cole no campo "Senha SMTP" acima</li>
                    </ol>
                    <small class="text-muted">
                        <strong>Dica:</strong> A chave de aplicativo é única e não pode ser recuperada. Guarde-a em local seguro!
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Inicializar tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

function testConnection() {
    // Implementar teste de conexão SMTP
    alert('Funcionalidade de teste de conexão será implementada em breve!');
}

function setGmailConfig() {
    document.getElementById('mail_smtp_host').value = 'smtp.gmail.com';
    document.getElementById('mail_smtp_port').value = '587';
    document.getElementById('mail_smtp_encryption').value = 'tls';
    
    // Mostrar aviso sobre chave de aplicativo
    const passwordField = document.getElementById('mail_smtp_password');
    passwordField.placeholder = 'Use uma Chave de Aplicativo do Google (16 caracteres)';
    passwordField.focus();
    
    // Destacar o campo de senha
    passwordField.classList.add('border-warning');
    setTimeout(() => {
        passwordField.classList.remove('border-warning');
    }, 3000);
}

function setOutlookConfig() {
    document.getElementById('mail_smtp_host').value = 'smtp-mail.outlook.com';
    document.getElementById('mail_smtp_port').value = '587';
    document.getElementById('mail_smtp_encryption').value = 'tls';
    document.getElementById('mail_smtp_password').placeholder = 'Digite sua senha do Outlook';
}

function setSendGridConfig() {
    document.getElementById('mail_smtp_host').value = 'smtp.sendgrid.net';
    document.getElementById('mail_smtp_port').value = '587';
    document.getElementById('mail_smtp_encryption').value = 'tls';
    document.getElementById('mail_smtp_password').placeholder = 'Digite sua chave API do SendGrid';
}
</script>
@endpush
