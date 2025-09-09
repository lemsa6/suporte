@extends('layouts.app')

@section('title', 'Tickets')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div class="mb-4 sm:mb-0">
        <span class="text-sm text-gray-500">Tickets</span>
        <h1 class="page-title mt-1">Gerenciar Tickets</h1>
        <p class="text-gray-600 mt-2">Gerencie todos os tickets do sistema</p>
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
                        <a href="{{ route('tickets.index') }}" class="text-gray-500 hover:text-gray-700 text-sm">
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
            <svg class="w-5 h-5 text-warning-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
        </x-stat-card>

        <x-stat-card 
            title="Em Andamento"
            :value="$stats['active'] ?? 0"
            color="info"
        >
            <svg class="w-5 h-5 text-info-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </x-stat-card>

        <x-stat-card 
            title="Urgentes"
            :value="$stats['urgent'] ?? 0"
            color="danger"
        >
            <svg class="w-5 h-5 text-danger-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
        </x-stat-card>

        <x-stat-card 
            title="Resolvidos"
            :value="$stats['resolved'] ?? 0"
            color="success"
        >
            <svg class="w-5 h-5 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                        <span class="ticket-table-client-initial">{{ substr($ticket->client->trade_name ?? $ticket->client->company_name ?? 'N/A', 0, 1) }}</span>
                                    </div>
                                    <span class="font-medium">{{ $ticket->client->trade_name ?? $ticket->client->company_name ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="ticket-table-cell">
                                @switch($ticket->status)
                                    @case('aberto')
                                        <x-badge variant="warning">Aberto</x-badge>
                                        @break
                                    @case('em_andamento')
                                        <x-badge variant="info">Em Andamento</x-badge>
                                        @break
                                    @case('resolvido')
                                        <x-badge variant="success">Resolvido</x-badge>
                                        @break
                                    @case('fechado')
                                        <x-badge variant="default">Fechado</x-badge>
                                        @break
                                    @default
                                        <x-badge variant="default">{{ ucfirst($ticket->status) }}</x-badge>
                                @endswitch
                            </td>
                            <td class="ticket-table-cell">
                                @switch($ticket->priority)
                                    @case('baixa')
                                        <x-badge variant="success">Baixa</x-badge>
                                        @break
                                    @case('média')
                                        <x-badge variant="warning">Média</x-badge>
                                        @break
                                    @case('alta')
                                        <x-badge variant="danger">Alta</x-badge>
                                        @break
                                    @default
                                        <x-badge variant="default">{{ ucfirst($ticket->priority) }}</x-badge>
                                @endswitch
                            </td>
                            <td class="ticket-table-cell">
                                <span class="ticket-table-category-text">{{ $ticket->category->name ?? 'N/A' }}</span>
                            </td>
                            <td class="ticket-table-cell">
                                <span class="ticket-table-date-text">{{ $ticket->created_at->diffForHumans() }}</span>
                            </td>
                            <td class="ticket-table-cell" onclick="event.stopPropagation();">
                                <div class="d-flex gap-1">
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
                                    <x-button variant="outline" size="sm" type="button" onclick="deleteTicket('{{ $ticket->ticket_number }}')" title="Excluir" class="text-danger">
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
            <div class="flex justify-between items-center p-4 border-t border-gray-200">
                <div class="text-gray-500 text-sm">
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
                <p>Tem certeza que deseja executar esta ação nos tickets selecionados?</p>
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
    const checkboxes = document.querySelectorAll('.ticket-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
    updateBulkActionButton();
});

document.querySelectorAll('.ticket-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', updateBulkActionButton);
});

function updateBulkActionButton() {
    const checkedBoxes = document.querySelectorAll('.ticket-checkbox:checked');
    const bulkButton = document.getElementById('apply-bulk-action');
    
    if (checkedBoxes.length > 0) {
        bulkButton.classList.remove('hidden');
        bulkButton.textContent = `Aplicar Ação (${checkedBoxes.length})`;
    } else {
        bulkButton.classList.add('hidden');
    }
}

// Evento de clique no botão de ação em lote
document.getElementById('apply-bulk-action').addEventListener('click', function() {
    const checkedBoxes = document.querySelectorAll('.ticket-checkbox:checked');
    if (checkedBoxes.length === 0) {
        alert('Selecione pelo menos um ticket para executar a ação.');
        return;
    }
    
    // Mostrar modal de confirmação
    const modal = new bootstrap.Modal(document.getElementById('bulk-action-modal'));
    modal.show();
});

// Excluir ticket
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
                window.location.reload();
            } else {
                alert('Erro ao excluir ticket: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erro ao excluir ticket');
        });
    }
}

// Ações em lote
function closeBulkActionModal() {
    const modal = bootstrap.Modal.getInstance(document.getElementById('bulk-action-modal'));
    modal.hide();
}

function executeBulkAction() {
    const checkedBoxes = document.querySelectorAll('.ticket-checkbox:checked');
    const ticketIds = Array.from(checkedBoxes).map(cb => cb.value);
    
    // Aqui você pode implementar a lógica para executar ações em lote
    console.log('Executando ação em lote para tickets:', ticketIds);
    
    closeBulkActionModal();
}
</script>
@endpush
@endsection
