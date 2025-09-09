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
<body>
    <div class="login-container">
        <div class="login-wrapper">
            <!-- Painel Esquerdo - Logo e Branding -->
            <div class="login-left-panel">
                <div class="login-brand">
                    <div class="login-logo-image">
                        <img src="{{ secure_asset('images/8bits-branco.png') }}" alt="8 BITS" class="logo-img">
                    </div>
                    <p class="login-tagline">Sistema de Gerenciamento de Tickets</p>
                </div>
            </div>

            <!-- Painel Direito - Formulário -->
            <div class="login-right-panel">
                
                    <div class="login-form">
                    <!-- Título do formulário -->
                    <div class="login-form-header">
                        <h2>Bem-vindo de volta!</h2>
                        <p>Faça login na sua conta</p>
                    </div>

                                <!-- Mensagens de erro -->
                                @if ($errors->any())
                                    <x-alert type="danger" dismissible>
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
                                <form method="POST" action="{{ route('login') }}">
                                    @csrf

                                    <!-- Email -->
                                    <x-input 
                                        type="email" 
                                        name="email" 
                                        label="E-mail" 
                                        placeholder="Digite seu email" 
                                        value="{{ old('email') }}" 
                                        required 
                                        autofocus 
                                        icon="user" />

                                    <!-- Password -->
                                    <x-input 
                                        type="password" 
                                        name="password" 
                                        label="Senha" 
                                        placeholder="Digite sua senha" 
                                        required 
                                        icon="lock" />

                                    <!-- Lembrar de mim -->
                                    <div class="flex items-center mb-4">
                                        <input type="checkbox" name="remember" id="remember" 
                                               class="w-4 h-4 text-amber-600 bg-gray-100 border-gray-300 rounded focus:ring-amber-500 focus:ring-2" 
                                               {{ old('remember') ? 'checked' : '' }}>
                                        <label for="remember" class="ml-2 text-sm text-gray-700">
                                            Lembrar de mim
                                        </label>
                                    </div>

                                    <!-- Botão de login -->
                                    <x-button type="submit" class="w-full">
                                        Entrar
                                    </x-button>

                                    <!-- Links do formulário -->
                                    <div class="login-form-links">
                                        @if (Route::has('password.request'))
                                            <a href="{{ route('password.request') }}" class="login-form-link">
                                                Esqueceu sua senha?
                                            </a>
                                        @endif
                                    </div>

                                    <!-- Removido: Criar conta -->
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
</body>
</html>