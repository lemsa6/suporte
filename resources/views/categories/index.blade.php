@extends('layouts.app')

@section('title', 'Categorias')

@section('header')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between">
    <div class="flex-grow-1">
        <h2 class="fs-2 fw-bold text-dark mb-1">
            Categorias
        </h2>
        <p class="text-muted">
            Gerencie as categorias de tickets do sistema
        </p>
    </div>
    <div class="mt-3 mt-md-0">
        <a href="{{ route('categories.create') }}" class="btn btn-primary d-inline-flex align-items-center">
            <svg class="me-2" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Nova Categoria
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="d-flex flex-column gap-4">
    <!-- Estatísticas Rápidas -->
    <div class="row g-3">
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon-primary">
                            <svg width="24" height="24" fill="none" stroke="currentColor" class="text-primary" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                        </div>
                        <div>
                            <h6 class="mb-1 text-muted small">Total</h6>
                            <h4 class="mb-0 fw-bold">{{ $categories->count() }}</h4>
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
                            <h6 class="mb-1 text-muted small">Ativas</h6>
                            <h4 class="mb-0 fw-bold">{{ $categories->where('is_active', true)->count() }}</h4>
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div>
                            <h6 class="mb-1 text-muted small">Com Tickets</h6>
                            <h4 class="mb-0 fw-bold">{{ $categories->where('tickets_count', '>', 0)->count() }}</h4>
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <div>
                            <h6 class="mb-1 text-muted small">Pendentes</h6>
                            <h4 class="mb-0 fw-bold">{{ $categories->where('is_active', false)->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Categorias -->
    <div class="card border-0">
        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold">Lista de Categorias</h5>
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
            @if($categories->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0">
                                    <input type="checkbox" class="form-check-input" id="select-all">
                                </th>
                                <th class="border-0">Nome</th>
                                <th class="border-0">Descrição</th>
                                <th class="border-0">Cor</th>
                                <th class="border-0">Status</th>
                                <th class="border-0">Tickets</th>
                                <th class="border-0">Criada</th>
                                <th class="border-0">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $category)
                                <tr>
                                    <td class="align-middle">
                                        <input type="checkbox" class="form-check-input category-checkbox" value="{{ $category->id }}">
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex align-items-center">
                                                                                         <div class="category-color-avatar" style="background-color: {{ $category->color }};">
                                                 <svg width="16" height="16" fill="none" stroke="currentColor" class="text-white" viewBox="0 0 24 24">
                                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                                 </svg>
                                             </div>
                                            <span class="fw-medium">{{ $category->name }}</span>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <span class="text-muted">{{ Str::limit($category->description, 50) }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex align-items-center">
                                                                                         <div class="category-color-preview" style="background-color: {{ $category->color }};"></div>
                                            <span class="text-muted small">{{ $category->color }}</span>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        @if($category->is_active)
                                            <span class="badge bg-success">Ativa</span>
                                        @else
                                            <span class="badge bg-secondary">Inativa</span>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        <span class="badge bg-primary">{{ $category->tickets_count ?? 0 }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <span class="text-muted">{{ $category->created_at->diffForHumans() }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <div class="dropdown">
                                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                Ações
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('categories.edit', $category) }}">
                                                        <svg class="me-2" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                        Editar
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <button type="button" class="dropdown-item text-danger" onclick="deleteCategory('{{ $category->id }}')">
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
            @else
                <div class="text-center py-5">
                    <svg width="64" height="64" fill="none" stroke="currentColor" class="text-muted mb-3" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    <h6 class="text-muted">Nenhuma categoria encontrada</h6>
                    <p class="text-muted small">Crie sua primeira categoria para começar</p>
                    <a href="{{ route('categories.create') }}" class="btn btn-primary">Criar Categoria</a>
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
                <p>Tem certeza que deseja executar esta ação nas categorias selecionadas?</p>
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
    const checkboxes = document.querySelectorAll('.category-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
    updateBulkActionButton();
});

document.querySelectorAll('.category-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', updateBulkActionButton);
});

function updateBulkActionButton() {
    const checkedBoxes = document.querySelectorAll('.category-checkbox:checked');
    const bulkButton = document.getElementById('apply-bulk-action');
    
    if (checkedBoxes.length > 0) {
        bulkButton.style.display = 'inline-flex';
        bulkButton.textContent = `Aplicar Ação (${checkedBoxes.length})`;
    } else {
        bulkButton.style.display = 'none';
    }
}

// Excluir categoria
function deleteCategory(categoryId) {
    if (confirm('Tem certeza que deseja excluir esta categoria?')) {
        fetch(`/categories/${categoryId}`, {
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
                alert('Erro ao excluir categoria: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erro ao excluir categoria');
        });
    }
}

// Ações em lote
function closeBulkActionModal() {
    const modal = bootstrap.Modal.getInstance(document.getElementById('bulk-action-modal'));
    modal.hide();
}

function executeBulkAction() {
    const checkedBoxes = document.querySelectorAll('.category-checkbox:checked');
    const categoryIds = Array.from(checkedBoxes).map(cb => cb.value);
    
    // Aqui você pode implementar a lógica para executar ações em lote
    console.log('Executando ação em lote para categorias:', categoryIds);
    
    closeBulkActionModal();
}
</script>
@endpush
@endsection
