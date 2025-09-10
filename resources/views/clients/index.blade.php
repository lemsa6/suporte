@extends('layouts.app')

@section('title', 'Clientes')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div class="mb-4 sm:mb-0">
        <span class="text-sm text-cinza-claro">Clientes</span>
        <h1 class="page-title mt-1">Gerenciar Clientes</h1>
        <p class="text-cinza mt-2">Gerencie todos os clientes do sistema</p>
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
    <h2 class="section-title mb-4">Filtros e Busca</h2>
    <x-card>
        <form method="GET" action="{{ route('clients.index') }}">
            <div class="flex flex-col lg:flex-row lg:items-end gap-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 flex-1">
                    <!-- Busca -->
                    <x-input 
                        label="Buscar"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Nome, empresa, email..."
                    />

                    <!-- Status -->
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Todos os status</option>
                            <option value="ativo" {{ request('status') === 'ativo' ? 'selected' : '' }}>Ativo</option>
                            <option value="inativo" {{ request('status') === 'inativo' ? 'selected' : '' }}>Inativo</option>
                        </select>
                    </div>


                    <!-- Ações -->
                    <div class="flex items-end">
                        <x-button type="submit" variant="primary" size="sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Filtrar
                        </x-button>
                    </div>
                </div>

                @if(request('search') || request('status'))
                    <div class="flex items-center gap-4">
                        <span class="text-cinza-claro text-sm">
                            Filtros aplicados: 
                            @if(request('search')) Busca: "{{ request('search') }}" @endif
                            @if(request('status')) Status: {{ request('status') }} @endif
                        </span>
                        <a href="{{ route('clients.index') }}" class="text-cinza-claro hover:text-cinza text-sm">
                            Limpar filtros
                        </a>
                    </div>
                @endif
            </div>
        </form>
    </x-card>

    <!-- Estatísticas Rápidas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <x-stat-card 
            title="Total"
            :value="$stats['total'] ?? 0"
            color="primary"
        >
            <svg class="w-5 h-5 text-roxo" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
        </x-stat-card>

        <x-stat-card 
            title="Ativos"
            :value="$stats['active'] ?? 0"
            color="success"
        >
            <svg class="w-5 h-5 text-verde" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </x-stat-card>

        <x-stat-card 
            title="Empresas"
            :value="$stats['companies'] ?? 0"
            color="info"
        >
            <svg class="w-5 h-5 text-roxo-det" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
        </x-stat-card>

        <x-stat-card 
            title="Com Tickets"
            :value="$stats['with_tickets'] ?? 0"
            color="warning"
        >
            <svg class="w-5 h-5 text-amarelo" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
        </x-stat-card>
    </div>

    <!-- Lista de Clientes -->
    <h2 class="section-title mb-4">Lista de Clientes</h2>
    <x-card>
        <div class="card-header-actions">
            <x-button id="apply-bulk-action" variant="outline" size="sm" class="bulk-action-btn hidden">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                Aplicar Ação
            </x-button>
        </div>
        @if($clients->count() > 0)
            <x-table striped hover class="client-table-auto">
                <thead>
                    <tr>
                        <th class="client-table-cell">
                            <div class="flex items-center gap-2">
                                <input type="checkbox" class="form-check-input" id="select-all">
                                <span class="client-table-header-id">ID</span>
                            </div>
                        </th>
                        <th class="client-table-cell-flex">Cliente</th>
                        <th class="client-table-cell-flex">Contato</th>
                        <th class="client-table-cell">Status</th>
                        <th class="client-table-cell">Tickets</th>
                        <th class="client-table-cell">Criado</th>
                        <th class="client-table-cell">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clients as $client)
                        <tr class="client-table-row" onclick="window.location.href='{{ route('clients.show', $client) }}'">
                            <td onclick="event.stopPropagation();" class="client-table-cell">
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" class="form-check-input client-checkbox" value="{{ $client->id }}">
                                    <span class="client-table-id-text">#{{ $client->id }}</span>
                                </div>
                            </td>
                            <td class="client-table-cell-flex">
                                <div class="flex items-center">
                                    <div class="client-table-client-avatar">
                                        <span class="client-table-client-initial">{{ substr($client->trade_name ?? $client->company_name, 0, 1) }}</span>
                                    </div>
                                    <div class="space-y-1">
                                        <div class="client-table-title-text">{{ $client->trade_name ?? $client->company_name }}</div>
                                        @if($client->trade_name)
                                            <div class="client-table-description-text">{{ $client->company_name }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="client-table-cell-flex">
                                <div>
                                    <div class="font-medium">{{ $client->email }}</div>
                                    @if($client->phone)
                                        <div class="client-table-description-text">{{ $client->phone }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="client-table-cell">
                                @if($client->is_active)
                                    <span class="badge bg-verde-claro text-cinza">Ativo</span>
                                @else
                                    <span class="badge bg-cinza-claro-2 text-cinza">Inativo</span>
                                @endif
                            </td>
                            <td class="client-table-cell">
                                <span class="badge bg-roxo text-branco">{{ $client->tickets_count ?? 0 }}</span>
                            </td>
                            <td class="client-table-cell">
                                <span class="client-table-date-text">{{ $client->created_at->diffForHumans() }}</span>
                            </td>
                            <td class="client-table-cell" onclick="event.stopPropagation();">
                                <div class="flex gap-1">
                                    <x-button variant="outline" size="sm" tag="a" href="{{ route('clients.show', $client) }}" title="Ver Detalhes">
                                        <svg class="client-table-action-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </x-button>
                                    <x-button variant="outline" size="sm" tag="a" href="{{ route('clients.edit', $client) }}" title="Editar">
                                        <svg class="client-table-action-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </x-button>
                                    <x-button variant="outline" size="sm" type="button" onclick="deleteClient('{{ $client->id }}')" title="Excluir" class="text-vermelho">
                                        <svg class="client-table-action-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
            <div class="flex justify-between items-center p-4 border-t border-cinza-claro">
                <div class="text-cinza-claro text-sm">
                    Mostrando {{ $clients->firstItem() ?? 0 }} a {{ $clients->lastItem() ?? 0 }} de {{ $clients->total() }} resultados
                </div>
                <div>
                    {{ $clients->links() }}
                </div>
            </div>
        @else
            <div class="text-center py-12">
                <svg width="64" height="64" fill="none" stroke="currentColor" class="client-table-empty-icon" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <h6 class="client-table-empty-title">Nenhum cliente encontrado</h6>
                <p class="client-table-empty-description">Crie seu primeiro cliente para começar</p>
                <x-button variant="primary" tag="a" href="{{ route('clients.create') }}">
                    Criar Cliente
                </x-button>
            </div>
        @endif
    </x-card>
</div>

<!-- Modal de Ação em Lote -->
<div class="fixed inset-0 bg-cinza-escuro bg-opacity-50 hidden z-50" id="bulk-action-modal">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="flex items-center justify-between p-6 border-b border-cinza-claro">
                <h3 class="text-lg font-semibold text-cinza">Ação em Lote</h3>
                <button type="button" onclick="closeBulkActionModal()" class="text-cinza-claro hover:text-cinza">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div class="p-6">
                <div class="mb-4">
                    <p class="text-cinza mb-4">Selecione a ação que deseja executar nos <span id="selected-count" class="font-semibold">0</span> clientes selecionados:</p>
                    
                    <div class="space-y-3">
                        <!-- Seleção de Ação -->
                        <div>
                            <label class="block text-sm font-medium text-cinza mb-2">Ação</label>
                            <select id="bulk-action-select" class="w-full px-3 py-2 border border-cinza-claro rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent">
                                <option value="">Selecione uma ação...</option>
                                <option value="activate">Ativar Clientes</option>
                                <option value="deactivate">Desativar Clientes</option>
                                <option value="delete" class="text-vermelho">Excluir Clientes</option>
                            </select>
                        </div>
                        
                        <!-- Confirmação para exclusão -->
                        <div id="delete-confirmation" class="hidden bg-vermelho-claro border border-vermelho text-vermelho-escuro px-4 py-3 rounded-md">
                            <div class="flex">
                                <svg class="w-5 h-5 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                <div>
                                    <p class="font-medium">Atenção!</p>
                                    <p class="text-sm">Esta ação não pode ser desfeita. Os clientes selecionados serão permanentemente excluídos.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end gap-3 p-6 border-t border-cinza-claro">
                <button type="button" onclick="closeBulkActionModal()" class="px-4 py-2 text-cinza-claro hover:text-cinza border border-cinza-claro rounded-md hover:bg-cinza-claro transition-colors">
                    Cancelar
                </button>
                <button type="button" onclick="executeBulkAction()" id="confirm-bulk-action" class="px-4 py-2 bg-roxo text-branco rounded-md hover:bg-roxo-claro transition-colors disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                    Confirmar
                </button>
            </div>
        </div>
    </div>
</div>


@push('scripts')
<script>
// Variáveis globais
let selectedClients = [];

// Inicialização quando o DOM estiver carregado
document.addEventListener('DOMContentLoaded', function() {
    initializeBulkActions();
});

function initializeBulkActions() {
    // Seleção em lote
    const selectAllCheckbox = document.getElementById('select-all');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.client-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkActionButton();
        });
    }

    // Checkboxes individuais
    document.querySelectorAll('.client-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActionButton);
    });

    // Botão de ação em lote
    const bulkButton = document.getElementById('apply-bulk-action');
    if (bulkButton) {
        bulkButton.addEventListener('click', showBulkActionModal);
    }

    // Seleção de ação no modal
    const actionSelect = document.getElementById('bulk-action-select');
    if (actionSelect) {
        actionSelect.addEventListener('change', handleActionChange);
    }
}

