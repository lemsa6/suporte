@extends('layouts.app')

@section('title', 'Detalhes do Log de Auditoria')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div class="mb-4 sm:mb-0">
        <span class="text-sm text-cinza-claro">Administração</span>
        <h1 class="page-title mt-1">Detalhes do Log de Auditoria</h1>
        <p class="text-cinza mt-2">Informações completas sobre a ação registrada</p>
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
    <!-- Informações Básicas -->
    <h2 class="section-title mb-4">Informações Básicas</h2>
    <x-card>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div class="flex justify-between">
                    <span class="text-cinza-claro">ID do Log:</span>
                    <span class="font-medium text-cinza">#{{ $auditLog->id }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-cinza-claro">Tipo de Evento:</span>
                    <x-audit-badge :event-type="$auditLog->event_type" />
                </div>
                <div class="flex justify-between">
                    <span class="text-cinza-claro">Modelo:</span>
                    <span class="font-medium text-cinza">{{ class_basename($auditLog->auditable_type) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-cinza-claro">ID do Modelo:</span>
                    <span class="font-medium text-cinza">{{ $auditLog->auditable_id }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-cinza-claro">Usuário:</span>
                    <span class="font-medium text-cinza">
                        @if($auditLog->user)
                            {{ $auditLog->user->name }}
                        @else
                            <span class="text-cinza-claro">Sistema</span>
                        @endif
                    </span>
                </div>
            </div>
            
            <div class="space-y-4">
                <div class="flex justify-between">
                    <span class="text-cinza-claro">Data/Hora:</span>
                    <span class="font-medium text-cinza">{{ $auditLog->created_at->format('d/m/Y H:i:s') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-cinza-claro">IP:</span>
                    <span class="font-medium text-cinza">{{ $auditLog->ip_address ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-cinza-claro">User Agent:</span>
                    <span class="font-medium text-cinza text-xs">{{ Str::limit($auditLog->user_agent ?? 'N/A', 30) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-cinza-claro">URL:</span>
                    <span class="font-medium text-cinza text-xs">{{ Str::limit($auditLog->url ?? 'N/A', 30) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-cinza-claro">Tags:</span>
                    <div class="flex flex-wrap gap-1">
                        @if($auditLog->tags)
                            @foreach(json_decode($auditLog->tags, true) as $tag)
                                <x-badge variant="info" size="sm">{{ $tag }}</x-badge>
                            @endforeach
                        @else
                            <span class="text-cinza-claro">Nenhuma</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </x-card>

    <!-- Dados Antigos -->
    @if($auditLog->old_values)
        <x-card title="Dados Antigos">
            <div class="bg-cinza-claro-2 rounded-lg p-4">
                <pre class="text-sm text-cinza whitespace-pre-wrap overflow-x-auto">{{ json_encode($auditLog->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
            </div>
        </x-card>
    @endif

    <!-- Dados Novos -->
    @if($auditLog->new_values)
        <x-card title="Dados Novos">
            <div class="bg-cinza-claro-2 rounded-lg p-4">
                <pre class="text-sm text-cinza whitespace-pre-wrap overflow-x-auto">{{ json_encode($auditLog->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
            </div>
        </x-card>
    @endif

    <!-- Comparação de Dados -->
    @if($auditLog->old_values && $auditLog->new_values)
        <x-card title="Comparação de Dados">
            <div class="space-y-4">
                @foreach($auditLog->new_values as $key => $newValue)
                    @php
                        $oldValue = $auditLog->old_values[$key] ?? null;
                        $hasChanged = $oldValue !== $newValue;
                    @endphp
                    
                    <div class="border border-cinza-claro-2 rounded-lg p-4 {{ $hasChanged ? 'bg-vermelho bg-opacity-5' : '' }}">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="font-medium text-cinza">{{ ucfirst(str_replace('_', ' ', $key)) }}</h4>
                            @if($hasChanged)
                                <x-badge variant="warning" size="sm">Alterado</x-badge>
                            @else
                                <x-badge variant="success" size="sm">Inalterado</x-badge>
                            @endif
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs text-cinza-claro uppercase tracking-wide">Valor Anterior</label>
                                <div class="mt-1 p-2 bg-cinza-claro-2 rounded text-sm text-cinza">
                                    {{ $oldValue ?? 'N/A' }}
                                </div>
                            </div>
                            <div>
                                <label class="text-xs text-cinza-claro uppercase tracking-wide">Novo Valor</label>
                                <div class="mt-1 p-2 bg-cinza-claro-2 rounded text-sm text-cinza">
                                    {{ $newValue ?? 'N/A' }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </x-card>
    @endif

    <!-- Metadados Adicionais -->
    @if($auditLog->metadata)
        <x-card title="Metadados Adicionais">
            <div class="bg-cinza-claro-2 rounded-lg p-4">
                <pre class="text-sm text-cinza whitespace-pre-wrap overflow-x-auto">{{ json_encode($auditLog->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
            </div>
        </x-card>
    @endif

    <!-- Ações -->
    <div class="flex justify-end gap-3">
        <x-button variant="outline" tag="a" href="{{ route('admin.audit.index') }}">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Voltar para Lista
        </x-button>
        
        @if($auditLog->user)
            <x-button variant="outline" tag="a" href="{{ route('admin.audit.user', $auditLog->user_id) }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Ver Logs do Usuário
            </x-button>
        @endif
        
        <x-button variant="primary" onclick="window.print()">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
            </svg>
            Imprimir
        </x-button>
    </div>
</div>

<style>
@media print {
    .no-print {
        display: none !important;
    }
}
</style>
@endsection