@extends('layouts.app')

@section('title', 'Erro')

@section('content')
    <div class="min-h-screen bg-creme flex items-center justify-center px-4">
    <div class="max-w-2xl w-full text-center">
        <!-- Código de Erro -->
        <div class="mb-8">
            <h1 class="text-6xl font-bold text-roxo mb-4">{{ $exception->getStatusCode() ?? 'Erro' }}</h1>
        </div>
        
        <!-- Ícone -->
        <div class="mb-8">
            <svg width="120" height="120" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="text-cinza-claro mx-auto">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
        </div>
        
        <!-- Título -->
        <div class="mb-6">
            <h2 class="text-2xl font-semibold text-cinza mb-4">
                @if($exception->getStatusCode() == 404)
                    Página não encontrada
                @elseif($exception->getStatusCode() == 403)
                    Acesso negado
                @elseif($exception->getStatusCode() == 500)
                    Erro interno do servidor
                @elseif($exception->getStatusCode() == 503)
                    Serviço indisponível
                @else
                    Ocorreu um erro
                @endif
            </h2>
            
            <p class="text-cinza-claro text-lg mb-4">
                @if($exception->getStatusCode() == 404)
                    A página que você está procurando não existe ou foi movida.
                @elseif($exception->getStatusCode() == 403)
                    Você não tem permissão para acessar esta página.
                @elseif($exception->getStatusCode() == 500)
                    Ocorreu um erro interno no servidor. Nossa equipe foi notificada.
                @elseif($exception->getStatusCode() == 503)
                    O serviço está temporariamente indisponível. Tente novamente em alguns minutos.
                @else
                    Algo deu errado. Por favor, tente novamente.
                @endif
            </p>
        </div>

        <!-- Detalhes do Erro (apenas em desenvolvimento) -->
        @if(config('app.debug') && $exception)
            <div class="mb-8">
                <details class="text-left bg-cinza-claro-2 rounded-lg p-4">
                    <summary class="cursor-pointer text-cinza font-medium mb-2">Detalhes do Erro (Desenvolvimento)</summary>
                    <div class="text-sm text-cinza-claro">
                        <p><strong>Mensagem:</strong> {{ $exception->getMessage() }}</p>
                        <p><strong>Arquivo:</strong> {{ $exception->getFile() }}</p>
                        <p><strong>Linha:</strong> {{ $exception->getLine() }}</p>
                        @if($exception->getTrace())
                            <p><strong>Stack Trace:</strong></p>
                            <pre class="text-xs overflow-x-auto">{{ $exception->getTraceAsString() }}</pre>
                        @endif
                    </div>
                </details>
            </div>
        @endif

        <!-- Botões de Ação -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <x-button variant="primary" size="lg" onclick="history.back()">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Voltar
            </x-button>
            
            <x-button variant="outline" size="lg" tag="a" href="{{ route('dashboard') }}">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                </svg>
                Ir para Dashboard
            </x-button>
            
            @if($exception->getStatusCode() == 500 || $exception->getStatusCode() == 503)
                <x-button variant="outline" size="lg" onclick="location.reload()">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Tentar Novamente
                </x-button>
            @endif
        </div>

        <!-- Informações Adicionais -->
        <div class="mt-12 pt-8 border-t border-cinza-claro-2">
            <p class="text-cinza-claro text-sm mb-0">
                Se o problema persistir, entre em contato com o suporte técnico.
            </p>
        </div>
    </div>
</div>
@endsection