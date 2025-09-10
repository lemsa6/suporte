@extends('layouts.app')

@section('title', 'Categorias')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div class="mb-4 sm:mb-0">
        <span class="text-sm text-cinza-claro">Categorias</span>
        <h1 class="page-title mt-1">Gerenciar Categorias</h1>
        <p class="text-cinza mt-2">Gerencie as categorias de tickets do sistema</p>
    </div>
    <x-button 
        variant="primary" 
        tag="a"
        href="{{ route('categories.create') }}"
    >
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Nova Categoria
    </x-button>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Estatísticas Rápidas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <x-stat-card 
            title="Total"
            :value="$categories->count()"
            color="primary"
        >
            <svg class="w-5 h-5 text-roxo" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
            </svg>
        </x-stat-card>

        <x-stat-card 
            title="Ativas"
            :value="$categories->where('is_active', true)->count()"
            color="success"
        >
            <svg class="w-5 h-5 text-verde" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </x-stat-card>

        <x-stat-card 
            title="Com Tickets"
            :value="$categories->where('tickets_count', '>', 0)->count()"
            color="info"
        >
            <svg class="w-5 h-5 text-roxo-det" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
        </x-stat-card>

        <x-stat-card 
            title="Pendentes"
            :value="$categories->where('is_active', false)->count()"
            color="warning"
        >
            <svg class="w-5 h-5 text-amarelo" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833.192 2.5 1.732 2.5z"></path>
            </svg>
        </x-stat-card>
    </div>

    <!-- Lista de Categorias -->
    <h2 class="section-title mb-4">Lista de Categorias</h2>
    <x-card>
        <div class="card-header-actions">
            <x-button id="apply-bulk-action" variant="outline" size="sm" class="bulk-action-btn hidden">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                Aplicar Ação
            </x-button>
        </div>
        @if($categories->count() > 0)
            <x-table striped hover>
                <thead>
                    <tr>
                        <th class="w-24">
                            <div class="flex items-center gap-2">
                                <input type="checkbox" class="form-check-input" id="select-all">
                                <span class="text-xs font-medium text-cinza whitespace-nowrap">ID</span>
                            </div>
                        </th>
                        <th>Nome</th>
                        <th>Descrição</th>
                        <th>Cor</th>
                        <th>Status</th>
                        <th>Tickets</th>
                        <th>Criada</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                        <tr class="cursor-pointer hover:bg-cinza-claro-2" onclick="window.location.href='{{ route('categories.edit', $category) }}'">
                            <td onclick="event.stopPropagation();" class="w-24">
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" class="form-check-input category-checkbox" value="{{ $category->id }}">
                                    <span class="font-medium text-xs whitespace-nowrap">#{{ $category->id }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center mr-2 category-color-avatar" style="--category-color: {{ $category->color }};">
                                        <svg width="16" height="16" fill="none" stroke="currentColor" class="text-white" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                        </svg>
                                    </div>
                                    <span class="font-medium">{{ $category->name }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="text-cinza-claro">{{ Str::limit($category->description, 50) }}</span>
                            </td>
                            <td>
                                <div class="flex items-center">
                                    <div class="w-4 h-4 rounded-full mr-2 category-color-badge" style="--category-color: {{ $category->color }};"></div>
                                    <span class="text-cinza-claro text-sm">{{ $category->color }}</span>
                                </div>
                            </td>
                            <td>
                                @if($category->is_active)
                                    <x-badge variant="success">Ativa</x-badge>
                                @else
                                    <x-badge variant="default">Inativa</x-badge>
                                @endif
                            </td>
                            <td>
                                <x-badge variant="primary">{{ $category->tickets_count ?? 0 }}</x-badge>
                            </td>
                            <td>
                                <span class="text-cinza-claro">{{ $category->created_at->diffForHumans() }}</span>
                            </td>
                            <td onclick="event.stopPropagation();">
                                <div class="flex gap-1">
                                    <x-button variant="outline" size="sm" tag="a" href="{{ route('categories.edit', $category) }}" title="Editar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </x-button>
                                    <x-button variant="outline" size="sm" type="button" onclick="deleteCategory('{{ $category->id }}')" title="Excluir" class="text-vermelho">
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
        @else
            <div class="text-center py-12">
                <svg width="64" height="64" fill="none" stroke="currentColor" class="text-cinza-claro-2 mb-4 mx-auto" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                </svg>
                <h6 class="text-cinza-claro mb-2">Nenhuma categoria encontrada</h6>
                <p class="text-cinza-claro-2 text-sm mb-4">Crie sua primeira categoria para começar</p>
                <x-button variant="primary" tag="a" href="{{ route('categories.create') }}">
                    Criar Categoria
                </x-button>
            </div>
        @endif
    </x-card>
</div>

<!-- Modal de Ação em Lote -->
<div class="hidden" id="bulk-action-modal">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bulk-action-modal-content">
            <div class="bulk-action-modal-header">
                <h3 class="text-lg font-medium text-cinza">Ação em Lote</h3>
                <button type="button" onclick="closeBulkActionModal()" class="text-cinza-claro hover:text-cinza">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="bulk-action-modal-body">
                <p class="text-cinza mb-4">Tem certeza que deseja executar esta ação nas categorias selecionadas?</p>
                <div id="bulk-action-details" class="bg-roxo-claro border border-roxo text-roxo-escuro px-4 py-3 rounded-md">
                    <!-- Detalhes da ação serão inseridos aqui -->
                </div>
            </div>
            <div class="bulk-action-modal-footer">
                <x-button variant="secondary" type="button" onclick="closeBulkActionModal()">Cancelar</x-button>
                <x-button variant="primary" type="button" onclick="executeBulkAction()">Confirmar</x-button>
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
        bulkButton.classList.remove('hidden');
        bulkButton.textContent = `Aplicar Ação (${checkedBoxes.length})`;
    } else {
        bulkButton.classList.add('hidden');
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
