@extends('layouts.app')

@section('title', 'Usuários da Empresa')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div class="mb-4 sm:mb-0">
        <span class="text-sm text-cinza-claro">Empresa</span>
        <h1 class="page-title mt-1">Usuários da Empresa</h1>
        <p class="text-cinza mt-2">Gerencie os usuários da sua empresa</p>
    </div>
    <x-button variant="primary" tag="a" href="{{ route('company.users.create') }}">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        Adicionar Usuário
    </x-button>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Filtros -->
    <x-card title="Filtros">
        <form method="GET" action="{{ route('company.users.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Busca -->
                <div>
                    <label for="search" class="block text-sm font-medium text-cinza mb-2">Buscar</label>
                    <input type="text" id="search" name="search" 
                        class="w-full px-3 py-2 border border-cinza-claro-2 rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent"
                        value="{{ request('search') }}"
                        placeholder="Nome, email ou cargo...">
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-cinza mb-2">Status</label>
                    <select id="status" name="status" 
                        class="w-full px-3 py-2 border border-cinza-claro-2 rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent">
                        <option value="">Todos os status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Ativo</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inativo</option>
                    </select>
                </div>

                <!-- Cargo -->
                <div>
                    <label for="role" class="block text-sm font-medium text-cinza mb-2">Cargo</label>
                    <select id="role" name="role" 
                        class="w-full px-3 py-2 border border-cinza-claro-2 rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent">
                        <option value="">Todos os cargos</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administrador</option>
                        <option value="manager" {{ request('role') == 'manager' ? 'selected' : '' }}>Gerente</option>
                        <option value="technician" {{ request('role') == 'technician' ? 'selected' : '' }}>Técnico</option>
                        <option value="client" {{ request('role') == 'client' ? 'selected' : '' }}>Cliente</option>
                    </select>
                </div>
            </div>

            <!-- Botões -->
            <div class="flex justify-end gap-3 pt-4 border-t border-cinza-claro-2">
                <x-button variant="outline" tag="a" href="{{ route('company.users.index') }}">
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

    <!-- Lista de Usuários -->
    <x-card title="Usuários ({{ $users->total() }})">
        @if($users->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-cinza-claro-2">
                    <thead class="bg-cinza-claro-2">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-cinza-claro uppercase tracking-wider">
                                <input type="checkbox" id="select-all" class="rounded border-cinza-claro-2 text-roxo focus:ring-roxo">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-cinza-claro uppercase tracking-wider">Usuário</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-cinza-claro uppercase tracking-wider">Cargo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-cinza-claro uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-cinza-claro uppercase tracking-wider">Último Acesso</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-cinza-claro uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-cinza-claro-2">
                        @foreach($users as $user)
                            <tr class="hover:bg-cinza-claro-2">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="checkbox" name="selected_users[]" value="{{ $user->id }}" 
                                        class="user-checkbox rounded border-cinza-claro-2 text-roxo focus:ring-roxo">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-roxo bg-opacity-10 rounded-full flex items-center justify-center mr-3">
                                            <span class="text-roxo font-medium text-sm">{{ substr($user->name, 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-cinza">{{ $user->name }}</div>
                                            <div class="text-sm text-cinza-claro">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <x-badge variant="{{ $user->role === 'admin' ? 'danger' : ($user->role === 'manager' ? 'warning' : ($user->role === 'technician' ? 'info' : 'success')) }}">
                                        {{ ucfirst($user->role) }}
                                    </x-badge>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <x-badge variant="{{ $user->is_active ? 'success' : 'danger' }}">
                                        {{ $user->is_active ? 'Ativo' : 'Inativo' }}
                                    </x-badge>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-cinza-claro">
                                    {{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Nunca' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <x-button variant="link" size="sm" tag="a" href="{{ route('company.users.show', $user->id) }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </x-button>
                                        <x-button variant="link" size="sm" tag="a" href="{{ route('company.users.edit', $user->id) }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </x-button>
                                        <x-button variant="link" size="sm" onclick="toggleUserStatus({{ $user->id }})">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                                            </svg>
                                        </x-button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginação -->
            <div class="mt-6">
                {{ $users->links() }}
            </div>
        @else
            <div class="text-center py-8">
                <svg class="w-16 h-16 text-cinza-claro mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
                <h3 class="text-lg font-medium text-cinza mb-2">Nenhum usuário encontrado</h3>
                <p class="text-cinza-claro mb-4">Não há usuários cadastrados com os filtros aplicados.</p>
                <x-button variant="primary" tag="a" href="{{ route('company.users.create') }}">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Adicionar Primeiro Usuário
                </x-button>
            </div>
        @endif
    </x-card>
</div>

<!-- Modal de Ação em Lote -->
<div id="bulkActionModal" class="fixed inset-0 bg-cinza-escuro bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-cinza mb-4">Ação em Lote</h3>
                <p class="text-cinza-claro mb-4">Selecione a ação que deseja executar nos usuários selecionados:</p>
                
                <div class="space-y-3">
                    <label class="flex items-center">
                        <input type="radio" name="bulk_action" value="activate" class="mr-3 text-roxo focus:ring-roxo">
                        <span class="text-cinza">Ativar usuários</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="bulk_action" value="deactivate" class="mr-3 text-roxo focus:ring-roxo">
                        <span class="text-cinza">Desativar usuários</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="bulk_action" value="delete" class="mr-3 text-vermelho focus:ring-vermelho">
                        <span class="text-cinza">Excluir usuários</span>
                    </label>
                </div>
                
                <div class="flex justify-end gap-3 mt-6">
                    <x-button variant="outline" onclick="closeBulkModal()">
                        Cancelar
                    </x-button>
                    <x-button variant="primary" onclick="executeBulkAction()">
                        Executar Ação
                    </x-button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Selecionar todos
document.getElementById('select-all').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.user-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
    updateBulkActions();
});

// Atualizar ações em lote
document.querySelectorAll('.user-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', updateBulkActions);
});

function updateBulkActions() {
    const selectedCount = document.querySelectorAll('.user-checkbox:checked').length;
    const bulkButton = document.getElementById('bulkActionsButton');
    
    if (bulkButton) {
        bulkButton.style.display = selectedCount > 0 ? 'block' : 'none';
        bulkButton.textContent = `${selectedCount} usuário(s) selecionado(s)`;
    }
}

function openBulkModal() {
    document.getElementById('bulkActionModal').classList.remove('hidden');
}

function closeBulkModal() {
    document.getElementById('bulkActionModal').classList.add('hidden');
}

function executeBulkAction() {
    const selectedUsers = Array.from(document.querySelectorAll('.user-checkbox:checked')).map(cb => cb.value);
    const action = document.querySelector('input[name="bulk_action"]:checked')?.value;
    
    if (!action || selectedUsers.length === 0) {
        alert('Selecione uma ação e pelo menos um usuário.');
        return;
    }
    
    if (confirm(`Tem certeza que deseja ${action} ${selectedUsers.length} usuário(s)?`)) {
        // Implementar ação em lote
        console.log('Executando ação:', action, 'em usuários:', selectedUsers);
        closeBulkModal();
    }
}

function toggleUserStatus(userId) {
    if (confirm('Tem certeza que deseja alterar o status deste usuário?')) {
        // Implementar toggle de status
        console.log('Alterando status do usuário:', userId);
    }
}
</script>
@endsection