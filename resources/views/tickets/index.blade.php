@extends('layouts.app')

@section('title', 'Tickets')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div class="mb-4 sm:mb-0">
        <span class="text-sm text-cinza-claro">Tickets</span>
        <h1 class="page-title mt-1">Gerenciar Tickets</h1>
        <p class="text-cinza mt-2">Gerencie todos os tickets do sistema</p>
    </div>
    <x-button 
        variant="primary" 
        tag="a"
        href="{{ route('tickets.create') }}"
    >
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Novo Ticket
    </x-button>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Filtros e Busca -->
    <h2 class="section-title mb-4">Filtros e Busca</h2>
    <x-card>
        <form method="GET" action="{{ route('tickets.index') }}">
            <div class="flex flex-col lg:flex-row lg:items-end gap-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 flex-1">
                    <!-- Busca -->
                    <x-input 
                        label="Buscar"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Título, cliente, funcionário..."
                    />

                    <!-- Status -->
                    <x-select 
                        label="Status"
                        name="status"
                        :options="[
                            '' => 'Todos os status',
                            'aberto' => 'Aberto',
                            'em_andamento' => 'Em Andamento',
                            'resolvido' => 'Resolvido',
                            'fechado' => 'Fechado'
                        ]"
                        :selected="request('status')"
                    />

                    <!-- Prioridade -->
                    <x-select 
                        label="Prioridade"
                        name="priority"
                        :options="[
                            '' => 'Todas as prioridades',
                            'baixa' => 'Baixa',
                            'média' => 'Média',
                            'alta' => 'Alta'
                        ]"
                        :selected="request('priority')"
                    />

                    <!-- Categoria -->
                    <x-select 
                        label="Categoria"
                        name="category"
                        :options="collect($categories)->mapWithKeys(fn($cat) => [$cat->id => $cat->name])->prepend('Todas as categorias', '')"
                        :selected="request('category')"
                    />
                </div>

                <div class="flex items-end gap-4">
                    <x-button type="submit" variant="primary" size="sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Filtrar
                    </x-button>

                    @if(request('search') || request('status') || request('priority') || request('category'))
                        <a href="{{ route('tickets.index') }}" class="text-cinza-claro hover:text-cinza text-sm">
                            Limpar filtros
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </x-card>

    <!-- Estatísticas Rápidas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <x-stat-card 
            title="Abertos"
            :value="$stats['open'] ?? 0"
            color="warning"
        >
            <svg class="w-5 h-5 text-amarelo" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
        </x-stat-card>

        <x-stat-card 
            title="Em Andamento"
            :value="$stats['active'] ?? 0"
            color="info"
        >
            <svg class="w-5 h-5 text-roxo-det" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </x-stat-card>

        <x-stat-card 
            title="Urgentes"
            :value="$stats['urgent'] ?? 0"
            color="danger"
        >
            <svg class="w-5 h-5 text-vermelho" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
        </x-stat-card>

        <x-stat-card 
            title="Resolvidos"
            :value="$stats['resolved'] ?? 0"
            color="success"
        >
            <svg class="w-5 h-5 text-verde" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </x-stat-card>
    </div>

    <!-- Lista de Tickets -->
    <h2 class="section-title mb-4">Lista de Tickets</h2>
    <x-card>
        <x-slot name="actions">
            <x-button id="apply-bulk-action" variant="outline" size="sm" class="bulk-action-btn hidden">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                Aplicar Ação
            </x-button>
        </x-slot>
        @if($tickets->count() > 0)
            <x-table striped hover class="ticket-table-auto">
                <thead>
                    <tr>
                        <th class="ticket-table-cell">
                            <div class="flex items-center gap-2">
                                <input type="checkbox" class="form-check-input" id="select-all">
                                <span class="ticket-table-header-id">ID</span>
                            </div>
                        </th>
                        <th class="ticket-table-cell-flex">Título</th>
                        <th class="ticket-table-cell-flex">Cliente</th>
                        <th class="ticket-table-cell">Status</th>
                        <th class="ticket-table-cell">Prioridade</th>
                        <th class="ticket-table-cell">Categoria</th>
                        <th class="ticket-table-cell">Criado</th>
                        <th class="ticket-table-cell">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tickets as $ticket)
                        <tr class="ticket-table-row" onclick="window.location.href='{{ route('tickets.show', $ticket->ticket_number) }}'">
                            <td onclick="event.stopPropagation();" class="ticket-table-cell">
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" class="form-check-input ticket-checkbox" value="{{ $ticket->ticket_number }}">
                                    <span class="ticket-table-id-text">#{{ $ticket->ticket_number }}</span>
                                </div>
                            </td>
                            <td class="ticket-table-cell-flex">
                                <div class="space-y-1">
                                    <div class="ticket-table-title-text">{{ Str::limit($ticket->title, 50) }}</div>
                                    <div class="ticket-table-description-text">{{ Str::limit($ticket->description, 60) }}</div>
                                </div>
                            </td>
                            <td class="ticket-table-cell-flex">
                                <div class="flex items-center">
                                    <div class="ticket-table-client-avatar">
                                        <span class="ticket-table-client-initial">{{ $ticket->client->display_initial }}</span>
                                    </div>
                                    <span class="font-medium">{{ $ticket->client->display_name }}</span>
                                </div>
                            </td>
                            <td class="ticket-table-cell">
                                <x-ticket-status-badge :status="$ticket->status" />
                            </td>
                            <td class="ticket-table-cell">
                                <x-ticket-priority-badge :priority="$ticket->priority" />
                            </td>
                            <td class="ticket-table-cell">
                                <x-category-badge :category="$ticket->category" />
                            </td>
                            <td class="ticket-table-cell">
                                <span class="ticket-table-date-text">{{ $ticket->created_at->diffForHumans() }}</span>
                            </td>
                            <td class="ticket-table-cell" onclick="event.stopPropagation();">
                                <div class="flex gap-1">
                                    <x-button variant="outline" size="sm" tag="a" href="{{ route('tickets.show', $ticket->ticket_number) }}" title="Ver Detalhes">
                                        <svg class="ticket-table-action-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </x-button>
                                    <x-button variant="outline" size="sm" tag="a" href="{{ route('tickets.edit', $ticket->ticket_number) }}" title="Editar">
                                        <svg class="ticket-table-action-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </x-button>
                                    <x-button variant="outline" size="sm" type="button" onclick="deleteTicket('{{ $ticket->ticket_number }}')" title="Excluir" class="text-vermelho">
                                        <svg class="ticket-table-action-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    Mostrando {{ $tickets->firstItem() ?? 0 }} a {{ $tickets->lastItem() ?? 0 }} de {{ $tickets->total() }} resultados
                </div>
                <div>
                    {{ $tickets->links() }}
                </div>
            </div>
        @else
            <div class="text-center py-12">
                <svg width="64" height="64" fill="none" stroke="currentColor" class="ticket-table-empty-icon" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h6 class="ticket-table-empty-title">Nenhum ticket encontrado</h6>
                <p class="ticket-table-empty-description">Crie seu primeiro ticket para começar</p>
                <x-button variant="primary" tag="a" href="{{ route('tickets.create') }}">
                    Criar Ticket
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
                    <p class="text-cinza mb-4">Selecione a ação que deseja executar nos <span id="selected-count" class="font-semibold">0</span> tickets selecionados:</p>
                    
                    <div class="space-y-3">
                        <!-- Seleção de Ação -->
                        <div>
                            <label class="block text-sm font-medium text-cinza mb-2">Ação</label>
                            <select id="bulk-action-select" class="w-full px-3 py-2 border border-cinza-claro rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent">
                                <option value="">Selecione uma ação...</option>
                                <option value="change_status">Alterar Status</option>
                                <option value="change_priority">Alterar Prioridade</option>
                                <option value="assign">Atribuir a Técnico</option>
                                <option value="mark_urgent">Marcar como Urgente</option>
                                <option value="unmark_urgent">Desmarcar como Urgente</option>
                                <option value="delete" class="text-vermelho">Excluir Tickets</option>
                            </select>
                        </div>
                        
                        <!-- Opções específicas da ação -->
                        <div id="action-options" class="hidden">
                            <!-- Status -->
                            <div id="status-options" class="hidden">
                                <label class="block text-sm font-medium text-cinza mb-2">Novo Status</label>
                                <select id="bulk-status" class="w-full px-3 py-2 border border-cinza-claro rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent">
                                    <option value="aberto">Aberto</option>
                                    <option value="em_andamento">Em Andamento</option>
                                    <option value="resolvido">Resolvido</option>
                                    <option value="fechado">Fechado</option>
                                </select>
                            </div>
                            
                            <!-- Prioridade -->
                            <div id="priority-options" class="hidden">
                                <label class="block text-sm font-medium text-cinza mb-2">Nova Prioridade</label>
                                <select id="bulk-priority" class="w-full px-3 py-2 border border-cinza-claro rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent">
                                    <option value="baixa">Baixa</option>
                                    <option value="média">Média</option>
                                    <option value="alta">Alta</option>
                                </select>
                            </div>
                            
                            <!-- Técnico -->
                            <div id="assign-options" class="hidden">
                                <label class="block text-sm font-medium text-cinza mb-2">Atribuir a</label>
                                <select id="bulk-assigned-to" class="w-full px-3 py-2 border border-cinza-claro rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent">
                                    <option value="">Selecione um técnico...</option>
                                    @foreach($technicians as $tech)
                                        <option value="{{ $tech->id }}">{{ $tech->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <!-- Confirmação para exclusão -->
                        <div id="delete-confirmation" class="hidden bg-vermelho-claro border border-vermelho text-vermelho-escuro px-4 py-3 rounded-md">
                            <div class="flex">
                                <svg class="w-5 h-5 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                <div>
                                    <p class="font-medium">Atenção!</p>
                                    <p class="text-sm">Esta ação não pode ser desfeita. Os tickets selecionados serão permanentemente excluídos.</p>
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
let selectedTickets = [];

// Inicialização quando o DOM estiver carregado
document.addEventListener('DOMContentLoaded', function() {
    initializeBulkActions();
});

function initializeBulkActions() {
    // Seleção em lote
    const selectAllCheckbox = document.getElementById('select-all');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.ticket-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkActionButton();
        });
    }

    // Checkboxes individuais
    document.querySelectorAll('.ticket-checkbox').forEach(checkbox => {
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
    const checkedBoxes = document.querySelectorAll('.ticket-checkbox:checked');
    const bulkButton = document.getElementById('apply-bulk-action');
    
    if (checkedBoxes.length > 0) {
        bulkButton.classList.remove('hidden');
        bulkButton.textContent = `Aplicar Ação (${checkedBoxes.length})`;
        selectedTickets = Array.from(checkedBoxes).map(cb => cb.value);
    } else {
        bulkButton.classList.add('hidden');
        selectedTickets = [];
    }
}

function showBulkActionModal() {
    const checkedBoxes = document.querySelectorAll('.ticket-checkbox:checked');
    if (checkedBoxes.length === 0) {
        showAlert('Selecione pelo menos um ticket para executar a ação.', 'warning');
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
    
    // Esconder todas as opções
    document.getElementById('action-options').classList.add('hidden');
    document.getElementById('status-options').classList.add('hidden');
    document.getElementById('priority-options').classList.add('hidden');
    document.getElementById('assign-options').classList.add('hidden');
    document.getElementById('delete-confirmation').classList.add('hidden');
    
    // Desabilitar botão de confirmação
    document.getElementById('confirm-bulk-action').disabled = true;
}

function handleActionChange() {
    const action = document.getElementById('bulk-action-select').value;
    const actionOptions = document.getElementById('action-options');
    const confirmButton = document.getElementById('confirm-bulk-action');
    
    // Esconder todas as opções específicas
    document.getElementById('status-options').classList.add('hidden');
    document.getElementById('priority-options').classList.add('hidden');
    document.getElementById('assign-options').classList.add('hidden');
    document.getElementById('delete-confirmation').classList.add('hidden');
    
    if (action) {
        actionOptions.classList.remove('hidden');
        confirmButton.disabled = false;
        
        // Mostrar opções específicas baseadas na ação
        switch (action) {
            case 'change_status':
                document.getElementById('status-options').classList.remove('hidden');
                break;
            case 'change_priority':
                document.getElementById('priority-options').classList.remove('hidden');
                break;
            case 'assign':
                document.getElementById('assign-options').classList.remove('hidden');
                break;
            case 'delete':
                document.getElementById('delete-confirmation').classList.remove('hidden');
                break;
        }
    } else {
        actionOptions.classList.add('hidden');
        confirmButton.disabled = true;
    }
}

function closeBulkActionModal() {
    document.getElementById('bulk-action-modal').classList.add('hidden');
    resetBulkActionForm();
}

function executeBulkAction() {
    const action = document.getElementById('bulk-action-select').value;
    const checkedBoxes = document.querySelectorAll('.ticket-checkbox:checked');
    const ticketIds = Array.from(checkedBoxes).map(cb => cb.value);
    
    if (!action) {
        showAlert('Selecione uma ação para executar.', 'warning');
        return;
    }
    
    if (ticketIds.length === 0) {
        showAlert('Selecione pelo menos um ticket.', 'warning');
        return;
    }
    
    // Validar campos obrigatórios
    if (!validateBulkAction(action)) {
        return;
    }
    
    // Preparar dados da requisição
    const formData = {
        action: action,
        ticket_ids: ticketIds,
        _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    };
    
    // Adicionar campos específicos baseados na ação
    switch (action) {
        case 'change_status':
            formData.status = document.getElementById('bulk-status').value;
            break;
        case 'change_priority':
            formData.priority = document.getElementById('bulk-priority').value;
            break;
        case 'assign':
            formData.assigned_to = document.getElementById('bulk-assigned-to').value;
            break;
    }
    
    // Desabilitar botão durante a requisição
    const confirmButton = document.getElementById('confirm-bulk-action');
    const originalText = confirmButton.textContent;
    confirmButton.disabled = true;
    confirmButton.textContent = 'Processando...';
    
    // Enviar requisição
    fetch('/tickets/bulk-action', {
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

function validateBulkAction(action) {
    switch (action) {
        case 'change_status':
            const status = document.getElementById('bulk-status').value;
            if (!status) {
                showAlert('Selecione um status.', 'warning');
                return false;
            }
            break;
        case 'change_priority':
            const priority = document.getElementById('bulk-priority').value;
            if (!priority) {
                showAlert('Selecione uma prioridade.', 'warning');
                return false;
            }
            break;
        case 'assign':
            const assignedTo = document.getElementById('bulk-assigned-to').value;
            if (!assignedTo) {
                showAlert('Selecione um técnico.', 'warning');
                return false;
            }
            break;
    }
    return true;
}

// Excluir ticket individual
function deleteTicket(ticketHash) {
    if (confirm('Tem certeza que deseja excluir este ticket?')) {
        fetch(`/tickets/${ticketHash}`, {
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
                showAlert('Ticket excluído com sucesso!', 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showAlert('Erro ao excluir ticket: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Erro ao excluir ticket', 'error');
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
