<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

            <title>{{ \App\Helpers\SystemHelper::name() }} - @yield('title', 'Dashboard')</title>

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
<body class="bg-light">
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar d-flex flex-column flex-shrink-0" id="sidebar">
            <!-- Logo -->
            <div class="d-flex align-items-center justify-content-between p-3 border-bottom">
                <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                    <div class="sidebar-logo-container">
                        <svg class="text-dark" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <span class="ms-2 fs-5 fw-semibold text-dark">{{ \App\Helpers\SystemHelper::name() }}</span>
                </a>
                
                <!-- Botão fechar sidebar (mobile) -->
                <button type="button" class="btn-close d-lg-none" aria-label="Close" onclick="toggleSidebar()"></button>
            </div>

            <!-- Navigation Menu -->
            <nav class="mt-3">
                <div class="nav flex-column nav-pills px-3">
                    <a href="{{ route('dashboard') }}" class="nav-link d-flex align-items-center mb-2 {{ request()->routeIs('dashboard') ? 'active' : 'text-dark' }}">
                        <svg class="me-2" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                        </svg>
                        Dashboard
                    </a>

                    <a href="{{ route('tickets.index') }}" class="nav-link d-flex align-items-center mb-2 {{ request()->routeIs('tickets.*') ? 'active' : 'text-dark' }}">
                        <svg class="me-2" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Tickets
                    </a>

                    @if(auth()->user()->canManageClients())
                    <a href="{{ route('clients.index') }}" class="nav-link d-flex align-items-center mb-2 {{ request()->routeIs('clients.*') ? 'active' : 'text-dark' }}">
                        <svg class="me-2" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Clientes
                    </a>
                    @endif

                    @if(auth()->user()->canManageClients())
                    <a href="{{ route('categories.index') }}" class="nav-link d-flex align-items-center mb-2 {{ request()->routeIs('categories.*') ? 'active' : 'text-dark' }}">
                        <svg class="me-2" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                        Categorias
                    </a>
                    @endif

                    @if(auth()->user()->canManageClients())
                    <a href="{{ route('reports.index') }}" class="nav-link d-flex align-items-center mb-2 {{ request()->routeIs('reports.*') ? 'active' : 'text-dark' }}">
                        <svg class="me-2" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Relatórios
                    </a>
                    @endif

                    @if(auth()->user()->isClienteGestor())
                    <a href="{{ route('company.users.index') }}" class="nav-link d-flex align-items-center mb-2 {{ request()->routeIs('company.users.*') ? 'active' : 'text-dark' }}">
                        <svg class="me-2" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Funcionários da Empresa
                    </a>
                    @endif
                </div>
            </nav>

            <!-- User Profile Section -->
            <div class="mt-auto p-3 border-top">
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="user-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="sidebar-user-avatar">
                            <span class="fw-medium text-primary">{{ substr(auth()->user()->name, 0, 1) }}</span>
                        </div>
                        <div class="ms-2">
                            <p class="mb-0 text-dark fw-medium">{{ auth()->user()->name }}</p>
                            <p class="mb-0 text-muted small">{{ auth()->user()->email }}</p>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="user-dropdown">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Perfil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">Sair</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Main Content Wrapper -->
        <div class="main-content">
            <!-- Top bar (mobile) -->
            <div class="d-lg-none sticky-top bg-white border-bottom">
                <div class="d-flex align-items-center justify-content-between p-3">
                    <button type="button" class="btn btn-outline-secondary" onclick="toggleSidebar()">
                        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <div class="d-flex align-items-center">
                        <div class="d-flex align-items-center justify-content-center bg-light rounded-circle me-2" style="width: 32px; height: 32px;">
                            <span class="fw-medium text-primary">{{ substr(auth()->user()->name, 0, 1) }}</span>
                        </div>
                        <span class="fw-medium">{{ auth()->user()->name }}</span>
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <main class="py-4">
                <div class="container">
                    <!-- Flash Messages -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('warning'))
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            {{ session('warning') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('info'))
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            {{ session('info') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Page Header -->
                    @hasSection('header')
                        <div class="mb-4">
                            @yield('header')
                        </div>
                    @endif

                    <!-- Content -->
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Toggle sidebar (mobile)
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.querySelector('.main-content');
            
            if (window.innerWidth < 992) {
                sidebar.classList.toggle('d-none');
                
                if (sidebar.classList.contains('d-none')) {
                    mainContent.style.marginLeft = '0';
                    mainContent.style.width = '100%';
                } else {
                    mainContent.style.marginLeft = '280px';
                    mainContent.style.width = 'calc(100% - 280px)';
                }
            }
        }

        // Handle window resize
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.querySelector('.main-content');
            
            if (window.innerWidth >= 992) {
                sidebar.classList.remove('d-none');
                mainContent.style.marginLeft = '280px';
                mainContent.style.width = 'calc(100% - 280px)';
            } else {
                sidebar.classList.add('d-none');
                mainContent.style.marginLeft = '0';
                mainContent.style.width = '100%';
            }
        });

        // Initialize sidebar state on page load
        document.addEventListener('DOMContentLoaded', function() {
            if (window.innerWidth < 992) {
                document.getElementById('sidebar').classList.add('d-none');
                document.querySelector('.main-content').style.marginLeft = '0';
                document.querySelector('.main-content').style.width = '100%';
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>