function updateBulkActionButton() {
    const checkedBoxes = document.querySelectorAll('.client-checkbox:checked');
    const bulkButton = document.getElementById('apply-bulk-action');
    
    if (checkedBoxes.length > 0) {
        bulkButton.classList.remove('hidden');
        bulkButton.textContent = `Aplicar Ação (${checkedBoxes.length})`;
        selectedClients = Array.from(checkedBoxes).map(cb => cb.value);
    } else {
        bulkButton.classList.add('hidden');
        selectedClients = [];
    }
}

function showBulkActionModal() {
    const checkedBoxes = document.querySelectorAll('.client-checkbox:checked');
    if (checkedBoxes.length === 0) {
        showAlert('Selecione pelo menos um cliente para executar a ação.', 'warning');
        return;
    }
    
    // Atualizar contador
    document.getElementById('selected-count').textContent = checkedBoxes.length;
    
    // Resetar formulário
    resetBulkActionForm();
    
    // Mostrar modal
    document.getElementById('bulk-action-modal').classList.remove('hidden');
}

function resetBulkActionForm() {
    // Resetar seleção de ação
    document.getElementById('bulk-action-select').value = '';
    
    // Esconder confirmação de exclusão
    document.getElementById('delete-confirmation').classList.add('hidden');
    
    // Desabilitar botão de confirmação
    document.getElementById('confirm-bulk-action').disabled = true;
}

