@extends('layouts.app')

@section('title', 'Erro Interno do Servidor')

@section('content')
<div class="container-fluid d-flex align-items-center justify-content-center min-vh-100">
    <div class="row w-100">
        <div class="col-12 text-center">
            <!-- Código de Erro -->
            <div class="error-code mb-4">
                <h1 class="display-1 fw-bold text-danger">500</h1>
            </div>
            
            <!-- Ícone -->
            <div class="error-icon mb-4">
                <svg width="120" height="120" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="text-danger">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            
            <!-- Título -->
            <h2 class="h3 fw-bold text-dark mb-3">Erro Interno do Servidor</h2>
            
            <!-- Descrição -->
            <p class="text-muted mb-4 fs-5">
                Ocorreu um erro interno no servidor. Nossa equipe foi notificada e está trabalhando para resolver o problema.
            </p>
            
            <!-- Informações Adicionais -->
            <div class="alert alert-warning border-0 bg-light mb-4 mx-auto error-alert">
                <div class="d-flex align-items-center">
                    <svg class="me-3" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="text-start">
                        <strong>O que fazer agora:</strong>
                        <ul class="mb-0 mt-2 small">
                            <li>Tente recarregar a página em alguns minutos</li>
                            <li>Verifique sua conexão com a internet</li>
                            <li>Se o problema persistir, entre em contato conosco</li>
                        </ul>
                    </div>
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
                
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-lg px-4">
                    <svg class="me-2" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Ir para Dashboard
                </a>
                
                <a href="mailto:suporte@8bits.pro" class="btn btn-outline-danger btn-lg px-4">
                    <svg class="me-2" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    Reportar Problema
                </a>
            </div>
            
            <!-- Informações de Suporte -->
            <div class="mt-5 pt-4 border-top">
                <p class="text-muted small mb-0">
                    <strong>ID do Erro:</strong> {{ uniqid() }} | 
                    <strong>Horário:</strong> {{ now()->format('d/m/Y H:i:s') }}
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
