@extends('layouts.app')

@section('title', 'Clientes')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div class="mb-4 sm:mb-0">
        <span class="text-sm text-gray-500">Clientes</span>
        <h1 class="page-title mt-1">Gerenciar Clientes</h1>
        <p class="text-gray-600 mt-2">Gerencie todos os clientes do sistema</p>
    </div>
    <x-button 
        variant="primary" 
        tag="a"
        href="{{ route('clients.create') }}"
    >
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Novo Cliente
    </x-button>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Filtros e Busca -->
    <x-card title="Filtros e Busca">
        <form method="GET" action="{{ route('clients.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Busca -->
                <x-input 
                    label="Buscar"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Nome, empresa, email..."
                />

                <!-- Status -->
                <x-select 
                    label="Status"
                    name="status"
                    :options="[
                        '' => 'Todos os status',
                        'ativo' => 'Ativo',
                        'inativo' => 'Inativo'
                    ]"
                    :selected="request('status')"
                />

                <!-- Tipo -->
                <x-select 
                    label="Tipo"
                    name="type"
                    :options="[
                        '' => 'Todos os tipos',
                        'empresa' => 'Empresa',
                        'pessoa_fisica' => 'Pessoa Física'
                    ]"
                    :selected="request('type')"
                />

                <!-- Ações -->
                <div class="flex items-end">
                    <x-button type="submit" variant="primary" class="w-full">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Filtrar
                    </x-button>
                </div>
            </div>

            @if(request('search') || request('status') || request('type'))
                <div class="flex justify-between items-center">
                    <span class="text-gray-500 text-sm">
                        Filtros aplicados: 
                        @if(request('search')) Busca: "{{ request('search') }}" @endif
                        @if(request('status')) Status: {{ request('status') }} @endif
                        @if(request('type')) Tipo: {{ request('type') }} @endif
                    </span>
                    <a href="{{ route('clients.index') }}" class="text-gray-500 hover:text-gray-700 text-sm">
                        Limpar filtros
                    </a>
                </div>
            @endif
        </form>
    </x-card>

    <!-- Estatísticas Rápidas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <x-stat-card 
            title="Total"
            :value="$clients->total()"
            color="primary"
        >
            <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
        </x-stat-card>

        <x-stat-card 
            title="Ativos"
            :value="$clients->where('is_active', true)->count()"
            color="success"
        >
            <svg class="w-5 h-5 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </x-stat-card>

        <x-stat-card 
            title="Empresas"
            :value="$clients->where('type', 'empresa')->count()"
            color="info"
        >
            <svg class="w-5 h-5 text-info-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
        </x-stat-card>

        <x-stat-card 
            title="Com Tickets"
            :value="$clients->where('tickets_count', '>', 0)->count()"
            color="warning"
        >
            <svg class="w-5 h-5 text-warning-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
        </x-stat-card>
    </div>

    <!-- Lista de Clientes -->
    <x-card title="Lista de Clientes">
        <x-slot name="actions">
            <x-button id="apply-bulk-action" variant="outline" size="sm" class="bulk-action-btn hidden">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                Aplicar Ação
            </x-button>
        </x-slot>
        @if($clients->count() > 0)
            <x-table striped hover>
                <thead>
                    <tr>
                        <th class="w-24">
                            <div class="flex items-center gap-2">
                                <input type="checkbox" class="form-check-input" id="select-all">
                                <span class="text-xs font-medium text-gray-700 whitespace-nowrap">ID</span>
                            </div>
                        </th>
                        <th>Cliente</th>
                        <th>Tipo</th>
                        <th>Contato</th>
                        <th>Status</th>
                        <th>Tickets</th>
                        <th>Criado</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clients as $client)
                        <tr class="cursor-pointer hover:bg-gray-50" onclick="window.location.href='{{ route('clients.show', $client) }}'">
                            <td onclick="event.stopPropagation();" class="w-24">
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" class="form-check-input client-checkbox" value="{{ $client->id }}">
                                    <span class="font-medium text-xs whitespace-nowrap">#{{ $client->id }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center mr-2">
                                        <span class="text-sm font-medium text-primary-600">{{ substr($client->name, 0, 1) }}</span>
                                    </div>
                                    <div class="space-y-1">
                                        <div class="font-medium text-gray-900">{{ $client->name }}</div>
                                        @if($client->company_name)
                                            <div class="text-gray-500 text-sm">{{ $client->company_name }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($client->type === 'empresa')
                                    <x-badge variant="info">Empresa</x-badge>
                                @else
                                    <x-badge variant="default">Pessoa Física</x-badge>
                                @endif
                            </td>
                            <td>
                                <div>
                                    <div class="font-medium">{{ $client->email }}</div>
                                    @if($client->phone)
                                        <div class="text-gray-500 text-sm">{{ $client->phone }}</div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($client->is_active)
                                    <x-badge variant="success">Ativo</x-badge>
                                @else
                                    <x-badge variant="default">Inativo</x-badge>
                                @endif
                            </td>
                            <td>
                                <x-badge variant="primary">{{ $client->tickets_count ?? 0 }}</x-badge>
                            </td>
                            <td>
                                <span class="text-gray-500">{{ $client->created_at->diffForHumans() }}</span>
                            </td>
                            <td onclick="event.stopPropagation();">
                                <div class="d-flex gap-1">
                                    <x-button variant="outline" size="sm" tag="a" href="{{ route('clients.show', $client) }}" title="Ver Detalhes">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </x-button>
                                    <x-button variant="outline" size="sm" tag="a" href="{{ route('clients.edit', $client) }}" title="Editar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </x-button>
                                    <x-button variant="outline" size="sm" type="button" onclick="deleteClient('{{ $client->id }}')" title="Excluir" class="text-danger">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </x-button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </x-table>

            <!-- Paginação -->
            <div class="flex justify-between items-center p-4 border-t border-gray-200">
                <div class="text-gray-500 text-sm">
                    Mostrando {{ $clients->firstItem() ?? 0 }} a {{ $clients->lastItem() ?? 0 }} de {{ $clients->total() }} resultados
                </div>
                <div>
                    {{ $clients->links() }}
                </div>
            </div>
        @else
            <div class="text-center py-12">
                <svg width="64" height="64" fill="none" stroke="currentColor" class="text-gray-300 mb-4 mx-auto" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <h6 class="text-gray-500 mb-2">Nenhum cliente encontrado</h6>
                <p class="text-gray-400 text-sm mb-4">Crie seu primeiro cliente para começar</p>
                <x-button variant="primary" tag="a" href="{{ route('clients.create') }}">
                    Criar Cliente
                </x-button>
            </div>
        @endif
    </x-card>
</div>

<!-- Modal de Ação em Lote -->
<div class="modal fade" id="bulk-action-modal" tabindex="-1" aria-labelledby="bulk-action-modal-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bulk-action-modal-label">Ação em Lote</h5>
                <x-button variant="outline" size="sm" type="button" data-bs-dismiss="modal" aria-label="Close">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </x-button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja executar esta ação nos clientes selecionados?</p>
                <div id="bulk-action-details" class="alert alert-info">
                    <!-- Detalhes da ação serão inseridos aqui -->
                </div>
            </div>
            <div class="modal-footer">
                <x-button variant="secondary" type="button" data-bs-dismiss="modal">Cancelar</x-button>
                <x-button variant="primary" type="button" onclick="executeBulkAction()">Confirmar</x-button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Seleção em lote
document.getElementById('select-all').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.client-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
    updateBulkActionButton();
});

