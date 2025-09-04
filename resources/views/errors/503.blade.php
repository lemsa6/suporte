@extends('layouts.app')

@section('title', 'Serviço Temporariamente Indisponível')

@section('content')
<div class="container-fluid d-flex align-items-center justify-content-center min-vh-100">
    <div class="row w-100">
        <div class="col-12 text-center">
            <!-- Código de Erro -->
            <div class="error-code mb-4">
                <h1 class="display-1 fw-bold text-secondary">503</h1>
            </div>
            
            <!-- Ícone -->
            <div class="error-icon mb-4">
                <svg width="120" height="120" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="text-secondary">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                </svg>
            </div>
            
            <!-- Título -->
            <h2 class="h3 fw-bold text-dark mb-3">Serviço Temporariamente Indisponível</h2>
            
            <!-- Descrição -->
            <p class="text-muted mb-4 fs-5">
                Estamos realizando manutenção no sistema. Voltaremos em breve!
            </p>
            
            <!-- Informações Adicionais -->
            <div class="alert alert-secondary border-0 bg-light mb-4 mx-auto" style="max-width: 500px;">
                <div class="d-flex align-items-center">
                    <svg class="me-3" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="text-start">
                        <strong>O que está acontecendo:</strong>
                        <ul class="mb-0 mt-2 small">
                            <li>Manutenção programada em andamento</li>
                            <li>Atualização de sistema</li>
                            <li>Melhorias de performance</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Tempo Estimado -->
            <div class="card border-0 bg-primary bg-opacity-10 mb-4 mx-auto" style="max-width: 400px;">
                <div class="card-body text-center">
                    <h6 class="card-title text-primary fw-bold">Tempo Estimado</h6>
                    <p class="card-text text-muted mb-0">
                        <svg class="me-2" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Aproximadamente 15-30 minutos
                    </p>
                </div>
            </div>
            
            <!-- Ações -->
            <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                <button onclick="location.reload()" class="btn btn-primary btn-lg px-4">
                    <svg class="me-2" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Tentar Novamente
                </button>
                
                <a href="mailto:suporte@8bits.pro" class="btn btn-outline-secondary btn-lg px-4">
                    <svg class="me-2" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    Contatar Suporte
                </a>
            </div>
            
            <!-- Informações de Suporte -->
            <div class="mt-5 pt-4 border-top">
                <p class="text-muted small mb-0">
                    <strong>Horário da Manutenção:</strong> {{ now()->format('d/m/Y H:i') }} | 
                    <strong>Status:</strong> Em andamento
                </p>
            </div>
        </div>
    </div>
</div>

<style>
.min-vh-100 {
    min-height: 100vh;
}

.error-code h1 {
    font-size: 8rem;
    line-height: 1;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
}

.error-icon svg {
    opacity: 0.8;
}

@media (max-width: 768px) {
    .error-code h1 {
        font-size: 6rem;
    }
    
    .error-icon svg {
        width: 80px;
        height: 80px;
    }
}
</style>
@endsection
