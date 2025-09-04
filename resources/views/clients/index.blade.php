@extends('layouts.app')

@section('title', 'Clientes')

@section('header')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between">
    <div class="flex-grow-1">
        <h2 class="fs-2 fw-bold text-dark mb-1">
            Clientes
        </h2>
        <p class="text-muted">
            Gerencie todos os clientes do sistema
        </p>
    </div>
    <div class="mt-3 mt-md-0">
        <a href="{{ route('clients.create') }}" class="btn btn-primary d-inline-flex align-items-center">
            <svg class="me-2" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Novo Cliente
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="d-flex flex-column gap-4">
    <!-- Filtros e Busca -->
    <div class="card border-0">
        <div class="card-body">
            <form method="GET" action="{{ route('clients.index') }}" class="d-flex flex-column gap-4">
                <div class="row g-3">
                    <!-- Busca -->
                    <div class="col-12 col-sm-6 col-lg-3">
                        <label for="search" class="form-label fw-medium text-dark">Buscar</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                            class="form-control"
                            placeholder="Nome, empresa, email...">
                    </div>

                    <!-- Status -->
                    <div class="col-12 col-sm-6 col-lg-3">
                        <label for="status" class="form-label fw-medium text-dark">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">Todos os status</option>
                            <option value="ativo" {{ request('status') == 'ativo' ? 'selected' : '' }}>Ativo</option>
                            <option value="inativo" {{ request('status') == 'inativo' ? 'selected' : '' }}>Inativo</option>
                        </select>
                    </div>

                    <!-- Tipo -->
                    <div class="col-12 col-sm-6 col-lg-3">
                        <label for="type" class="form-label fw-medium text-dark">Tipo</label>
                        <select name="type" id="type" class="form-select">
                            <option value="">Todos os tipos</option>
                            <option value="empresa" {{ request('type') == 'empresa' ? 'selected' : '' }}>Empresa</option>
                            <option value="pessoa_fisica" {{ request('type') == 'pessoa_fisica' ? 'selected' : '' }}>Pessoa Física</option>
                        </select>
                    </div>

                    <!-- Ações -->
                    <div class="col-12 col-sm-6 col-lg-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary d-inline-flex align-items-center">
                            <svg class="me-2" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Filtrar
                        </button>
                    </div>
                </div>

                @if(request('search') || request('status') || request('type'))
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">
                            Filtros aplicados: 
                            @if(request('search')) Busca: "{{ request('search') }}" @endif
                            @if(request('status')) Status: {{ request('status') }} @endif
                            @if(request('type')) Tipo: {{ request('type') }} @endif
                        </span>
                        <a href="{{ route('clients.index') }}" class="text-decoration-none text-muted">
                            Limpar filtros
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Estatísticas Rápidas -->
    <div class="row g-3">
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon-primary">
                            <svg width="24" height="24" fill="none" stroke="currentColor" class="text-primary" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h6 class="mb-1 text-muted small">Total</h6>
                            <h4 class="mb-0 fw-bold">{{ $clients->total() }}</h4>
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
                            <h6 class="mb-1 text-muted small">Ativos</h6>
                            <h4 class="mb-0 fw-bold">{{ $clients->where('is_active', true)->count() }}</h4>
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <div>
                            <h6 class="mb-1 text-muted small">Empresas</h6>
                            <h4 class="mb-0 fw-bold">{{ $clients->where('type', 'empresa')->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon-warning">
                            <svg width="24" height="24" fill="none" stroke="currentColor" class="text-warning" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div>
                            <h6 class="mb-1 text-muted small">Com Tickets</h6>
                            <h4 class="mb-0 fw-bold">{{ $clients->where('tickets_count', '>', 0)->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Clientes -->
    <div class="card border-0">
        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold">Lista de Clientes</h5>
            <div class="d-flex gap-2">
                <button id="apply-bulk-action" class="btn btn-outline-primary btn-sm d-inline-flex align-items-center" style="display: none;">
                    <svg class="me-1" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Aplicar Ação
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            @if($clients->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0">
                                    <input type="checkbox" class="form-check-input" id="select-all">
                                </th>
                                <th class="border-0">Cliente</th>
                                <th class="border-0">Tipo</th>
                                <th class="border-0">Contato</th>
                                <th class="border-0">Status</th>
                                <th class="border-0">Tickets</th>
                                <th class="border-0">Criado</th>
                                <th class="border-0">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($clients as $client)
                                <tr>
                                    <td class="align-middle">
                                        <input type="checkbox" class="form-check-input client-checkbox" value="{{ $client->id }}">
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex align-items-center">
                                            <div class="d-flex align-items-center justify-content-center bg-light rounded-circle me-2" style="width: 40px; height: 40px;">
                                                <span class="fw-medium text-primary">{{ substr($client->name, 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <div class="fw-medium text-dark">{{ $client->name }}</div>
                                                @if($client->company_name)
                                                    <div class="text-muted small">{{ $client->company_name }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        @if($client->type === 'empresa')
                                            <span class="badge bg-info">Empresa</span>
                                        @else
                                            <span class="badge bg-secondary">Pessoa Física</span>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        <div>
                                            <div class="fw-medium">{{ $client->email }}</div>
                                            @if($client->phone)
                                                <div class="text-muted small">{{ $client->phone }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        @if($client->is_active)
                                            <span class="badge bg-success">Ativo</span>
                                        @else
                                            <span class="badge bg-secondary">Inativo</span>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        <span class="badge bg-primary">{{ $client->tickets_count ?? 0 }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <span class="text-muted">{{ $client->created_at->diffForHumans() }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('clients.show', $client) }}" class="btn btn-secondary btn-sm">
                                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </a>
                                            <a href="{{ route('clients.edit', $client) }}" class="btn btn-secondary btn-sm">
                                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>
                                            <button type="button" class="btn btn-secondary btn-sm" onclick="deleteClient('{{ $client->id }}')">
                                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
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
                        Mostrando {{ $clients->firstItem() ?? 0 }} a {{ $clients->lastItem() ?? 0 }} de {{ $clients->total() }} resultados
                    </div>
                    <div>
                        {{ $clients->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <svg width="64" height="64" fill="none" stroke="currentColor" class="text-muted mb-3" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <h6 class="text-muted">Nenhum cliente encontrado</h6>
                    <p class="text-muted small">Crie seu primeiro cliente para começar</p>
                    <a href="{{ route('clients.create') }}" class="btn btn-primary">Criar Cliente</a>
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
                <p>Tem certeza que deseja executar esta ação nos clientes selecionados?</p>
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
        bulkButton.style.display = 'inline-flex';
        bulkButton.textContent = `Aplicar Ação (${checkedBoxes.length})`;
    } else {
        bulkButton.style.display = 'none';
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

