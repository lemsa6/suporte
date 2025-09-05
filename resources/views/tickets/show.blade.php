@extends('layouts.app')

@section('title', 'Ticket #' . $ticket->ticket_number)

@section('header')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between">
    <div class="flex-grow-1">
        <div class="d-flex align-items-center">
            <a href="{{ route('tickets.index') }}" class="btn btn-outline-secondary me-3">
                <svg class="me-2" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Voltar
            </a>
            <div>
                <h2 class="fs-2 fw-bold text-dark mb-1">
                    Ticket #{{ $ticket->ticket_number }}
                </h2>
                <p class="text-muted mb-0">
                    {{ $ticket->title }}
                </p>
            </div>
        </div>
    </div>
    <div class="mt-3 mt-md-0 d-flex gap-2">
        <a href="{{ route('tickets.edit', $ticket->ticket_number) }}" class="btn btn-outline-primary">
            <svg class="me-2" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Editar
        </a>
        @if($ticket->status !== 'fechado')
            <button type="button" onclick="changeStatus('{{ $ticket->ticket_number }}', 'fechado')" class="btn btn-success">
                <svg class="me-2" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Fechar Ticket
            </button>
        @else
            <button type="button" onclick="reopenTicket('{{ $ticket->ticket_number }}')" class="btn btn-primary">
                <svg class="me-2" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Reabrir Ticket
            </button>
        @endif
    </div>
</div>
@endsection

