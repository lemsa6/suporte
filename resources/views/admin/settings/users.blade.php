@extends('layouts.app')

@section('title', 'Gerenciar Usuários')

@section('header')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between">
    <div class="flex-grow-1">
        <h2 class="fs-2 fw-bold text-dark mb-1">
            Gerenciar Usuários
        </h2>
        <p class="text-muted">
            Gerencie usuários, permissões e configurações de acesso ao sistema
        </p>
    </div>
    <div class="mt-3 mt-md-0">
        <a href="{{ route('users.create') }}" class="btn btn-primary d-inline-flex align-items-center">
            <svg class="me-2" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Novo Usuário
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="d-flex flex-column gap-4">
    <!-- Filtros e Busca -->
    <div class="card border-0">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.settings.users') }}" class="d-flex flex-column gap-4">
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
                            <option value="">Todos</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Ativo</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inativo</option>
                        </select>
                    </div>

                    <!-- Cargo -->
                    <div class="col-12 col-sm-6 col-lg-3">
                        <label for="role" class="form-label fw-medium text-dark">Cargo</label>
                        <select name="role" id="role" class="form-select">
                            <option value="">Todos</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administrador</option>
                            <option value="tecnico" {{ request('role') == 'tecnico' ? 'selected' : '' }}>Técnico</option>
                            <option value="cliente_gestor" {{ request('role') == 'cliente_gestor' ? 'selected' : '' }}>Cliente Gestor</option>
                        </select>
                    </div>

                    <!-- Ações -->
                    <div class="col-12 col-sm-6 col-lg-3 d-flex align-items-end">
                        <div class="d-flex gap-2 w-100">
                            <button type="submit" class="btn btn-primary flex-fill">
                                <svg class="me-2" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Buscar
                            </button>
                            <a href="{{ route('admin.settings.users') }}" class="btn btn-outline-secondary">
                                <svg class="me-2" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Limpar
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de Usuários -->
    <div class="card border-0">
        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold">Lista de Usuários</h5>
            <span class="badge bg-primary">{{ $users->total() }} usuários</span>
        </div>
        <div class="card-body p-0">
            @if($users->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0">Usuário</th>
                                <th class="border-0">Cargo</th>
                                <th class="border-0">Contato</th>
                                <th class="border-0">Status</th>
                                <th class="border-0">Último Acesso</th>
                                <th class="border-0">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                <span class="fw-medium text-primary">{{ substr($user->name, 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <div class="fw-medium text-dark">{{ $user->name }}</div>
                                                <div class="text-muted small">{{ $user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'tecnico' ? 'warning' : 'info') }}">
                                            {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="text-muted small">
                                            @if($user->phone)
                                                <div>{{ $user->phone }}</div>
                                            @endif
                                            <div>{{ $user->email }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }}">
                                            {{ $user->is_active ? 'Ativo' : 'Inativo' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="text-muted small">
                                            {{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Nunca' }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-outline-primary" title="Ver">
                                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </a>
                                            <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-warning" title="Editar">
                                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>
                                            <form method="POST" action="{{ route('users.toggle-status', $user) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-{{ $user->is_active ? 'secondary' : 'success' }}" title="{{ $user->is_active ? 'Desativar' : 'Ativar' }}">
                                                    @if($user->is_active)
                                                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                                                        </svg>
                                                    @else
                                                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                    @endif
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginação -->
                @if($users->hasPages())
                    <div class="card-footer bg-white border-0">
                        {{ $users->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <svg class="text-muted mb-3" width="64" height="64" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                    <h5 class="text-muted">Nenhum usuário encontrado</h5>
                    <p class="text-muted">Não há usuários que correspondam aos filtros aplicados.</p>
                    <a href="{{ route('users.create') }}" class="btn btn-primary">
                        <svg class="me-2" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Criar Primeiro Usuário
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
