@extends('layouts.app')

@section('title', 'Acesso Negado')

@section('content')
<div class="container-fluid d-flex align-items-center justify-content-center min-vh-100">
    <div class="row w-100">
        <div class="col-12 text-center">
            <!-- Código de Erro -->
            <div class="error-code mb-4">
                <h1 class="display-1 fw-bold text-warning">403</h1>
            </div>
            
            <!-- Ícone -->
            <div class="error-icon mb-4">
                <svg width="120" height="120" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="text-warning">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            
            <!-- Título -->
            <h2 class="h3 fw-bold text-dark mb-3">Acesso Negado</h2>
            
            <!-- Descrição -->
            <p class="text-muted mb-4 fs-5">
                Você não tem permissão para acessar esta página ou recurso.
            </p>
            
            <!-- Informações Adicionais -->
            <div class="alert alert-warning border-0 bg-light mb-4 mx-auto" style="max-width: 500px;">
                <div class="d-flex align-items-center">
                    <svg class="me-3" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="text-start">
                        <strong>Possíveis motivos:</strong>
                        <ul class="mb-0 mt-2 small">
                            <li>Você não tem permissão para acessar este recurso</li>
                            <li>Sua sessão pode ter expirado</li>
                            <li>É necessário fazer login novamente</li>
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
                
                <a href="{{ route('login') }}" class="btn btn-outline-warning btn-lg px-4">
                    <svg class="me-2" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                    Fazer Login
                </a>
            </div>
            
            <!-- Informações de Suporte -->
            <div class="mt-5 pt-4 border-top">
                <p class="text-muted small mb-0">
                    Se você acredita que deveria ter acesso a esta página, entre em contato com o administrador.
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
