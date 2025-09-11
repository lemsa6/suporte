@extends('layouts.app')

@section('title', 'Auditoria do Usuário: ' . $user->name)

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div class="mb-4 sm:mb-0">
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('admin.audit.index') }}" class="text-cinza-claro hover:text-cinza">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <span class="text-sm text-cinza-claro">Auditoria</span>
        </div>
        <h1 class="page-title mt-1">Auditoria do Usuário</h1>
        <div class="flex items-center gap-3 mt-2">
            <div class="w-8 h-8 bg-roxo bg-opacity-10 rounded-full flex items-center justify-center">
                <span class="text-sm font-medium text-roxo">{{ substr($user->name, 0, 1) }}</span>
            </div>
            <div>
                <p class="text-cinza font-medium">{{ $user->name }}</p>
                <p class="text-sm text-cinza-claro">{{ $user->email }} • {{ ucfirst($user->role) }}</p>
            </div>
        </div>
    </div>
    <div class="flex gap-3">
        <x-button variant="outline" tag="a" href="{{ route('users.show', $user) }}">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            Ver Perfil
        </x-button>
    </div>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Estatísticas Rápidas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <x-stat-card 
            title="Total de Ações"
            :value="$logs->total()"
            color="primary"
        >
            <svg class="w-5 h-5 text-roxo" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </x-stat-card>

        <x-stat-card 
            title="Hoje"
            :value="$logs->where('created_at', '>=', today())->count()"
            color="success"
        >
            <svg class="w-5 h-5 text-verde" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
        </x-stat-card>

        <x-stat-card 
            title="Esta Semana"
            :value="$logs->where('created_at', '>=', now()->startOfWeek())->count()"
            color="warning"
        >
            <svg class="w-5 h-5 text-amarelo" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
        </x-stat-card>

        <x-stat-card 
            title="Último Acesso"
            :value="$user->last_login_at ? $user->last_login_at->format('d/m H:i') : 'Nunca'"
            color="info"
        >
            <svg class="w-5 h-5 text-roxo-det" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </x-stat-card>
    </div>

    <!-- Logs de Auditoria -->
    <h2 class="section-title mb-4">Histórico de Ações</h2>
    <x-card>
        @if($logs->count() > 0)
            <x-table striped hover>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Evento</th>
                        <th>Modelo</th>
                        <th>Data/Hora</th>
                        <th>IP</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                        <tr class="hover:bg-cinza-claro-2">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-cinza">
                                #{{ $log->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <x-badge variant="{{ 
                                    $log->event_type === 'created' ? 'success' : (
                                    $log->event_type === 'updated' ? 'warning' : (
                                    $log->event_type === 'deleted' ? 'danger' : 'secondary'
                                )) }}">
                                    {{ ucfirst($log->event_type) }}
                                </x-badge>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-cinza">
                                {{ class_basename($log->auditable_type) }}
                                @if($log->auditable_id)
                                    <span class="text-cinza-claro">#{{ $log->auditable_id }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-cinza">
                                {{ $log->created_at->format('d/m/Y H:i:s') }}
                                <br>
                                <span class="text-xs text-cinza-claro">{{ $log->created_at->diffForHumans() }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-cinza-claro">
                                {{ $log->ip_address ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <x-button variant="outline" size="sm" tag="a" href="{{ route('admin.audit.show', $log) }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Detalhes
                                </x-button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </x-table>

            <!-- Paginação -->
            <div class="mt-6">
                {{ $logs->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-cinza-claro mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-lg font-medium text-cinza mb-2">Nenhum log encontrado</h3>
                <p class="text-cinza-claro">Este usuário ainda não possui logs de auditoria registrados.</p>
            </div>
        @endif
    </x-card>
</div>
@endsection
