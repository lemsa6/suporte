<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ \App\Helpers\SystemHelper::name() }} - Login</title>

    <!-- Fonts - Lato Local -->
    <link rel="stylesheet" href="{{ asset('fonts/lato/lato.css') }}">

    <!-- CSS do Tailwind via Vite -->
    @vite(['resources/css/tailwind.css', 'resources/js/app.js'])
</head>
<body class="bg-creme min-h-screen flex items-center justify-center p-4">
    <!-- Box de Login Centralizado -->
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
                <h2 class="section-title text-xl mb-2">Bem-vindo de volta!</h2>
                <p class="text-cinza-claro">Faça login na sua conta</p>
            </div>

            <!-- Mensagens de erro -->
            @if ($errors->any())
                <x-alert type="danger" class="mb-6">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>
                                @if (str_contains($error, 'These credentials do not match our records'))
                                    Credenciais inválidas. Verifique seu email e senha.
                                @elseif (str_contains($error, 'The email field is required'))
                                    O campo email é obrigatório.
                                @elseif (str_contains($error, 'The password field is required'))
                                    O campo senha é obrigatório.
                                @elseif (str_contains($error, 'The email must be a valid email address'))
                                    Digite um email válido.
                                @else
                                    {{ $error }}
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </x-alert>
            @endif

            <!-- Formulário de login -->
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email -->
                <x-input 
                    type="email" 
                    name="email" 
                    label="E-mail" 
                    placeholder="Digite seu email" 
                    value="{{ old('email') }}" 
                    required 
                    autofocus />

                <!-- Password -->
                <x-input 
                    type="password" 
                    name="password" 
                    label="Senha" 
                    placeholder="Digite sua senha" 
                    required />

                <!-- Lembrar de mim -->
                <div class="flex items-center">
                    <input type="checkbox" name="remember" id="remember" 
                           class="w-4 h-4 text-roxo bg-branco border-cinza-claro rounded focus:ring-roxo focus:ring-2" 
                           {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember" class="ml-2 text-sm text-cinza">
                        Lembrar de mim
                    </label>
                </div>

                <!-- Botão de login -->
                <x-button type="submit" variant="primary" class="w-full">
                    Entrar
                </x-button>

                <!-- Links do formulário -->
                <div class="text-center">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-roxo-det hover:text-roxo text-sm">
                            Esqueceu sua senha?
                        </a>
                    @endif
                </div>
            </form>
        </x-card>
    </div>
</body>
</html>