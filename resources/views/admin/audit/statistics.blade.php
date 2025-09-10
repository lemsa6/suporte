@extends('layouts.app')

@section('title', 'Estatísticas de Auditoria')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div class="mb-4 sm:mb-0">
        <span class="text-sm text-cinza-claro">Administração</span>
        <h1 class="page-title mt-1">Estatísticas de Auditoria</h1>
        <p class="text-cinza mt-2">Análise detalhada dos logs de auditoria do sistema</p>
    </div>
    <x-button variant="outline" tag="a" href="{{ route('admin.audit.index') }}">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Voltar
    </x-button>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Filtros -->
    <x-card title="Filtros">
        <form method="GET" action="{{ route('admin.audit.statistics') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Data Inicial -->
                <div>
                    <label for="date_from" class="block text-sm font-medium text-cinza mb-2">Data Inicial</label>
                    <input type="date" id="date_from" name="date_from" 
                        class="w-full px-3 py-2 border border-cinza-claro-2 rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent"
                        value="{{ request('date_from') }}">
                </div>

                <!-- Data Final -->
                <div>
                    <label for="date_to" class="block text-sm font-medium text-cinza mb-2">Data Final</label>
                    <input type="date" id="date_to" name="date_to" 
                        class="w-full px-3 py-2 border border-cinza-claro-2 rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent"
                        value="{{ request('date_to') }}">
                </div>

                <!-- Tipo de Evento -->
                <div>
                    <label for="event_type" class="block text-sm font-medium text-cinza mb-2">Tipo de Evento</label>
                    <select id="event_type" name="event_type" 
                        class="w-full px-3 py-2 border border-cinza-claro-2 rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent">
                        <option value="">Todos os tipos</option>
                        @foreach($eventTypes as $type => $label)
                            <option value="{{ $type }}" {{ request('event_type') == $type ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Usuário -->
                <div>
                    <label for="user_id" class="block text-sm font-medium text-cinza mb-2">Usuário</label>
                    <select id="user_id" name="user_id" 
                        class="w-full px-3 py-2 border border-cinza-claro-2 rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent">
                        <option value="">Todos os usuários</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Botões -->
            <div class="flex justify-end gap-3 pt-4 border-t border-cinza-claro-2">
                <x-button variant="outline" tag="a" href="{{ route('admin.audit.statistics') }}">
                    Limpar Filtros
                </x-button>
                <x-button variant="primary" type="submit">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Aplicar Filtros
                </x-button>
            </div>
        </form>
    </x-card>

    <!-- Estatísticas Gerais -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <x-stat-card 
            title="Total de Eventos" 
            :value="$statistics['total_events']" 
            icon="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
            color="primary"
        />
        
        <x-stat-card 
            title="Eventos Hoje" 
            :value="$statistics['events_today']" 
            icon="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
            color="success"
        />
        
        <x-stat-card 
            title="Usuários Ativos" 
            :value="$statistics['active_users']" 
            icon="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"
            color="warning"
        />
        
        <x-stat-card 
            title="Modelos Auditados" 
            :value="$statistics['audited_models']" 
            icon="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
            color="info"
        />
    </div>

    <!-- Gráficos -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Eventos por Dia -->
        <x-card title="Eventos por Dia">
            <div class="h-64 flex items-center justify-center">
                <div class="text-center">
                    <svg class="w-16 h-16 text-cinza-claro mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <p class="text-cinza-claro">Gráfico será implementado em breve</p>
                </div>
            </div>
        </x-card>

        <!-- Eventos por Tipo -->
        <x-card title="Eventos por Tipo">
            <div class="space-y-3">
                @foreach($statistics['events_by_type'] as $type => $count)
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-cinza">{{ ucfirst(str_replace('_', ' ', $type)) }}</span>
                        <div class="flex items-center">
                            <div class="w-20 bg-cinza-claro-2 rounded-full h-2 mr-3">
                                <div class="bg-roxo h-2 rounded-full" style="width: {{ ($count / $statistics['total_events']) * 100 }}%"></div>
                            </div>
                            <span class="text-sm font-medium text-cinza">{{ $count }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </x-card>
    </div>

    <!-- Top Usuários -->
    <x-card title="Top Usuários por Atividade">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-cinza-claro-2">
                <thead class="bg-cinza-claro-2">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-cinza-claro uppercase tracking-wider">Usuário</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-cinza-claro uppercase tracking-wider">Eventos</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-cinza-claro uppercase tracking-wider">Última Atividade</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-cinza-claro uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-cinza-claro-2">
                    @foreach($statistics['top_users'] as $user)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-roxo bg-opacity-10 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-roxo font-medium text-sm">{{ substr($user->name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-cinza">{{ $user->name }}</div>
                                        <div class="text-sm text-cinza-claro">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-cinza">{{ $user->events_count }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-cinza-claro">
                                {{ $user->last_activity ? $user->last_activity->format('d/m/Y H:i') : 'Nunca' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <x-button variant="link" size="sm" tag="a" href="{{ route('admin.audit.user', $user->id) }}">
                                    Ver Detalhes
                                </x-button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-card>
</div>
@endsection