function handleActionChange() {
    const action = document.getElementById('bulk-action-select').value;
    const confirmButton = document.getElementById('confirm-bulk-action');
    
    // Esconder confirmação de exclusão
    document.getElementById('delete-confirmation').classList.add('hidden');
    
    if (action) {
        confirmButton.disabled = false;
        
        // Mostrar confirmação de exclusão se necessário
        if (action === 'delete') {
            document.getElementById('delete-confirmation').classList.remove('hidden');
        }
    } else {
        confirmButton.disabled = true;
    }
}

function closeBulkActionModal() {
    document.getElementById('bulk-action-modal').classList.add('hidden');
    resetBulkActionForm();
}

function executeBulkAction() {
    const action = document.getElementById('bulk-action-select').value;
    const checkedBoxes = document.querySelectorAll('.client-checkbox:checked');
    const clientIds = Array.from(checkedBoxes).map(cb => cb.value);
    
    if (!action) {
        showAlert('Selecione uma ação para executar.', 'warning');
        return;
    }
    
    if (clientIds.length === 0) {
        showAlert('Selecione pelo menos um cliente.', 'warning');
        return;
    }
    
    // Preparar dados da requisição
    const formData = {
        action: action,
        client_ids: clientIds,
        _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    };
    
    // Desabilitar botão durante a requisição
    const confirmButton = document.getElementById('confirm-bulk-action');
    const originalText = confirmButton.textContent;
    confirmButton.disabled = true;
    confirmButton.textContent = 'Processando...';
    
    // Enviar requisição
    fetch('/clients/bulk-action', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': formData._token
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert(data.message, 'success');
            // Recarregar a página para mostrar as mudanças
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showAlert(data.message || 'Erro ao executar ação em lote.', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Erro ao executar ação em lote. Tente novamente.', 'error');
    })
    .finally(() => {
        // Reabilitar botão
        confirmButton.disabled = false;
        confirmButton.textContent = originalText;
    });
}

// Excluir cliente individual
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
                showAlert('Cliente excluído com sucesso!', 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showAlert('Erro ao excluir cliente: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Erro ao excluir cliente', 'error');
        });
    }
}

// Sistema de alertas
function showAlert(message, type = 'info') {
    // Remover alertas existentes
    const existingAlerts = document.querySelectorAll('.alert-toast');
    existingAlerts.forEach(alert => alert.remove());
    
    // Criar novo alerta
    const alert = document.createElement('div');
    alert.className = `alert-toast fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg max-w-sm ${getAlertClasses(type)}`;
    alert.innerHTML = `
        <div class="flex items-center">
            <div class="flex-shrink-0">
                ${getAlertIcon(type)}
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium">${message}</p>
            </div>
            <div class="ml-auto pl-3">
                <button onclick="this.parentElement.parentElement.parentElement.remove()" class="text-current hover:opacity-75">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        </div>
    `;
    
    document.body.appendChild(alert);
    
    // Auto-remover após 5 segundos
    setTimeout(() => {
        if (alert.parentElement) {
            alert.remove();
        }
    }, 5000);
}

function getAlertClasses(type) {
    const classes = {
        'success': 'bg-verde-claro border border-verde text-verde-escuro',
        'error': 'bg-vermelho-claro border border-vermelho text-vermelho-escuro',
        'warning': 'bg-amarelo-claro border border-amarelo text-amarelo-escuro',
        'info': 'bg-azul-claro border border-azul text-azul-escuro'
    };
    return classes[type] || classes.info;
}

function getAlertIcon(type) {
    const icons = {
        'success': '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>',
        'error': '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>',
        'warning': '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>',
        'info': '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>'
    };
    return icons[type] || icons.info;
}

// Fechar modal ao clicar fora dele
document.addEventListener('click', function(event) {
    const modal = document.getElementById('bulk-action-modal');
    if (event.target === modal) {
        closeBulkActionModal();
    }
});

// Fechar modal com ESC
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeBulkActionModal();
    }
});
</script>
@endpush
@endsection

