<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Token Expirado - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts - Lato Local -->
    <link rel="stylesheet" href="{{ asset('fonts/lato/lato.css') }}">

    <!-- CSS do Tailwind via Vite -->
    @vite(['resources/css/tailwind.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gradient-to-br from-cinza-claro-2 to-cinza-claro flex items-center justify-center px-4">
        <div class="max-w-2xl w-full text-center">
            <!-- Ícone de Erro -->
            <div class="mb-8">
                <svg width="120" height="120" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="text-cinza-claro mx-auto">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>

            <!-- Título e Descrição -->
            <div class="mb-8">
                <h1 class="text-6xl font-bold text-cinza mb-4">419</h1>
                <h2 class="text-2xl font-semibold text-cinza mb-4">Token de Sessão Expirado</h2>
                <p class="text-cinza-claro text-lg mb-4">
                    Sua sessão expirou por inatividade. Por favor, faça login novamente para continuar.
                </p>
                <div class="flex items-center justify-center text-cinza-claro">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Sessão expirada por segurança</span>
                </div>
            </div>

            <!-- Botões de Ação -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <x-button variant="primary" size="lg" tag="a" href="{{ route('login') }}">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                    Fazer Login
                </x-button>
                
                <x-button variant="outline" size="lg" onclick="history.back()">
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
            </div>

            <!-- Informações Adicionais -->
            <div class="mt-12 pt-8 border-t border-cinza-claro-2">
                <p class="text-cinza-claro text-sm mb-0">
                    Por segurança, as sessões expiram após um período de inatividade.
                </p>
            </div>
        </div>
    </div>
</body>
</html>