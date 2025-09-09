@extends('layouts.app')

@section('title', 'Templates de Notificação')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div class="mb-4 sm:mb-0">
        <span class="text-sm text-gray-500">Administração</span>
        <h1 class="page-title mt-1">Templates de Notificação</h1>
        <p class="text-gray-600 mt-2">Edite e personalize os templates de email para notificações do sistema</p>
    </div>
    <x-button variant="outline" tag="a" href="{{ route('admin.settings.index') }}">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Voltar
    </x-button>
</div>
@endsection

@section('content')
<div class="d-flex flex-column gap-4">
    <!-- Navegação por Abas -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0">
            <ul class="nav nav-tabs nav-tabs-custom border-0" id="templateTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="ticket-created-tab" data-bs-toggle="tab" data-bs-target="#ticket-created" type="button" role="tab">
                        <svg class="me-2" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Ticket Criado
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="ticket-created-for-you-tab" data-bs-toggle="tab" data-bs-target="#ticket-created-for-you" type="button" role="tab">
                        <svg class="me-2" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Ticket para Cliente
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="ticket-replied-tab" data-bs-toggle="tab" data-bs-target="#ticket-replied" type="button" role="tab">
                        <svg class="me-2" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        Resposta
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="ticket-status-changed-tab" data-bs-toggle="tab" data-bs-target="#ticket-status-changed" type="button" role="tab">
                        <svg class="me-2" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Status Alterado
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="ticket-closed-tab" data-bs-toggle="tab" data-bs-target="#ticket-closed" type="button" role="tab">
                        <svg class="me-2" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Ticket Fechado
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="email-layout-tab" data-bs-toggle="tab" data-bs-target="#email-layout" type="button" role="tab">
                        <svg class="me-2" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
                        </svg>
                        Layout Base
                    </button>
                </li>
            </ul>
        </div>
    </div>

    <!-- Conteúdo das Abas -->
    <div class="tab-content" id="templateTabsContent">
        @foreach(['ticket_created', 'ticket_created_for_you', 'ticket_replied', 'ticket_status_changed', 'ticket_closed', 'email_layout'] as $template)
        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" 
             id="{{ str_replace('_', '-', $template) }}" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0 fw-semibold">
                            @switch($template)
                                @case('ticket_created')
                                    Template: Ticket Criado
                                    @break
                                @case('ticket_created_for_you')
                                    Template: Ticket Criado para Cliente
                                    @break
                                @case('ticket_replied')
                                    Template: Resposta ao Ticket
                                    @break
                                @case('ticket_status_changed')
                                    Template: Status Alterado
                                    @break
                                @case('ticket_closed')
                                    Template: Ticket Fechado
                                    @break
                                @case('email_layout')
                                    Template: Layout Base
                                    @break
                            @endswitch
                        </h5>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-info btn-sm" onclick="previewTemplate('{{ $template }}')">
                                <svg class="me-1" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Preview
                            </button>
                            @if($template !== 'email_layout')
                            <button type="button" class="btn btn-outline-success btn-sm" onclick="testEmail('{{ $template }}')">
                                <svg class="me-1" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Testar
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.settings.templates.update') }}" class="d-flex flex-column gap-3">
                        @csrf
                        <input type="hidden" name="template_name" value="{{ $template }}">
                        
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="template_content_{{ $template }}" class="form-label fw-medium text-dark">
                                    Código do Template
                                </label>
                                <textarea name="template_content" id="template_content_{{ $template }}" 
                                    class="form-control font-monospace" rows="20" 
                                    class="text-xs leading-tight">{{ $templates[$template] }}</textarea>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-outline-secondary" onclick="resetTemplate('{{ $template }}')">
                                <svg class="me-2" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Resetar
                            </button>
                            <button type="submit" class="btn btn-primary d-inline-flex align-items-center">
                                <svg class="me-2" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Salvar Template
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Modal de Preview -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalLabel">Preview do Template</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <iframe id="previewFrame" src="" width="100%" height="600" frameborder="0"></iframe>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Teste de Email -->
<div class="modal fade" id="testEmailModal" tabindex="-1" aria-labelledby="testEmailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="testEmailModalLabel">Testar Envio de Email</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('admin.settings.templates.test') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="test_email" class="form-label">Email de Teste</label>
                        <input type="email" class="form-control" id="test_email" name="test_email" 
                            value="{{ auth()->user()->email }}" required>
                    </div>
                    <input type="hidden" name="template_type" id="test_template_type">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Enviar Teste</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.nav-tabs-custom .nav-link {
    border: none;
    border-bottom: 2px solid transparent;
    color: #6c757d;
    font-weight: 500;
    padding: 12px 20px;
    transition: all 0.3s ease;
}

.nav-tabs-custom .nav-link:hover {
    border-color: #dee2e6;
    color: #495057;
}

.nav-tabs-custom .nav-link.active {
    border-color: #667eea;
    color: #667eea;
    background: none;
}

.font-monospace {
    font-family: 'Courier New', Courier, monospace;
}
</style>
@endpush

@push('scripts')
<script>
function previewTemplate(templateName) {
    const content = document.getElementById(`template_content_${templateName}`).value;
    
    // Criar um formulário temporário para enviar os dados
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("admin.settings.templates.preview") }}';
    form.target = 'previewFrame';
    
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    
    const templateNameInput = document.createElement('input');
    templateNameInput.type = 'hidden';
    templateNameInput.name = 'template_name';
    templateNameInput.value = templateName;
    
    const contentInput = document.createElement('input');
    contentInput.type = 'hidden';
    contentInput.name = 'template_content';
    contentInput.value = content;
    
    form.appendChild(csrfToken);
    form.appendChild(templateNameInput);
    form.appendChild(contentInput);
    
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
    
    // Mostrar o modal
    const modal = new bootstrap.Modal(document.getElementById('previewModal'));
    modal.show();
}

function testEmail(templateType) {
    document.getElementById('test_template_type').value = templateType;
    const modal = new bootstrap.Modal(document.getElementById('testEmailModal'));
    modal.show();
}

function resetTemplate(templateName) {
    if (confirm('Tem certeza que deseja resetar este template para o padrão?')) {
        // Aqui você pode implementar a lógica para resetar o template
        location.reload();
    }
}
</script>
@endpush