@section('content')
<div class="d-flex flex-column gap-4">
    <!-- Informações do Ticket -->
    <div class="card border-0">
        <div class="card-header">
            <h5 class="mb-0 fw-semibold">Informações do Ticket</h5>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <!-- Informações Principais -->
                <div class="col-12 col-lg-6">
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="fw-medium text-muted">Status:</span>
                            <span class="badge 
                                @if($ticket->status === 'aberto') bg-warning text-dark
                                @elseif($ticket->status === 'em_andamento') bg-info text-white
                                @elseif($ticket->status === 'resolvido') bg-success text-white
                                @else bg-secondary text-white
                                @endif">
                                {{ ucfirst($ticket->status) }}
                            </span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="fw-medium text-muted">Prioridade:</span>
                            <span class="badge 
                                @if($ticket->priority === 'alta') bg-danger text-white
                                @elseif($ticket->priority === 'média') bg-warning text-dark
                                @else bg-success text-white
                                @endif">
                                {{ ucfirst($ticket->priority) }}
                            </span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="fw-medium text-muted">Categoria:</span>
                            <span class="text-dark">{{ $ticket->category->name }}</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="fw-medium text-muted">Urgente:</span>
                            <span class="text-dark">{{ $ticket->is_urgent ? 'Sim' : 'Não' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Informações Adicionais -->
                <div class="col-12 col-lg-6">
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="fw-medium text-muted">Responsável:</span>
                            <span class="text-dark">
                                @if($ticket->assignedTo)
                                    {{ $ticket->assignedTo->name }}
                                @else
                                    <span class="text-muted">Não atribuído</span>
                                @endif
                            </span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="fw-medium text-muted">Aberto em:</span>
                            <span class="text-dark">{{ $ticket->opened_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="fw-medium text-muted">Tempo aberto:</span>
                            <span class="text-dark">{{ $ticket->days_open }} dias</span>
                        </div>
                        @if($ticket->resolved_at)
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="fw-medium text-muted">Resolvido em:</span>
                            <span class="text-dark">{{ $ticket->resolved_at->format('d/m/Y H:i') }}</span>
                        </div>
                        @endif
                        @if($ticket->closed_at)
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="fw-medium text-muted">Fechado em:</span>
                            <span class="text-dark">{{ $ticket->closed_at->format('d/m/Y H:i') }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Cliente -->
            <div class="mt-4">
                <h6 class="fw-semibold text-dark mb-3">Cliente</h6>
                <div class="row g-3">
                    <div class="col-12 col-sm-6">
                        <div class="d-flex justify-content-between">
                            <span class="fw-medium text-muted">Empresa:</span>
                            <span class="text-dark">{{ $ticket->client->company_name }}</span>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="d-flex justify-content-between">
                            <span class="fw-medium text-muted">CNPJ:</span>
                            <span class="text-dark">{{ $ticket->client->formatted_cnpj }}</span>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="d-flex justify-content-between">
                            <span class="fw-medium text-muted">Contato:</span>
                            <span class="text-dark">{{ $ticket->contact->name }}</span>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="d-flex justify-content-between">
                            <span class="fw-medium text-muted">Email:</span>
                            <span class="text-dark">{{ $ticket->contact->email }}</span>
                        </div>
                    </div>
                    @if($ticket->contact->phone)
                    <div class="col-12 col-sm-6">
                        <div class="d-flex justify-content-between">
                            <span class="fw-medium text-muted">Telefone:</span>
                            <span class="text-dark">{{ $ticket->contact->phone }}</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            @if($ticket->resolution_notes)
            <div class="mt-4">
                <h6 class="fw-semibold text-dark mb-3">Notas de Resolução</h6>
                <div class="p-3 bg-light rounded">
                    <p class="text-dark mb-0">{{ $ticket->resolution_notes }}</p>
                </div>
            </div>
            @endif

            <!-- Descrição -->
            <div class="mt-4">
                <h6 class="fw-semibold text-dark mb-3">Descrição</h6>
                <div class="p-3 bg-light rounded">
                    <p class="text-dark mb-0">{{ $ticket->description }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Timeline de Mensagens -->
    <div class="card border-0">
        <div class="card-header">
            <h5 class="mb-0 fw-semibold">Histórico de Mensagens</h5>
        </div>
        <div class="card-body">
            @if($ticket->messages->count() > 0)
                <div class="timeline">
                    @foreach($ticket->messages as $message)
                        <div class="timeline-item">
                            <div class="timeline-marker">
                                <div class="timeline-avatar 
                                    @if($message->user) bg-primary
                                    @else bg-secondary
                                    @endif">
                                    @if($message->user)
                                        <span class="text-white">{{ substr($message->user->name, 0, 1) }}</span>
                                    @else
                                        <span class="text-white">{{ substr($message->contact->name, 0, 1) }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="fw-medium text-dark">
                                            @if($message->user)
                                                {{ $message->user->name }}
                                                <small class="text-muted">({{ ucfirst($message->user->role) }})</small>
                                            @else
                                                {{ $message->contact->name }}
                                                <small class="text-muted">(Cliente)</small>
                                            @endif
                                        </span>
                                        <span class="badge 
                                            @if($message->type === 'note') bg-info text-white
                                            @elseif($message->type === 'status_change') bg-warning text-dark
                                            @else bg-secondary text-white
                                            @endif">
                                            {{ ucfirst($message->type) }}
                                        </span>
                                        @if($message->is_internal)
                                            <span class="badge bg-danger text-white">Interno</span>
                                        @endif
                                    </div>
                                    <small class="text-muted">{{ $message->created_at->format('d/m/Y H:i') }}</small>
                                </div>
                                <div class="message-content">
                                    <p class="text-dark mb-2">{{ $message->message }}</p>
                                    @if($message->attachments->count() > 0)
                                        <div class="attachments">
                                            <small class="text-muted d-block mb-2">Anexos:</small>
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach($message->attachments as $attachment)
                                                    <div class="btn-group" role="group">
                                                        @php
                                                            $canPreview = in_array($attachment->file_type, [
                                                                'application/pdf',
                                                                'image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp',
                                                                'text/plain', 'text/html', 'application/json', 'text/csv', 'text/xml'
                                                            ]);
                                                        @endphp
                                                        
                                                        @if($canPreview)
                                                            <button type="button" class="btn btn-sm btn-outline-primary" 
                                                                    onclick="previewAttachment({{ $attachment->id }}, '{{ $attachment->filename }}', '{{ $attachment->file_type }}')">
                                                                <svg class="me-1" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                                </svg>
                                                                Preview
                                                            </button>
                                                        @endif
                                                        
                                                        <a href="{{ route('attachments.download', $attachment) }}" class="btn btn-sm btn-outline-secondary">
                                                            <svg class="me-1" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                                            </svg>
                                                            Download
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                    @if($message->metadata)
                                        <div class="metadata mt-2">
                                            @if(isset($message->metadata['status_change']))
                                                <span class="badge bg-warning text-dark">
                                                    Status alterado de "{{ $message->metadata['status_change']['from'] }}" para "{{ $message->metadata['status_change']['to'] }}"
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <svg class="mx-auto mb-3 text-muted" width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <h6 class="fw-medium text-dark">Nenhuma mensagem ainda</h6>
                    <p class="text-muted mb-0">Seja o primeiro a comentar neste ticket.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Nova Mensagem -->
    @if($ticket->status !== 'fechado')
    <div class="card border-0">
        <div class="card-header">
            <h5 class="mb-0 fw-semibold">Adicionar Mensagem</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('tickets.message', $ticket->ticket_number) }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-3">
                    <label for="message" class="form-label fw-medium text-dark">Mensagem</label>
                    <textarea id="message" name="message" rows="4" required
                        class="form-control"
                        placeholder="Digite sua mensagem..."></textarea>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-12 col-sm-6">
                        <label for="type" class="form-label fw-medium text-dark">Tipo</label>
                        <select id="type" name="type" class="form-select">
                            <option value="reply">Resposta</option>
                            @if(auth()->user()->canManageTickets())
                            <option value="note">Nota</option>
                            <option value="status_change">Mudança de Status</option>
                            @endif
                        </select>
                    </div>

                    @if(auth()->user()->canManageTickets())
                    <div class="col-12 col-sm-6">
                        <div class="form-check mt-4">
                            <input type="checkbox" id="is_internal" name="is_internal" value="1" class="form-check-input">
                            <label for="is_internal" class="form-check-label text-dark">
                                Mensagem interna (apenas para equipe)
                            </label>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="attachments" class="form-label fw-medium text-dark">Anexos</label>
                    <input type="file" id="attachments" name="attachments[]" multiple class="form-control">
                    <div class="form-text">Formatos aceitos: PDF, JPG, PNG, ZIP, LOG. Máximo 25MB por arquivo.</div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        <svg class="me-2" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        Enviar Mensagem
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>

<!-- Modal de Preview de Anexos -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-semibold" id="previewModalLabel">
                    <svg class="me-2" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    Preview do Arquivo
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div id="previewContent" class="d-flex justify-content-center align-items-center" style="min-height: 500px;">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Carregando...</span>
                        </div>
                        <p class="mt-2 text-muted">Carregando preview...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <svg class="me-2" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Fechar
                </button>
                <a id="downloadBtn" href="#" class="btn btn-primary">
                    <svg class="me-2" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                    </svg>
                    Download
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal para alterar status -->
<div id="status-modal" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-semibold">Alterar Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="new-status" class="form-label fw-medium text-dark">Novo Status</label>
                    <select id="new-status" class="form-select">
                        <option value="aberto">Aberto</option>
                        <option value="em_andamento">Em Andamento</option>
                        <option value="resolvido">Resolvido</option>
                        <option value="fechado">Fechado</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="status-notes" class="form-label fw-medium text-dark">Observações (opcional)</label>
                    <textarea id="status-notes" rows="3" class="form-control" placeholder="Adicione observações sobre a mudança de status..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" onclick="executeStatusChange()" class="btn btn-primary">Alterar Status</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function changeStatus(ticketHash, status) {
        if (status === 'fechado') {
            if (confirm('Tem certeza que deseja fechar este ticket?')) {
                // Implementar fechamento via AJAX
                console.log('Fechando ticket', ticketHash);
            }
        } else {
            showStatusModal(ticketHash);
        }
    }

    function showStatusModal(ticketHash) {
        const modal = new bootstrap.Modal(document.getElementById('status-modal'));
        document.getElementById('status-modal').dataset.ticketHash = ticketHash;
        modal.show();
    }

    function executeStatusChange() {
        const modal = document.getElementById('status-modal');
        const ticketHash = modal.dataset.ticketHash;
        const newStatus = document.getElementById('new-status').value;
        const notes = document.getElementById('status-notes').value;
        
        // Implementar alteração de status via AJAX
        console.log('Alterando status do ticket', ticketHash, 'para', newStatus, 'com notas:', notes);
        
        const modalInstance = bootstrap.Modal.getInstance(modal);
        modalInstance.hide();
    }

    function reopenTicket(ticketHash) {
        if (confirm('Tem certeza que deseja reabrir este ticket?')) {
            // Implementar reabertura via AJAX
            console.log('Reabrindo ticket', ticketHash);
        }
    }

    function previewAttachment(attachmentId, filename, fileType) {
        // Mostrar modal
        const modal = new bootstrap.Modal(document.getElementById('previewModal'));
        modal.show();
        
        // Atualizar título
        document.getElementById('previewModalLabel').innerHTML = `
            <svg class="me-2" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
            </svg>
            Preview: ${filename}
        `;
        
        // Atualizar link de download
        document.getElementById('downloadBtn').href = `/attachments/${attachmentId}/download`;
        
        // Carregar preview
        const previewContent = document.getElementById('previewContent');
        previewContent.innerHTML = `
            <div class="text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Carregando...</span>
                </div>
                <p class="mt-2 text-muted">Carregando preview...</p>
            </div>
        `;
        
        // Determinar como exibir o arquivo
        const previewUrl = `/attachments/${attachmentId}/preview`;
        
        if (fileType.startsWith('image/')) {
            // Para imagens
            const img = document.createElement('img');
            img.src = previewUrl;
            img.className = 'img-fluid';
            img.style.maxHeight = '70vh';
            img.onload = function() {
                previewContent.innerHTML = '';
                previewContent.appendChild(img);
            };
            img.onerror = function() {
                previewContent.innerHTML = `
                    <div class="text-center text-muted">
                        <svg class="mb-3" width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        <p>Erro ao carregar imagem</p>
                    </div>
                `;
            };
        } else if (fileType === 'application/pdf') {
            // Para PDFs
            const iframe = document.createElement('iframe');
            iframe.src = previewUrl;
            iframe.style.width = '100%';
            iframe.style.height = '70vh';
            iframe.style.border = 'none';
            iframe.onload = function() {
                previewContent.innerHTML = '';
                previewContent.appendChild(iframe);
            };
            iframe.onerror = function() {
                previewContent.innerHTML = `
                    <div class="text-center text-muted">
                        <svg class="mb-3" width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        <p>Erro ao carregar PDF</p>
                    </div>
                `;
            };
        } else if (fileType.startsWith('text/') || fileType === 'application/json') {
            // Para arquivos de texto
            fetch(previewUrl)
                .then(response => response.text())
                .then(text => {
                    const pre = document.createElement('pre');
                    pre.className = 'bg-light p-3 rounded';
                    pre.style.maxHeight = '70vh';
                    pre.style.overflow = 'auto';
                    pre.textContent = text;
                    previewContent.innerHTML = '';
                    previewContent.appendChild(pre);
                })
                .catch(error => {
                    previewContent.innerHTML = `
                        <div class="text-center text-muted">
                            <svg class="mb-3" width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            <p>Erro ao carregar arquivo</p>
                        </div>
                    `;
                });
        } else {
            // Para outros tipos
            previewContent.innerHTML = `
                <div class="text-center text-muted">
                    <svg class="mb-3" width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p>Preview não disponível para este tipo de arquivo</p>
                    <p class="small">Use o botão Download para baixar o arquivo</p>
                </div>
            `;
        }
    }
</script>
@endpush
