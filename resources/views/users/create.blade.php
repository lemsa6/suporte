@extends('layouts.app')

@section('title', 'Novo Usuário')

@section('header')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between">
    <div class="flex-grow-1">
        <h2 class="fs-2 fw-bold text-dark mb-1">
            Novo Usuário
        </h2>
        <p class="text-muted">
            Cadastre um novo usuário no sistema
        </p>
    </div>
    <div class="mt-3 mt-md-0">
        <a href="{{ route('settings.users') }}" class="btn btn-outline-secondary d-inline-flex align-items-center">
            <svg class="me-2" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Voltar
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="d-flex flex-column gap-4">
    <form action="{{ route('users.store') }}" method="POST" class="d-flex flex-column gap-4">
        @csrf
        
        <!-- Informações Básicas -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0 fw-semibold">Informações Básicas</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <!-- Nome -->
                    <div class="col-12 col-md-6">
                        <label for="name" class="form-label fw-medium text-dark">Nome Completo *</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                            class="form-control @error('name') is-invalid @enderror"
                            placeholder="Digite o nome completo" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="col-12 col-md-6">
                        <label for="email" class="form-label fw-medium text-dark">E-mail *</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                            class="form-control @error('email') is-invalid @enderror"
                            placeholder="Digite o e-mail" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Senha -->
                    <div class="col-12 col-md-6">
                        <label for="password" class="form-label fw-medium text-dark">Senha *</label>
                        <input type="password" name="password" id="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Digite a senha" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirmar Senha -->
                    <div class="col-12 col-md-6">
                        <label for="password_confirmation" class="form-label fw-medium text-dark">Confirmar Senha *</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="form-control @error('password_confirmation') is-invalid @enderror"
                            placeholder="Confirme a senha" required>
                        @error('password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Cargo -->
                    <div class="col-12 col-md-6">
                        <label for="role" class="form-label fw-medium text-dark">Cargo *</label>
                        <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
                            <option value="">Selecione o cargo</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrador</option>
                            <option value="tecnico" {{ old('role') == 'tecnico' ? 'selected' : '' }}>Técnico</option>
                            <option value="cliente_gestor" {{ old('role') == 'cliente_gestor' ? 'selected' : '' }}>Cliente Gestor</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="col-12 col-md-6">
                        <label for="is_active" class="form-label fw-medium text-dark">Status</label>
                        <select name="is_active" id="is_active" class="form-select @error('is_active') is-invalid @enderror">
                            <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>Ativo</option>
                            <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>Inativo</option>
                        </select>
                        @error('is_active')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Configurações de Notificação -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0 fw-semibold">Configurações de Notificação</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="form-check">
                            <input type="checkbox" name="notify_ticket_created" id="notify_ticket_created" value="1"
                                class="form-check-input" {{ old('notify_ticket_created', 1) ? 'checked' : '' }}>
                            <label for="notify_ticket_created" class="form-check-label">
                                Notificar sobre novos tickets
                            </label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-check">
                            <input type="checkbox" name="notify_ticket_replied" id="notify_ticket_replied" value="1"
                                class="form-check-input" {{ old('notify_ticket_replied', 1) ? 'checked' : '' }}>
                            <label for="notify_ticket_replied" class="form-check-label">
                                Notificar sobre respostas em tickets
                            </label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-check">
                            <input type="checkbox" name="notify_ticket_status_changed" id="notify_ticket_status_changed" value="1"
                                class="form-check-input" {{ old('notify_ticket_status_changed', 1) ? 'checked' : '' }}>
                            <label for="notify_ticket_status_changed" class="form-check-label">
                                Notificar sobre mudanças de status
                            </label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-check">
                            <input type="checkbox" name="notify_ticket_priority_changed" id="notify_ticket_priority_changed" value="1"
                                class="form-check-input" {{ old('notify_ticket_priority_changed', 1) ? 'checked' : '' }}>
                            <label for="notify_ticket_priority_changed" class="form-check-label">
                                Notificar sobre mudanças de prioridade
                            </label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-check">
                            <input type="checkbox" name="notify_ticket_urgent" id="notify_ticket_urgent" value="1"
                                class="form-check-input" {{ old('notify_ticket_urgent', 1) ? 'checked' : '' }}>
                            <label for="notify_ticket_urgent" class="form-check-label">
                                Notificar sobre tickets urgentes
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ações -->
        <div class="d-flex gap-3 justify-content-end">
            <a href="{{ route('settings.users') }}" class="btn btn-outline-secondary">
                Cancelar
            </a>
            <button type="submit" class="btn btn-primary">
                <svg class="me-2" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Criar Usuário
            </button>
        </div>
    </form>
</div>
@endsection
