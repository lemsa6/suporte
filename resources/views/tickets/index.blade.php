@extends('layouts.app')

@section('title', 'Tickets')

@section('header')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between">
    <div class="flex-grow-1">
        <h2 class="fs-2 fw-bold text-dark mb-1">
            Tickets
        </h2>
        <p class="text-muted">
            Gerencie todos os tickets do sistema
        </p>
    </div>
    <div class="mt-3 mt-md-0">
        <a href="{{ route('tickets.create') }}" class="btn btn-primary d-inline-flex align-items-center">
            <svg class="me-2" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Novo Ticket
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="d-flex flex-column gap-4">
    <!-- Filtros e Busca -->
    <div class="card border-0">
        <div class="card-body">
            <form method="GET" action="{{ route('tickets.index') }}" class="d-flex flex-column gap-4">
                <div class="row g-3">
                    <!-- Busca -->
                    <div class="col-12 col-sm-6 col-lg-3">
                        <label for="search" class="form-label fw-medium text-dark">Buscar</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                            class="form-control"
                            placeholder="Título, cliente, funcionário...">
                    </div>

                    <!-- Status -->
                    <div class="col-12 col-sm-6 col-lg-3">
                        <label for="status" class="form-label fw-medium text-dark">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">Todos os status</option>
                            <option value="aberto" {{ request('status') == 'aberto' ? 'selected' : '' }}>Aberto</option>
                            <option value="em_andamento" {{ request('status') == 'em_andamento' ? 'selected' : '' }}>Em Andamento</option>
                            <option value="resolvido" {{ request('status') == 'resolvido' ? 'selected' : '' }}>Resolvido</option>
                            <option value="fechado" {{ request('status') == 'fechado' ? 'selected' : '' }}>Fechado</option>
                        </select>
                    </div>

                    <!-- Prioridade -->
                    <div class="col-12 col-sm-6 col-lg-3">
                        <label for="priority" class="form-label fw-medium text-dark">Prioridade</label>
                        <select name="priority" id="priority" class="form-select">
                            <option value="">Todas as prioridades</option>
                            <option value="baixa" {{ request('priority') == 'baixa' ? 'selected' : '' }}>Baixa</option>
                            <option value="média" {{ request('priority') == 'média' ? 'selected' : '' }}>Média</option>
                            <option value="alta" {{ request('priority') == 'alta' ? 'selected' : '' }}>Alta</option>
                        </select>
                    </div>

                    <!-- Categoria -->
                    <div class="col-12 col-sm-6 col-lg-3">
                        <label for="category" class="form-label fw-medium text-dark">Categoria</label>
                        <select name="category" id="category" class="form-select">
                            <option value="">Todas as categorias</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <button type="submit" class="btn btn-primary d-inline-flex align-items-center">
                        <svg class="me-2" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Filtrar
                    </button>

                    @if(request('search') || request('status') || request('priority') || request('category'))
                        <a href="{{ route('tickets.index') }}" class="text-decoration-none text-muted">
                            Limpar filtros
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Estatísticas Rápidas -->
    <div class="row g-3">
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon-warning">
                            <svg width="24" height="24" fill="none" stroke="currentColor" class="text-warning" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <div>
                            <h6 class="mb-1 text-muted small">Abertos</h6>
                            <h4 class="mb-0 fw-bold">{{ $stats['open'] ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon-info">
                            <svg width="24" height="24" fill="none" stroke="currentColor" class="text-info" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h6 class="mb-1 text-muted small">Em Andamento</h6>
                            <h4 class="mb-0 fw-bold">{{ $stats['active'] ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon-danger">
                            <svg width="24" height="24" fill="none" stroke="currentColor" class="text-danger" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <div>
                            <h6 class="mb-1 text-muted small">Urgentes</h6>
                            <h4 class="mb-0 fw-bold">{{ $stats['urgent'] ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon-success">
                            <svg width="24" height="24" fill="none" stroke="currentColor" class="text-success" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h6 class="mb-1 text-muted small">Resolvidos</h6>
                            <h4 class="mb-0 fw-bold">{{ $stats['resolved'] ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Tickets -->
    <div class="card border-0">
        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold">Lista de Tickets</h5>
            <div class="d-flex gap-2">
                <button id="apply-bulk-action" class="btn btn-outline-primary btn-sm d-inline-flex align-items-center bulk-action-btn">
                    <svg class="me-1" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Aplicar Ação
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            @if($tickets->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0">
                                    <input type="checkbox" class="form-check-input" id="select-all">
                                </th>
                                <th class="border-0">ID</th>
                                <th class="border-0">Título</th>
                                <th class="border-0">Cliente</th>
                                <th class="border-0">Status</th>
                                <th class="border-0">Prioridade</th>
                                <th class="border-0">Categoria</th>
                                <th class="border-0">Criado</th>
                                <th class="border-0">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tickets as $ticket)
                                <tr class="clickable" onclick="window.location.href='{{ route('tickets.show', $ticket->ticket_number) }}'">
                                    <td class="align-middle" onclick="event.stopPropagation();">
                                        <input type="checkbox" class="form-check-input ticket-checkbox" value="{{ $ticket->ticket_number }}">
                                    </td>
                                    <td class="align-middle">
                                        <span class="fw-medium">#{{ $ticket->ticket_number }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <div>
                                            <div class="fw-medium text-dark">{{ Str::limit($ticket->title, 50) }}</div>
                                            <div class="text-muted small">{{ Str::limit($ticket->description, 60) }}</div>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex align-items-center">
                                            <div class="d-flex align-items-center justify-content-center bg-light rounded-circle me-2" style="width: 32px; height: 32px;">
                                                <span class="fw-medium text-primary">{{ substr($ticket->client->name ?? 'N/A', 0, 1) }}</span>
                                            </div>
                                            <span class="fw-medium">{{ $ticket->client->name ?? 'N/A' }}</span>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        @switch($ticket->status)
                                            @case('aberto')
                                                <span class="badge bg-warning text-dark">Aberto</span>
                                                @break
                                            @case('em_andamento')
                                                <span class="badge bg-info">Em Andamento</span>
                                                @break
                                            @case('resolvido')
                                                <span class="badge bg-success">Resolvido</span>
                                                @break
                                            @case('fechado')
                                                <span class="badge bg-secondary">Fechado</span>
                                                @break
                                            @default
                                                <span class="badge bg-light text-dark">{{ ucfirst($ticket->status) }}</span>
                                        @endswitch
                                    </td>
                                    <td class="align-middle">
                                        @switch($ticket->priority)
                                            @case('baixa')
                                                <span class="badge bg-success">Baixa</span>
                                                @break
                                            @case('média')
                                                <span class="badge bg-warning text-dark">Média</span>
                                                @break
                                            @case('alta')
                                                <span class="badge bg-danger">Alta</span>
                                                @break
                                            @default
                                                <span class="badge bg-light text-dark">{{ ucfirst($ticket->priority) }}</span>
                                        @endswitch
                                    </td>
                                    <td class="align-middle">
                                        <span class="text-muted">{{ $ticket->category->name ?? 'N/A' }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <span class="text-muted">{{ $ticket->created_at->diffForHumans() }}</span>
                                    </td>
                                    <td class="align-middle" onclick="event.stopPropagation();">
                                        <div class="dropdown">
                                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                Ações
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end" id="ticket-actions-menu-{{ $ticket->id }}">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('tickets.show', $ticket->ticket_number) }}">
                                                        <svg class="me-2" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                        </svg>
                                                        Ver Detalhes
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('tickets.edit', $ticket->ticket_number) }}">
                                                        <svg class="me-2" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                        Editar
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <button type="button" class="dropdown-item text-danger" onclick="deleteTicket('{{ $ticket->ticket_number }}')">
                                                        <svg class="me-2" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                        Excluir
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginação -->
                <div class="d-flex justify-content-between align-items-center p-3 border-top">
                    <div class="text-muted small">
                        Mostrando {{ $tickets->firstItem() ?? 0 }} a {{ $tickets->lastItem() ?? 0 }} de {{ $tickets->total() }} resultados
                    </div>
                    <div>
                        {{ $tickets->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <svg width="64" height="64" fill="none" stroke="currentColor" class="text-muted mb-3" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h6 class="text-muted">Nenhum ticket encontrado</h6>
                    <p class="text-muted small">Crie seu primeiro ticket para começar</p>
                    <a href="{{ route('tickets.create') }}" class="btn btn-primary">Criar Ticket</a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal de Ação em Lote -->
<div class="modal fade" id="bulk-action-modal" tabindex="-1" aria-labelledby="bulk-action-modal-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bulk-action-modal-label">Ação em Lote</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja executar esta ação nos tickets selecionados?</p>
                <div id="bulk-action-details" class="alert alert-info">
                    <!-- Detalhes da ação serão inseridos aqui -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="executeBulkAction()">Confirmar</button>
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
        bulkButton.style.display = 'inline-flex';
        bulkButton.textContent = `Aplicar Ação (${checkedBoxes.length})`;
    } else {
        bulkButton.style.display = 'none';
    }
}

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
