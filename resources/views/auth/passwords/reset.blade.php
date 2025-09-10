<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ \App\Helpers\SystemHelper::name() }} - Redefinir Senha</title>

    <!-- Fonts - Lato Local -->
    <link rel="stylesheet" href="{{ asset('fonts/lato/lato.css') }}">

    <!-- CSS do Tailwind via Vite -->
    @vite(['resources/css/tailwind.css', 'resources/js/app.js'])
</head>
<body class="bg-creme min-h-screen flex items-center justify-center p-4">
    <!-- Box de Redefinição Centralizado -->
    <div class="w-full max-w-md">
        <x-card class="login-card">
            <!-- Logo e Título -->
            <div class="text-center mb-8">
                <div class="login-logo mb-4">
                    <img src="{{ secure_asset('images/8bits-branco.png') }}" alt="8 BITS" class="w-16 h-16 mx-auto">
                </div>
                <h1 class="page-title text-2xl mb-2">{{ \App\Helpers\SystemHelper::name() }}</h1>
                <p class="text-cinza">Sistema de Gerenciamento de Tickets</p>
            </div>

            <!-- Título do formulário -->
            <div class="text-center mb-6">
                <h2 class="section-title text-xl mb-2">Redefinir Senha</h2>
                <p class="text-cinza-claro">Digite sua nova senha</p>
            </div>

            <!-- Mensagens de status -->
            @if (session('status'))
                <x-alert type="success" class="mb-6">
                    {{ session('status') }}
                </x-alert>
            @endif

            <!-- Mensagens de erro -->
            @if ($errors->any())
                <x-alert type="danger" class="mb-6">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </x-alert>
            @endif

            <!-- Formulário de redefinição -->
            <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
                @csrf

                <!-- Token hidden -->
                <input type="hidden" name="token" value="{{ $token }}">

                <!-- Email -->
                <x-input 
                    type="email" 
                    name="email" 
                    label="E-mail" 
                    placeholder="Digite seu email" 
                    value="{{ $email }}" 
                    required 
                    readonly />

                <!-- Nova Senha -->
                <x-input 
                    type="password" 
                    name="password" 
                    label="Nova Senha" 
                    placeholder="Digite sua nova senha" 
                    required 
                    autofocus />

                <!-- Confirmar Senha -->
                <x-input 
                    type="password" 
                    name="password_confirmation" 
                    label="Confirmar Nova Senha" 
                    placeholder="Confirme sua nova senha" 
                    required />

                <!-- Botão de redefinição -->
                <x-button type="submit" variant="primary" class="w-full">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Redefinir Senha
                </x-button>

                <!-- Link de volta -->
                <div class="text-center">
                    <a href="{{ route('login') }}" class="text-roxo-det hover:text-roxo text-sm">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Voltar ao Login
                    </a>
                </div>
            </form>
        </x-card>
    </div>
</body>
</html>
