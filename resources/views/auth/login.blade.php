<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ \App\Helpers\SystemHelper::name() }} - Login</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <!-- Bootstrap 5 via CDN (sem warnings de deprecação) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- CSS compilado diretamente -->
    <link rel="stylesheet" href="{{ asset('build/assets/app-4456f621.css') }}">
    <link rel="stylesheet" href="{{ asset('build/assets/app-9d8fa3fc.css') }}">
    <script src="{{ asset('build/assets/app-478fd0a3.js') }}" defer></script>
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
                                    <div class="login-error-message">
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
                                    </div>
                                @endif

                                <!-- Formulário de login -->
                                <form method="POST" action="{{ route('login') }}">
                                    @csrf

                                    <!-- Username -->
                                    <div class="login-form-group">
                                        <label for="email" class="form-label">E-mail</label>
                                        <div class="position-relative">
                                            <svg class="login-input-icon" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                            </svg>
                                            <input type="email" name="email" id="email" 
                                                class="login-form-input @error('email') is-invalid @enderror" 
                                                value="{{ old('email') }}" 
                                                placeholder="Digite seu email" 
                                                required autofocus>
                                        </div>
                                    </div>

                                    <!-- Password -->
                                    <div class="login-form-group">
                                        
                                        <div class="position-relative">
                                            <svg class="login-input-icon" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                            </svg>
                                            <input type="password" name="password" id="password" 
                                                class="login-form-input @error('password') is-invalid @enderror" 
                                                placeholder="Digite sua senha" 
                                                required>
                                        </div>
                                    </div>

                                    <!-- Lembrar de mim -->
                                    <div class="form-check mb-3">
                                        <input class="login-form-check-input form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <label class="login-form-check-label form-check-label" for="remember">
                                            Lembrar de mim
                                        </label>
                                    </div>

                                    <!-- Botão de login -->
                                    <button type="submit" class="login-btn">
                                        Entrar
                                    </button>

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