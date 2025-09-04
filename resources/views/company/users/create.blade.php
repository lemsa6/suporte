@extends('layouts.app')

@section('title', 'Adicionar Funcionário')

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h1 class="fs-2 fw-semibold text-dark">Adicionar Novo Funcionário</h1>
            <p class="mt-2 fs-6 text-muted">
                Adicione um novo funcionário para a empresa <strong>{{ $client->company_name }}</strong>
            </p>
        </div>

        <div class="bg-white shadow-sm rounded">
            <form id="createUserForm" class="space-y-6 p-6">
                @csrf
                
                <!-- Informações Pessoais -->
                <div>
                    <h3 class="fs-4 fw-medium text-dark mb-4">Informações Pessoais</h3>
                    <div class="grid row-cols-1 gap-4 sm:row-cols-2">
                        <div>
                            <label for="name" class="block fs-6 fw-medium text-dark">Nome Completo *</label>
                            <input type="text" id="name" name="name" required class="mt-1 block w-full rounded border-light shadow-sm-sm focus:border-brand-500 focus:ring-brand-500 sm:fs-6">
                        </div>
                        
                        <div>
                            <label for="email" class="block fs-6 fw-medium text-dark">E-mail *</label>
                            <input type="email" id="email" name="email" required class="mt-1 block w-full rounded border-light shadow-sm-sm focus:border-brand-500 focus:ring-brand-500 sm:fs-6">
                        </div>
                    </div>
                </div>

                <!-- Informações Profissionais -->
                <div>
                    <h3 class="fs-4 fw-medium text-dark mb-4">Informações Profissionais</h3>
                    <div class="grid row-cols-1 gap-4 sm:row-cols-2">
                        <div>
                            <label for="position" class="block fs-6 fw-medium text-dark">Cargo</label>
                            <input type="text" id="position" name="position" class="mt-1 block w-full rounded border-light shadow-sm-sm focus:border-brand-500 focus:ring-brand-500 sm:fs-6" placeholder="Ex: Desenvolvedor, Analista, etc.">
                        </div>
                        
                        <div>
                            <label for="department" class="block fs-6 fw-medium text-dark">Departamento</label>
                            <input type="text" id="department" name="department" class="mt-1 block w-full rounded border-light shadow-sm-sm focus:border-brand-500 focus:ring-brand-500 sm:fs-6" placeholder="Ex: TI, RH, Financeiro, etc.">
                        </div>
                    </div>
                </div>

                <!-- Contato -->
                <div>
                    <h3 class="fs-4 fw-medium text-dark mb-4">Contato</h3>
                    <div>
                        <label for="phone" class="block fs-6 fw-medium text-dark">Telefone</label>
                        <input type="tel" id="phone" name="phone" class="mt-1 block w-full rounded border-light shadow-sm-sm focus:border-brand-500 focus:ring-brand-500 sm:fs-6" placeholder="(11) 99999-9999">
                    </div>
                </div>

                <!-- Senha -->
                <div>
                    <h3 class="fs-4 fw-medium text-dark mb-4">Acesso ao Sistema</h3>
                    <div class="grid row-cols-1 gap-4 sm:row-cols-2">
                        <div>
                            <label for="password" class="block fs-6 fw-medium text-dark">Senha *</label>
                            <input type="password" id="password" name="password" required minlength="8" class="mt-1 block w-full rounded border-light shadow-sm-sm focus:border-brand-500 focus:ring-brand-500 sm:fs-6">
                            <p class="mt-1 fs-6 text-muted">Mínimo de 8 caracteres</p>
                        </div>
                        
                        <div>
                            <label for="password_confirmation" class="block fs-6 fw-medium text-dark">Confirmar Senha *</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required minlength="8" class="mt-1 block w-full rounded border-light shadow-sm-sm focus:border-brand-500 focus:ring-brand-500 sm:fs-6">
                        </div>
                    </div>
                </div>

                <!-- Informações do Sistema -->
                <div class="bg-blue-50 border border-blue-200 rounded p-4">
                    <div class="d-flex">
                        <div class="d-flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="fs-6 fw-medium text-blue-800">Informações do Sistema</h3>
                            <div class="mt-2 fs-6 text-blue-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    <li>O novo funcionário terá acesso ao sistema com role <strong>Funcionário</strong></li>
                                    <li>Poderá criar e gerenciar apenas seus próprios tickets</li>
                                    <li>Receberá notificações por e-mail sobre seus tickets</li>
                                    <li>A senha será enviada por e-mail para o usuário</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botões -->
                <div class="d-flex justify-content-end space-x-3 pt-4 border-t border-light">
                    <a href="{{ route('company.users.index') }}" class="inline-d-flex align-items-center px-4 py-2 border border-light shadow-sm-sm fs-6 fw-medium rounded text-dark bg-white hover:bg-light focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500">
                        Cancelar
                    </a>
                    <button type="submit" class="inline-d-flex align-items-center px-4 py-2 border border-transparent fs-6 fw-medium rounded shadow-sm-sm text-white bg-primary hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Adicionar Funcionário
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('createUserForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Validação da senha
    const password = document.getElementById('password').value;
    const passwordConfirmation = document.getElementById('password_confirmation').value;
    
    if (password !== passwordConfirmation) {
        alert('As senhas não conferem!');
        return;
    }
    
    if (password.length < 8) {
        alert('A senha deve ter pelo menos 8 caracteres!');
        return;
    }
    
    // Criar FormData
    const formData = new FormData(this);
    
    // Enviar requisição
    fetch('{{ route("company.users.store") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            window.location.href = '{{ route("company.users.index") }}';
        } else {
            alert('Erro: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erro ao criar funcionário');
    });
});
</script>
@endsection
