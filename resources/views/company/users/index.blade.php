@extends('layouts.app')

@section('title', 'Funcionários da Empresa')

@section('header')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between">
    <div class="flex-grow-1">
        <h2 class="fs-2 fw-bold text-dark mb-1">
            Funcionários da Empresa
        </h2>
        <p class="text-muted">
            Gerencie os funcionários da sua empresa
        </p>
    </div>
    <div class="mt-3 mt-md-0">
        <a href="{{ route('company.users.create') }}" class="btn btn-primary d-inline-flex align-items-center">
            <svg class="me-2" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Novo Funcionário
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="d-flex flex-column gap-4">
    <!-- Filtros e Busca -->
    <div class="card border-0">
        <div class="card-body">
            <form method="GET" action="{{ route('company.users.index') }}" class="d-flex flex-column gap-4">
                <div class="row g-3">
                    <!-- Busca -->
                    <div class="col-12 col-sm-6 col-lg-3">
                        <label for="search" class="form-label fw-medium text-dark">Buscar</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                            class="form-control"
                            placeholder="Nome, email, cargo...">
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

                    <!-- Cargo -->
                    <div class="col-12 col-sm-6 col-lg-3">
                        <label for="role" class="form-label fw-medium text-dark">Cargo</label>
                        <select name="role" id="role" class="form-select">
                            <option value="">Todos os cargos</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administrador</option>
                            <option value="manager" {{ request('role') == 'manager' ? 'selected' : '' }}>Gerente</option>
                            <option value="employee" {{ request('role') == 'employee' ? 'selected' : '' }}>Funcionário</option>
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

                @if(request('search') || request('status') || request('role'))
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">
                            Filtros aplicados: 
                            @if(request('search')) Busca: "{{ request('search') }}" @endif
                            @if(request('status')) Status: {{ request('status') }} @endif
                            @if(request('role')) Cargo: {{ request('role') }} @endif
                        </span>
                        <a href="{{ route('company.users.index') }}" class="text-decoration-none text-muted">
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
                            <h4 class="mb-0 fw-bold">{{ $users->total() }}</h4>
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
                            <h4 class="mb-0 fw-bold">{{ $users->where('is_active', true)->count() }}</h4>
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h6 class="mb-1 text-muted small">Administradores</h6>
                            <h4 class="mb-0 fw-bold">{{ $users->where('role', 'admin')->count() }}</h4>
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h6 class="mb-1 text-muted small">Com Tickets</h6>
                            <h4 class="mb-0 fw-bold">{{ $users->where('tickets_count', '>', 0)->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Usuários -->
    <div class="card border-0">
        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold">Lista de Funcionários</h5>
        </div>
        <div class="card-body p-0">
            @if($users->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0">Funcionário</th>
                                <th class="border-0">Cargo</th>
                                <th class="border-0">Contato</th>
                                <th class="border-0">Status</th>
                                <th class="border-0">Tickets</th>
                                <th class="border-0">Último Acesso</th>
                                <th class="border-0">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td class="align-middle">
                                        <div class="d-flex align-items-center">
                                            <div class="d-flex align-items-center justify-content-center bg-light rounded-circle me-2" style="width: 40px; height: 40px;">
                                                <span class="fw-medium text-primary">{{ substr($user->name, 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <div class="fw-medium text-dark">{{ $user->name }}</div>
                                                <div class="text-muted small">{{ $user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        @switch($user->role)
                                            @case('admin')
                                                <span class="badge bg-danger">Administrador</span>
                                                @break
                                            @case('manager')
                                                <span class="badge bg-warning text-dark">Gerente</span>
                                                @break
                                            @case('employee')
                                                <span class="badge bg-info">Funcionário</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">{{ ucfirst($user->role) }}</span>
                                        @endswitch
                                    </td>
                                    <td class="align-middle">
                                        <div>
                                            <div class="fw-medium">{{ $user->email }}</div>
                                            @if($user->phone)
                                                <div class="text-muted small">{{ $user->phone }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        @if($user->is_active)
                                            <span class="badge bg-success">Ativo</span>
                                        @else
                                            <span class="badge bg-secondary">Inativo</span>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        <span class="badge bg-primary">{{ $user->tickets_count ?? 0 }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <span class="text-muted">{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Nunca' }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <div class="dropdown">
                                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                Ações
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('company.users.edit', $user) }}">
                                                        <svg class="me-2" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                        Editar
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <button type="button" class="dropdown-item text-danger" onclick="deleteUser('{{ $user->id }}')">
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
                        Mostrando {{ $users->firstItem() ?? 0 }} a {{ $users->lastItem() ?? 0 }} de {{ $users->total() }} resultados
                    </div>
                    <div>
                        {{ $users->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <svg width="64" height="64" fill="none" stroke="currentColor" class="text-muted mb-3" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <h6 class="text-muted">Nenhum funcionário encontrado</h6>
                    <p class="text-muted small">Adicione seu primeiro funcionário para começar</p>
                    <a href="{{ route('company.users.create') }}" class="btn btn-primary">Adicionar Funcionário</a>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
// Excluir usuário
function deleteUser(userId) {
    if (confirm('Tem certeza que deseja excluir este funcionário?')) {
        fetch(`/company/users/${userId}`, {
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
                alert('Erro ao excluir funcionário: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erro ao excluir funcionário');
        });
    }
}
</script>
@endpush
@endsection
