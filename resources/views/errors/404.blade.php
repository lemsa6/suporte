@extends('layouts.app')

@section('title', 'Página Não Encontrada')

@section('content')
<div class="container-fluid d-flex align-items-center justify-content-center min-vh-100">
    <div class="row w-100">
        <div class="col-12 text-center">
            <!-- Código de Erro -->
            <div class="error-code mb-4">
                <h1 class="display-1 fw-bold text-primary">404</h1>
            </div>
            
            <!-- Ícone -->
            <div class="error-icon mb-4">
                <svg width="120" height="120" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="text-muted">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.29-1.009-5.824-2.709M15 6.291A7.962 7.962 0 0112 4c-2.34 0-4.29 1.009-5.824 2.709M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2z"></path>
                </svg>
            </div>
            
            <!-- Título -->
            <h2 class="h3 fw-bold text-dark mb-3">Página Não Encontrada</h2>
            
            <!-- Descrição -->
            <p class="text-muted mb-4 fs-5">
                A página que você está procurando não existe ou foi movida.
            </p>
            
            <!-- Informações Adicionais -->
            <div class="alert alert-info border-0 bg-light mb-4 mx-auto" style="max-width: 500px;">
                <div class="d-flex align-items-center">
                    <svg class="me-3" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="text-start">
                        <strong>Possíveis causas:</strong>
                        <ul class="mb-0 mt-2 small">
                            <li>URL digitada incorretamente</li>
                            <li>Página foi movida ou removida</li>
                            <li>Link desatualizado</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Ações -->
            <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg px-4">
                    <svg class="me-2" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Ir para Dashboard
                </a>
                
                <button onclick="history.back()" class="btn btn-outline-secondary btn-lg px-4">
                    <svg class="me-2" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Voltar
                </button>
                
                <a href="{{ route('tickets.index') }}" class="btn btn-outline-primary btn-lg px-4">
                    <svg class="me-2" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Ver Tickets
                </a>
            </div>
            
            <!-- Informações de Suporte -->
            <div class="mt-5 pt-4 border-top">
                <p class="text-muted small mb-0">
                    Se você acredita que isso é um erro, entre em contato com o suporte técnico.
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
    opacity: 0.6;
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
