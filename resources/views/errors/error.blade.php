@extends('layouts.app')

@section('title', 'Erro')

@section('content')
<div class="container-fluid d-flex align-items-center justify-content-center min-vh-100">
    <div class="row w-100">
        <div class="col-12 text-center">
            <!-- Código de Erro -->
            <div class="error-code mb-4">
                <h1 class="display-1 fw-bold text-primary">{{ $exception->getStatusCode() ?? 'Erro' }}</h1>
            </div>
            
            <!-- Ícone -->
            <div class="error-icon mb-4">
                <svg width="120" height="120" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="text-muted">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            
            <!-- Título -->
            <h2 class="h3 fw-bold text-dark mb-3">
                @switch($exception->getStatusCode())
                    @case(400)
                        Solicitação Inválida
                        @break
                    @case(401)
                        Não Autorizado
                        @break
                    @case(402)
                        Pagamento Necessário
                        @break
                    @case(404)
                        Página Não Encontrada
                        @break
                    @case(405)
                        Método Não Permitido
                        @break
                    @case(408)
                        Tempo Esgotado
                        @break
                    @case(422)
                        Dados Inválidos
                        @break
                    @case(429)
                        Muitas Solicitações
                        @break
                    @case(500)
                        Erro Interno do Servidor
                        @break
                    @case(502)
                        Gateway Inválido
                        @break
                    @case(503)
                        Serviço Indisponível
                        @break
                    @case(504)
                        Gateway Timeout
                        @break
                    @default
                        Ocorreu um Erro
                @endswitch
            </h2>
            
            <!-- Descrição -->
            <p class="text-muted mb-4 fs-5">
                @switch($exception->getStatusCode())
                    @case(400)
                        A solicitação contém dados inválidos ou malformados.
                        @break
                    @case(401)
                        Você precisa fazer login para acessar este recurso.
                        @break
                    @case(402)
                        É necessário realizar um pagamento para acessar este recurso.
                        @break
                    @case(404)
                        A página que você está procurando não foi encontrada.
                        @break
                    @case(405)
                        O método de requisição não é permitido para este recurso.
                        @break
                    @case(408)
                        A solicitação demorou muito para ser processada.
                        @break
                    @case(422)
                        Os dados enviados não são válidos.
                        @break
                    @case(429)
                        Muitas solicitações foram feitas. Tente novamente mais tarde.
                        @break
                    @case(500)
                        Ocorreu um erro interno no servidor.
                        @break
                    @case(502)
                        O servidor recebeu uma resposta inválida de outro servidor.
                        @break
                    @case(503)
                        O serviço está temporariamente indisponível.
                        @break
                    @case(504)
                        O servidor não conseguiu responder a tempo.
                        @break
                    @default
                        Ocorreu um erro inesperado. Nossa equipe foi notificada.
                @endswitch
            </p>
            
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
                
                <button onclick="location.reload()" class="btn btn-outline-primary btn-lg px-4">
                    <svg class="me-2" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Tentar Novamente
                </button>
            </div>
            
            <!-- Informações de Suporte -->
            <div class="mt-5 pt-4 border-top">
                <p class="text-muted small mb-0">
                    <strong>Código do Erro:</strong> {{ $exception->getStatusCode() }} | 
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
