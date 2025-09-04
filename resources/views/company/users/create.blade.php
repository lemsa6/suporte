@extends('layouts.app')

@section('title', 'Adicionar Funcionário')

@section('header')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between">
    <div class="flex-grow-1">
        <h2 class="fs-2 fw-bold text-dark mb-1">
            Adicionar Novo Funcionário
        </h2>
        <p class="text-muted">
            Adicione um novo funcionário para a empresa <strong>{{ $client->company_name }}</strong>
        </p>
    </div>
</div>
@endsection

@section('content')
<div class="d-flex flex-column gap-4">
    <form id="createUserForm" class="d-flex flex-column gap-4">
        @csrf
        
        <!-- Informações Pessoais -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0 fw-semibold">Informações Pessoais</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label for="name" class="form-label fw-medium text-dark">Nome Completo *</label>
                        <input type="text" id="name" name="name" required class="form-control">
                    </div>
                    
                    <div class="col-12 col-md-6">
                        <label for="email" class="form-label fw-medium text-dark">E-mail *</label>
                        <input type="email" id="email" name="email" required class="form-control">
                    </div>
                </div>
            </div>
        </div>

        <!-- Informações Profissionais -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0 fw-semibold">Informações Profissionais</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label for="position" class="form-label fw-medium text-dark">Cargo</label>
                        <input type="text" id="position" name="position" class="form-control" placeholder="Ex: Desenvolvedor, Analista, etc.">
                    </div>
                    
                    <div class="col-12 col-md-6">
                        <label for="department" class="form-label fw-medium text-dark">Departamento</label>
                        <input type="text" id="department" name="department" class="form-control" placeholder="Ex: TI, RH, Financeiro, etc.">
                    </div>
                </div>
            </div>
        </div>

        <!-- Configurações de Acesso -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0 fw-semibold">Configurações de Acesso</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label for="role" class="form-label fw-medium text-dark">Função *</label>
                        <select id="role" name="role" required class="form-select">
                            <option value="">Selecione uma função</option>
                            <option value="cliente_funcionario">Funcionário</option>
                            <option value="cliente_gestor">Gestor</option>
                        </select>
                    </div>
                    
                    <div class="col-12 col-md-6">
                        <label for="phone" class="form-label fw-medium text-dark">Telefone</label>
                        <input type="tel" id="phone" name="phone" class="form-control" placeholder="(11) 99999-9999">
                    </div>
                </div>
            </div>
        </div>

        <!-- Botões de Ação -->
        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('company.users.index', $client) }}" class="btn btn-outline-secondary">
                <svg class="me-1" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Cancelar
            </a>
            
            <button type="submit" class="btn btn-primary">
                <svg class="me-1" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Adicionar Funcionário
            </button>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
document.getElementById('createUserForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    
    // Validação básica
    if (!data.name || !data.email || !data.role) {
        alert('Por favor, preencha todos os campos obrigatórios.');
        return;
    }
    
    // Validação de email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(data.email)) {
        alert('Por favor, insira um email válido.');
        return;
    }
    
    // Aqui você pode adicionar a lógica para enviar os dados
    console.log('Dados do formulário:', data);
    alert('Funcionário adicionado com sucesso!');
});
</script>
@endpush