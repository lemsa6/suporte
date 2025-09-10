@extends('layouts.app')

@section('title', 'Logs de Auditoria')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div class="mb-4 sm:mb-0">
        <span class="text-sm text-cinza-claro">Administração</span>
        <h1 class="page-title mt-1">Logs de Auditoria</h1>
        <p class="text-cinza mt-2">Acompanhe todas as ações realizadas no sistema</p>
    </div>
    <div class="flex gap-2">
        <x-button variant="outline" tag="a" href="{{ route('admin.audit.statistics') }}">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            Estatísticas
        </x-button>
        <x-button variant="outline" tag="a" href="{{ route('admin.settings.index') }}">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Voltar
        </x-button>
    </div>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Filtros -->
    <h2 class="section-title mb-4">Filtros</h2>
    <x-card>
        <form method="GET" action="{{ route('admin.audit.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Tipo de Evento -->
                <div>
                    <label for="event_type" class="block text-sm font-medium text-cinza mb-2">Tipo de Evento</label>
                    <select id="event_type" name="event_type" 
                        class="w-full px-3 py-2 border border-cinza-claro-2 rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent">
                        <option value="">Todos os tipos</option>
                        <option value="created" {{ request('event_type') == 'created' ? 'selected' : '' }}>Criado</option>
                        <option value="updated" {{ request('event_type') == 'updated' ? 'selected' : '' }}>Atualizado</option>
                        <option value="deleted" {{ request('event_type') == 'deleted' ? 'selected' : '' }}>Excluído</option>
                        <option value="restored" {{ request('event_type') == 'restored' ? 'selected' : '' }}>Restaurado</option>
                    </select>
                </div>

                <!-- Modelo -->
                <div>
                    <label for="auditable_type" class="block text-sm font-medium text-cinza mb-2">Modelo</label>
                    <select id="auditable_type" name="auditable_type" 
                        class="w-full px-3 py-2 border border-cinza-claro-2 rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent">
                        <option value="">Todos os modelos</option>
                        @foreach($auditableTypes as $type => $label)
                            <option value="{{ $type }}" {{ request('auditable_type') == $type ? 'selected' : '' }}>
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

                <!-- Data -->
                <div>
                    <label for="date" class="block text-sm font-medium text-cinza mb-2">Data</label>
                    <input type="date" id="date" name="date" 
                        class="w-full px-3 py-2 border border-cinza-claro-2 rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent"
                        value="{{ request('date') }}">
                </div>
            </div>

            <!-- Botões -->
            <div class="flex justify-end gap-3 pt-4 border-t border-cinza-claro-2">
                <x-button variant="outline" tag="a" href="{{ route('admin.audit.index') }}">
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

    <!-- Lista de Logs -->
    <h2 class="section-title mb-4">Logs de Auditoria ({{ $logs->total() }})</h2>
    <x-card>
        @if($logs->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-cinza-claro-2">
                    <thead class="bg-cinza-claro-2">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-cinza-claro uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-cinza-claro uppercase tracking-wider">Evento</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-cinza-claro uppercase tracking-wider">Modelo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-cinza-claro uppercase tracking-wider">Usuário</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-cinza-claro uppercase tracking-wider">Data/Hora</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-cinza-claro uppercase tracking-wider">IP</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-cinza-claro uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-cinza-claro-2">
                        @foreach($logs as $log)
                            <tr class="hover:bg-cinza-claro-2">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-cinza">
                                    #{{ $log->id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <x-audit-badge :event-type="$log->event_type" />
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-cinza">
                                    {{ $auditableTypes[$log->auditable_type] ?? class_basename($log->auditable_type) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-roxo bg-opacity-10 rounded-full flex items-center justify-center mr-3">
                                            <span class="text-roxo font-medium text-sm">{{ substr($log->user_name ?? 'S', 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-cinza">{{ $log->user_name ?? 'Sistema' }}</div>
                                            <div class="text-sm text-cinza-claro">{{ ucfirst($log->user_type ?? 'Sistema') }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-cinza-claro">
                                    {{ $log->created_at->format('d/m/Y H:i:s') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-cinza-claro">
                                    {{ $log->ip_address ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <x-button variant="link" size="sm" tag="a" href="{{ route('admin.audit.show', $log->id) }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </x-button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginação -->
            <div class="mt-6">
                {{ $logs->links() }}
            </div>
        @else
            <div class="text-center py-8">
                <svg class="w-16 h-16 text-cinza-claro mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-lg font-medium text-cinza mb-2">Nenhum log encontrado</h3>
                <p class="text-cinza-claro">Não há registros de auditoria com os filtros aplicados.</p>
            </div>
        @endif
    </x-card>
</div>
@endsection