document.querySelectorAll('.client-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', updateBulkActionButton);
});

function updateBulkActionButton() {
    const checkedBoxes = document.querySelectorAll('.client-checkbox:checked');
    const bulkButton = document.getElementById('apply-bulk-action');
    
    if (checkedBoxes.length > 0) {
        bulkButton.classList.remove('hidden');
        bulkButton.textContent = `Aplicar Ação (${checkedBoxes.length})`;
    } else {
        bulkButton.classList.add('hidden');
    }
}

// Excluir cliente
function deleteClient(clientId) {
    if (confirm('Tem certeza que deseja excluir este cliente?')) {
        fetch(`/clients/${clientId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert('Erro ao excluir cliente: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erro ao excluir cliente');
        });
    }
}

// Ações em lote
function closeBulkActionModal() {
    const modal = bootstrap.Modal.getInstance(document.getElementById('bulk-action-modal'));
    modal.hide();
}

function executeBulkAction() {
    const checkedBoxes = document.querySelectorAll('.client-checkbox:checked');
    const clientIds = Array.from(checkedBoxes).map(cb => cb.value);
    
    // Aqui você pode implementar a lógica para executar ações em lote
    console.log('Executando ação em lote para clientes:', clientIds);
    
    closeBulkActionModal();
}
</script>
@endpush
@endsection

