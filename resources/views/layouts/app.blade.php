<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{ \App\Helpers\SystemHelper::name() }}</title>

    <!-- Fonts - Lato Local -->
    <link rel="stylesheet" href="{{ asset('fonts/lato/lato.css') }}">

    <!-- CSS do Tailwind via Vite -->
    @vite(['resources/css/tailwind.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 antialiased">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <!-- Logo -->
            <div class="flex items-center justify-between p-6">
                <a href="{{ route('dashboard') }}" class="flex items-center text-decoration-none">
                    <div class="sidebar-logo">
                        <svg class="text-white" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <span class="ml-3 text-xl font-light text-white">{{ \App\Helpers\SystemHelper::name() }}</span>
                </a>
                
                <!-- Botão fechar sidebar (mobile) -->
                <button type="button" class="sidebar-close-btn" onclick="toggleSidebar()">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Navigation Menu -->
            <nav class="mt-6 px-4">
                <div class="space-y-2">
                    <x-menu-item 
                        href="{{ route('dashboard') }}" 
                        :active="request()->routeIs('dashboard')"
                        icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>'
                    >
                        Dashboard
                    </x-menu-item>

                    <x-menu-item 
                        href="{{ route('tickets.index') }}" 
                        :active="request()->routeIs('tickets.*')"
                        icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>'
                    >
                        Tickets
                    </x-menu-item>

                    @if(auth()->user()->canManageClients())
                    <x-menu-item 
                        href="{{ route('clients.index') }}" 
                        :active="request()->routeIs('clients.*')"
                        icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>'
                    >
                        Clientes
                    </x-menu-item>
                    @endif

                    @if(auth()->user()->canManageClients())
                    <x-menu-item 
                        href="{{ route('categories.index') }}" 
                        :active="request()->routeIs('categories.*')"
                        icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>'
                    >
                        Categorias
                    </x-menu-item>
                    @endif

                    @if(auth()->user()->canManageClients())
                    <x-menu-item 
                        href="{{ route('reports.index') }}" 
                        :active="request()->routeIs('reports.*')"
                        icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>'
                    >
                        Relatórios
                    </x-menu-item>
                    @endif

                    @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'nav-link-active' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Configurações
                    </a>
                    
                    <a href="{{ route('admin.audit.index') }}" class="nav-link {{ request()->routeIs('admin.audit.*') ? 'nav-link-active' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Auditoria
                    </a>
                    @endif

                    @if(auth()->user()->isClienteGestor())
                    <a href="{{ route('company.users.index') }}" class="nav-link {{ request()->routeIs('company.users.*') ? 'nav-link-active' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Funcionários da Empresa
                    </a>
                    @endif
                </div>
            </nav>

            <!-- User Profile Section -->
            <div class="absolute bottom-0 left-0 right-0 p-4">
                <div class="relative">
                    <button class="sidebar-user-btn" onclick="toggleUserMenu()">
                        <div class="sidebar-user-avatar">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="sidebar-user-info">
                            <p class="sidebar-user-name">{{ auth()->user()->name }}</p>
                            <p class="sidebar-user-email">{{ auth()->user()->email }}</p>
                        </div>
                        <svg class="sidebar-user-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    
                    <!-- User Dropdown Menu -->
                    <div id="userMenu" class="sidebar-user-menu hidden">
                        <div class="py-1">
                            <a href="{{ route('profile.edit') }}" class="sidebar-user-menu-item">Perfil</a>
                            <hr class="sidebar-user-menu-divider">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="sidebar-user-menu-item">Sair</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Page Content -->
            <div class="main-content-container">
                <main class="py-6">
                <!-- Flash Messages -->
                @if(session('success'))
                    <x-alert type="success" dismissible>
                        <strong>Sucesso!</strong> {{ session('success') }}
                    </x-alert>
                @endif

                @if(session('error'))
                    <x-alert type="error" dismissible>
                        <strong>Erro!</strong> {{ session('error') }}
                    </x-alert>
                @endif

                @if(session('warning'))
                    <x-alert type="warning" dismissible>
                        <strong>Aviso!</strong> {{ session('warning') }}
                    </x-alert>
                @endif

                @if(session('info'))
                    <x-alert type="info" dismissible>
                        <strong>Informação!</strong> {{ session('info') }}
                    </x-alert>
                @endif

                <!-- Page Header -->
                @hasSection('header')
                    <div class="mb-8">
                        @yield('header')
                    </div>
                @endif

                @yield('content')
            </main>
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('-translate-x-full');
        }

        function toggleUserMenu() {
            const userMenu = document.getElementById('userMenu');
            userMenu.classList.toggle('hidden');
        }

        // Fechar sidebar ao clicar fora em telas pequenas
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggleButton = document.querySelector('.lg\\:hidden button');
            const isClickInsideSidebar = sidebar.contains(event.target);
            const isClickOnToggleButton = toggleButton && toggleButton.contains(event.target);

            if (!isClickInsideSidebar && !isClickOnToggleButton && !sidebar.classList.contains('-translate-x-full') && window.innerWidth < 1024) {
                sidebar.classList.add('-translate-x-full');
            }
        });

        // Fechar user menu ao clicar fora
        document.addEventListener('click', function(event) {
            const userMenu = document.getElementById('userMenu');
            const userButton = event.target.closest('button[onclick="toggleUserMenu()"]');
            
            if (!userButton && !userMenu.contains(event.target)) {
                userMenu.classList.add('hidden');
            }
        });

        // Ajustar layout em resize
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.querySelector('.main-content');
            
            if (window.innerWidth >= 1024) {
                sidebar.classList.remove('-translate-x-full');
                // Removido: mainContent.style.width para não sobrescrever CSS
            } else {
                // Removido: mainContent.style.width para não sobrescrever CSS
            }
        });
    </script>

    @stack('scripts')
</body>
</html>