@extends('layouts.app')

@section('title', 'Ticket #' . $ticket->ticket_number)

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div class="mb-4 sm:mb-0">
        <div class="flex items-center">
            <x-button variant="outline" tag="a" href="{{ route('tickets.index') }}" class="mr-3">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Voltar
            </x-button>
            <div>
                <span class="text-sm text-cinza-claro">Ticket</span>
                <h1 class="page-title mt-1">#{{ $ticket->ticket_number }}</h1>
                <p class="text-cinza mt-2">{{ $ticket->title }}</p>
            </div>
        </div>
    </div>
    <div class="flex gap-2">
        @if(auth()->user()->isAdmin() || auth()->user()->isTecnico())
            <x-button variant="outline" tag="a" href="{{ route('admin.audit.ticket', $ticket->ticket_number) }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Auditoria
            </x-button>
        @endif
        <x-button variant="outline" tag="a" href="{{ route('tickets.edit', $ticket->ticket_number) }}">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Editar
        </x-button>
        @if($ticket->status !== 'fechado')
            <x-button variant="success" onclick="changeStatus('{{ $ticket->ticket_number }}', 'fechado')">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Fechar Ticket
            </x-button>
        @else
            <x-button variant="primary" onclick="reopenTicket('{{ $ticket->ticket_number }}')">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Reabrir Ticket
            </x-button>
        @endif
    </div>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Informações do Ticket -->
    <x-card>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Informações Principais -->
            <div class="space-y-4">
                <h3 class="section-title mb-4">Informações Principais</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between py-2 border-b border-cinza-claro-2">
                        <span class="text-sm font-medium text-cinza">Status:</span>
                        <x-badge variant="
                            @if($ticket->status === 'aberto') warning
                            @elseif($ticket->status === 'em_andamento') info
                            @elseif($ticket->status === 'resolvido') success
                            @else secondary
                            @endif">
                            {{ ucfirst($ticket->status) }}
                        </x-badge>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-cinza-claro-2">
                        <span class="text-sm font-medium text-cinza">Prioridade:</span>
                        <x-badge variant="
                            @if($ticket->priority === 'alta') danger
                            @elseif($ticket->priority === 'média') warning
                            @else success
                            @endif">
                            {{ ucfirst($ticket->priority) }}
                        </x-badge>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-cinza-claro-2">
                        <span class="text-sm font-medium text-cinza">Categoria:</span>
                        <span class="text-cinza font-medium">{{ $ticket->category->name }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-cinza-claro-2">
                        <span class="text-sm font-medium text-cinza">Urgente:</span>
                        <x-badge variant="{{ $ticket->is_urgent ? 'danger' : 'secondary' }}">
                            {{ $ticket->is_urgent ? 'Sim' : 'Não' }}
                        </x-badge>
                    </div>
                </div>
            </div>

            <!-- Informações Adicionais -->
            <div class="space-y-4">
                <h3 class="section-title mb-4">Detalhes do Ticket</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between py-2 border-b border-cinza-claro-2">
                        <span class="text-sm font-medium text-cinza">Responsável:</span>
                        <span class="text-cinza font-medium">
                            @if($ticket->assignedTo)
                                {{ $ticket->assignedTo->name }}
                            @else
                                <span class="text-cinza-claro-2">Não atribuído</span>
                            @endif
                        </span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-cinza-claro-2">
                        <span class="text-sm font-medium text-cinza">Aberto em:</span>
                        <span class="text-cinza font-medium">{{ $ticket->opened_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-cinza-claro-2">
                        <span class="text-sm font-medium text-cinza">Tempo aberto:</span>
                        <span class="text-cinza font-medium">{{ $ticket->days_open }} dias</span>
                    </div>
                    @if($ticket->resolved_at)
                    <div class="flex items-center justify-between py-2 border-b border-cinza-claro-2">
                        <span class="text-sm font-medium text-cinza">Resolvido em:</span>
                        <span class="text-cinza font-medium">{{ $ticket->resolved_at->format('d/m/Y H:i') }}</span>
                    </div>
                    @endif
                    @if($ticket->closed_at)
                    <div class="flex items-center justify-between py-2 border-b border-cinza-claro-2">
                        <span class="text-sm font-medium text-cinza">Fechado em:</span>
                        <span class="text-cinza font-medium">{{ $ticket->closed_at->format('d/m/Y H:i') }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Cliente -->
        <div class="mt-8 pt-6 border-t border-cinza-claro">
            <h3 class="section-title mb-4">Informações do Cliente</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-cinza-claro-2 p-4 rounded-lg">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-cinza">Empresa:</span>
                        <span class="text-cinza font-medium">{{ $ticket->client->company_name }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-cinza">CNPJ:</span>
                        <span class="text-cinza font-medium">{{ $ticket->client->formatted_cnpj }}</span>
                    </div>
                </div>
                <div class="bg-cinza-claro-2 p-4 rounded-lg">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-cinza">Contato:</span>
                        <span class="text-cinza font-medium">{{ $ticket->contact->name }}</span>
                    </div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-cinza">Email:</span>
                        <span class="text-cinza font-medium">{{ $ticket->contact->email }}</span>
                    </div>
                    @if($ticket->contact->phone)
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-cinza">Telefone:</span>
                        <span class="text-cinza font-medium">{{ $ticket->contact->phone }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        @if($ticket->resolution_notes)
        <div class="mt-6 pt-6 border-t border-cinza-claro">
            <h3 class="section-title mb-4">Notas de Resolução</h3>
            <div class="alert-info p-4 rounded-lg">
                <p class="text-cinza">{{ $ticket->resolution_notes }}</p>
            </div>
        </div>
        @endif

        <!-- Descrição -->
        <div class="mt-6 pt-6 border-t border-cinza-claro">
            <h3 class="section-title mb-4">Descrição do Problema</h3>
            <div class="bg-cinza-claro-2 border border-cinza-claro p-4 rounded-lg">
                <p class="text-cinza whitespace-pre-wrap">{{ $ticket->description }}</p>
            </div>
        </div>
    </x-card>

    <!-- Timeline de Mensagens -->
    <x-card>
        <h2 class="section-title mb-6">Histórico de Mensagens</h2>
        @if($ticket->messages->count() > 0)
            <div class="space-y-6">
                @foreach($ticket->messages as $message)
                    <div class="flex gap-4 p-4 bg-cinza-claro-2 rounded-lg border border-cinza-claro">
                        <!-- Avatar -->
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-medium
                                @if($message->user) bg-roxo
                                @else bg-cinza-claro-2
                                @endif">
                                @if($message->user)
                                    {{ substr($message->user->name, 0, 1) }}
                                @else
                                    {{ substr($message->contact->name, 0, 1) }}
                                @endif
                            </div>
                        </div>
                        
                        <!-- Conteúdo da Mensagem -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <span class="font-medium text-cinza">
                                        @if($message->user)
                                            {{ $message->user->name }}
                                            <span class="text-sm text-cinza-claro">({{ ucfirst($message->user->role) }})</span>
                                        @else
                                            {{ $message->contact->name }}
                                            <span class="text-sm text-cinza-claro">(Cliente)</span>
                                        @endif
                                    </span>
                                    <x-badge variant="
                                        @if($message->type === 'note') info
                                        @elseif($message->type === 'status_change') warning
                                        @else secondary
                                        @endif">
                                        {{ ucfirst($message->type) }}
                                    </x-badge>
                                    @if($message->is_internal)
                                        <x-badge variant="danger">Interno</x-badge>
                                    @endif
                                </div>
                                <span class="text-sm text-cinza-claro">{{ $message->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            
                            <div class="prose max-w-none">
                                <p class="text-cinza whitespace-pre-wrap">{{ $message->message }}</p>
                            </div>
                            
                            @if($message->attachments->count() > 0)
                                <div class="mt-4">
                                    <h4 class="text-sm font-medium text-cinza mb-2">Anexos:</h4>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($message->attachments as $attachment)
                                            @php
                                                $canPreview = in_array($attachment->file_type, [
                                                    'application/pdf',
                                                    'image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp',
                                                    'text/plain', 'text/html', 'application/json', 'text/csv', 'text/xml'
                                                ]);
                                            @endphp
                                            
                                            <div class="flex gap-1">
                                                @if($canPreview)
                                                    <x-button variant="outline" size="sm" type="button" 
                                                            onclick="previewAttachment({{ $attachment->id }}, '{{ $attachment->filename }}', '{{ $attachment->file_type }}')">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                        </svg>
                                                        Preview
                                                    </x-button>
                                                @endif
                                                
                                                <x-button variant="outline" size="sm" tag="a" href="{{ route('attachments.download', $attachment) }}">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                                    </svg>
                                                    Download
                                                </x-button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            
                            @if($message->metadata)
                                <div class="mt-3">
                                    @if(isset($message->metadata['status_change']))
                                        <x-badge variant="warning">
                                            Status alterado de "{{ $message->metadata['status_change']['from'] }}" para "{{ $message->metadata['status_change']['to'] }}"
                                        </x-badge>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto mb-4 text-cinza-claro" width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                <h3 class="text-lg font-medium text-cinza mb-2">Nenhuma mensagem ainda</h3>
                <p class="text-cinza-claro">Seja o primeiro a comentar neste ticket.</p>
            </div>
        @endif
    </x-card>

    <!-- Nova Mensagem -->
    @if($ticket->status !== 'fechado')
    <x-card>
        <h2 class="section-title mb-6">Adicionar Mensagem</h2>
        <form action="{{ route('tickets.message', $ticket->ticket_number) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div>
                <label for="message" class="block text-sm font-medium text-cinza mb-2">Mensagem</label>
                <x-textarea id="message" name="message" rows="4" required placeholder="Digite sua mensagem..."></x-textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="type" class="block text-sm font-medium text-cinza mb-2">Tipo</label>
                    <x-select id="type" name="type">
                        <option value="reply">Resposta</option>
                        @if(auth()->user()->canManageTickets())
                        <option value="note">Nota</option>
                        <option value="status_change">Mudança de Status</option>
                        @endif
                    </x-select>
                </div>

                @if(auth()->user()->canManageTickets())
                <div class="flex items-center">
                    <div class="flex items-center h-5">
                        <input type="checkbox" id="is_internal" name="is_internal" value="1" class="h-4 w-4 text-roxo focus:ring-roxo border-cinza-claro rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="is_internal" class="font-medium text-cinza">
                            Mensagem interna (apenas para equipe)
                        </label>
                    </div>
                </div>
                @endif
            </div>

            <div>
                <label for="attachments" class="block text-sm font-medium text-cinza mb-2">Anexos</label>
                <input type="file" id="attachments" name="attachments[]" multiple 
                       class="block w-full text-sm text-cinza-claro file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-roxo file:bg-opacity-10 file:text-roxo hover:file:bg-roxo hover:file:bg-opacity-20">
                <p class="mt-1 text-sm text-cinza-claro">Formatos aceitos: PDF, JPG, PNG, ZIP, LOG. Máximo 25MB por arquivo.</p>
            </div>

            <div class="flex justify-end">
                <x-button variant="primary" type="submit">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                    Enviar Mensagem
                </x-button>
            </div>
        </form>
    </x-card>
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
                <button type="button" class="text-cinza-claro hover:text-cinza" onclick="closePreviewModal()" aria-label="Close">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="modal-body p-0">
                <div id="previewContent" class="flex justify-center items-center preview-modal">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Carregando...</span>
                        </div>
                        <p class="mt-2 text-cinza-claro">Carregando preview...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <x-button variant="outline" onclick="closeStatusModal()">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Fechar
                </x-button>
                <x-button variant="primary" tag="a" href="#" id="downloadBtn">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                    </svg>
                    Download
                </x-button>
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
                <button type="button" class="absolute top-4 right-4 text-cinza-claro hover:text-cinza" onclick="closeStatusModal()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="new-status" class="block text-sm font-medium text-cinza mb-2">Novo Status</label>
                    <x-select id="new-status">
                        <option value="aberto">Aberto</option>
                        <option value="em_andamento">Em Andamento</option>
                        <option value="resolvido">Resolvido</option>
                        <option value="fechado">Fechado</option>
                    </x-select>
                </div>

                <div class="mb-3">
                    <label for="status-notes" class="block text-sm font-medium text-cinza mb-2">Observações (opcional)</label>
                    <x-textarea id="status-notes" rows="3" placeholder="Adicione observações sobre a mudança de status..."></x-textarea>
                </div>
            </div>
            <div class="modal-footer">
                <x-button variant="outline" onclick="closeStatusModal()">Cancelar</x-button>
                <x-button variant="primary" type="button" onclick="executeStatusChange()">Alterar Status</x-button>
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
                <p class="mt-2 text-cinza-claro">Carregando preview...</p>
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
                    <div class="text-center text-cinza-claro">
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
                    <div class="text-center text-cinza-claro">
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
                        <div class="text-center text-cinza-claro">
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
                        <div class="text-center text-cinza-claro">
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
