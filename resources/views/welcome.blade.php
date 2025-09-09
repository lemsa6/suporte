<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

            <title>{{ \App\Helpers\SystemHelper::name() }} - Bem-vindo</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <!-- Vite CSS & JS -->
    @vite(['resources/css/app.css', 'resources/scss/app.scss', 'resources/js/app.js'])
</head>
<body class="bg-light">
    <div class="d-flex flex-column justify-content-center min-vh-100 py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-xl-6">
                    <!-- Header -->
                    <div class="text-center mb-5">
                        <div class="d-inline-flex align-items-center justify-content-center bg-primary rounded p-3 shadow-sm mb-4">
                            <svg width="40" height="40" fill="none" stroke="currentColor" class="text-dark" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        
                        <h1 class="display-5 fw-bold mb-3">Sistema de Tickets</h1>
                        <p class="lead text-muted mb-5">Bem-vindo ao sistema de gerenciamento de chamados</p>
                        
                        <div class="d-flex justify-content-center gap-3">
                            @auth
                                <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg d-inline-flex align-items-center">
                                    <svg class="me-2" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                                    </svg>
                                    Acessar Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-primary btn-lg d-inline-flex align-items-center">
                                    <svg class="me-2" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                    </svg>
                                    Fazer Login
                                </a>
                                <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg d-inline-flex align-items-center">
                                    <svg class="me-2" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                    </svg>
                                    Criar Conta
                                </a>
                            @endauth
                        </div>
                    </div>

                    <!-- Features -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h3 class="h5 fw-semibold mb-4 text-center">Principais Funcionalidades</h3>
                            
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center py-2">
                                        <div class="d-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10 p-2 me-3 feature-icon">
                                            <svg width="20" height="20" fill="none" stroke="currentColor" class="text-primary" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h6 class="mb-1 fw-medium">Gestão de Tickets</h6>
                                            <p class="text-muted small mb-0">Crie, acompanhe e gerencie todos os chamados de suporte</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="d-flex align-items-center py-2">
                                        <div class="d-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10 p-2 me-3 feature-icon">
                                            <svg width="20" height="20" fill="none" stroke="currentColor" class="text-primary" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h6 class="mb-1 fw-medium">Gestão de Clientes</h6>
                                            <p class="text-muted small mb-0">Organize e mantenha informações dos seus clientes</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="d-flex align-items-center py-2">
                                        <div class="d-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10 p-2 me-3 feature-icon">
                                            <svg width="20" height="20" fill="none" stroke="currentColor" class="text-primary" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h6 class="mb-1 fw-medium">Relatórios e Analytics</h6>
                                            <p class="text-muted small mb-0">Acompanhe métricas e performance do suporte</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="text-center mt-5">
                        <p class="text-muted small mb-0">
                            &copy; {{ date('Y') }} {{ config('app.name', 'Sistema de Tickets') }}. Todos os direitos reservados.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
