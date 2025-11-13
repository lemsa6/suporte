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
        @if(auth()->user()->isAdmin() || auth()->user()->isTecnico())
            <x-button variant="outline" type="button" onclick="openStatusModal()">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                </svg>
                Alterar Status
            </x-button>
        @endif
        @if($ticket->status !== 'fechado')
            <!-- TESTE: Se você vê este comentário, o arquivo está sendo editado corretamente -->
            <x-button variant="success" type="button" onclick="openCloseTicketModal()">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Fechar Ticket
            </x-button>
        @else
            <x-button variant="primary" type="button" onclick="openReopenTicketModal()">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Reabrir Ticket
            </x-button>
        @endif
    </div>
</div>

<!-- Modal para Fechar Ticket -->
<div id="close-ticket-modal" class="fixed inset-0 bg-black bg-opacity-10 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-lg max-w-sm mx-4 transform transition-all duration-300 scale-95 opacity-0" id="close-ticket-modal-content">
            <div class="flex items-center justify-between p-6 border-b border-cinza-claro">
                <h3 class="text-lg font-semibold text-cinza">Fechar Ticket</h3>
                <button type="button" onclick="closeCloseTicketModal()" class="text-cinza-claro hover:text-cinza">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0 w-10 h-10 mx-auto bg-amarelo-claro rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-amarelo" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                </div>
                <div class="text-center">
                    <h4 class="text-lg font-medium text-cinza mb-2">Tem certeza?</h4>
                    <p class="text-sm text-cinza-claro mb-4">
                        Esta ação irá alterar o status do ticket <strong>{{ $ticket->ticket_number }}</strong> para <strong>"Fechado"</strong>.
                    </p>
                    <p class="text-xs text-cinza-claro">
                        O ticket poderá ser reaberto posteriormente se necessário.
                    </p>
                </div>
            </div>
            
            <div class="flex items-center justify-end gap-3 p-6 border-t border-cinza-claro">
                <x-button variant="outline" onclick="closeCloseTicketModal()">
                    Cancelar
                </x-button>
                <form action="{{ route('tickets.change-status', $ticket->ticket_number) }}" method="POST" style="display: inline;">
                    @csrf
                    <input type="hidden" name="status" value="fechado">
                    <x-button variant="success" type="submit">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Sim, Fechar Ticket
                    </x-button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('content')
<div class="space-y-6">
    <!-- Informações do Ticket -->
    <h2 class="section-title mb-4">Informações do Ticket</h2>
    <x-card>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Informações Principais -->
            <div class="space-y-4">
                <h3 class="subsection-title mb-4">Informações Principais</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between py-2 border-b border-padrao">
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
                    <div class="flex items-center justify-between py-2 border-b border-padrao">
                        <span class="text-sm font-medium text-cinza">Prioridade:</span>
                        <x-badge variant="
                            @if($ticket->priority === 'alta') danger
                            @elseif($ticket->priority === 'média') warning
                            @else success
                            @endif">
                            {{ ucfirst($ticket->priority) }}
                        </x-badge>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-padrao">
                        <span class="text-sm font-medium text-bege">Categoria:</span>
                        <span class="text-bege font-medium">{{ $ticket->category->name }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-padrao">
                        <span class="text-sm font-medium text-bege">Urgente:</span>
                        <x-badge variant="{{ $ticket->is_urgent ? 'danger' : 'secondary' }}">
                            {{ $ticket->is_urgent ? 'Sim' : 'Não' }}
                        </x-badge>
                    </div>
                </div>
            </div>

            <!-- Informações Adicionais -->
            <div class="space-y-4">
                <h3 class="subsection-title mb-4">Detalhes do Ticket</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between py-2 border-b border-padrao">
                        <span class="text-sm font-medium text-bege">Responsável:</span>
                        <span class="text-bege font-medium">
                            @if($ticket->assignedTo)
                                {{ $ticket->assignedTo->name }}
                            @else
                                <span class="text-cinza-claro-2">Não atribuído</span>
                            @endif
                        </span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-padrao">
                        <span class="text-sm font-medium text-bege">Aberto em:</span>
                        <span class="text-bege font-medium">{{ $ticket->opened_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-padrao">
                        <span class="text-sm font-medium text-bege">Tempo aberto:</span>
                        <span class="text-bege font-medium">{{ $ticket->days_open }} dias</span>
                    </div>
                    @if($ticket->resolved_at)
                    <div class="flex items-center justify-between py-2 border-b border-padrao">
                        <span class="text-sm font-medium text-cinza">Resolvido em:</span>
                        <span class="text-cinza font-medium">{{ $ticket->resolved_at->format('d/m/Y H:i') }}</span>
                    </div>
                    @endif
                    @if($ticket->closed_at)
                    <div class="flex items-center justify-between py-2 border-b border-padrao">
                        <span class="text-sm font-medium text-cinza">Fechado em:</span>
                        <span class="text-cinza font-medium">{{ $ticket->closed_at->format('d/m/Y H:i') }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </x-card>
    
    <!-- Cliente -->
    <h2 class="section-title mb-4">Informações do Cliente</h2>
    <x-card>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-creme p-4 rounded-lg">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-cinza">Empresa:</span>
                        <span class="text-cinza font-medium">{{ $ticket->client->company_name }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-cinza">CNPJ:</span>
                        <span class="text-cinza font-medium">{{ $ticket->client->formatted_cnpj }}</span>
                    </div>
                </div>
                <div class="bg-creme p-4 rounded-lg">
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

    </x-card>
    
    @if($ticket->resolution_notes)
    <h2 class="section-title mb-4">Notas de Resolução</h2>
    <x-card>
        <div class="alert-info p-4 rounded-lg">
            <p class="text-cinza">{{ $ticket->resolution_notes }}</p>
        </div>
    </x-card>
    @endif
    
    <!-- Descrição -->
    <h2 class="section-title mb-4">Descrição do Problema</h2>
    <x-card>
        <div class="bg-creme border border-padrao p-4 rounded-lg">
            <p class="text-cinza whitespace-pre-wrap">{{ $ticket->description }}</p>
        </div>
    </x-card>

    <!-- Timeline de Mensagens -->
    <h2 class="section-title mb-4">Histórico de Mensagens</h2>
    <x-card>
        @if($ticket->messages->count() > 0)
            <!-- Timeline Container -->
            <div class="relative">
                <!-- Linha da Timeline -->
                <div class="absolute left-6 top-0 bottom-0 w-0.5 bg-cinza-claro-2"></div>
                
                <!-- Mensagens da Timeline -->
                <div class="space-y-8">
                    @foreach($ticket->messages as $index => $message)
                        <div class="relative flex items-start">
                            <!-- Avatar da Timeline -->
                            <div class="relative z-10 flex-shrink-0">
                                <div class="w-12 h-12 rounded-full flex items-center justify-center text-white font-medium border-4 border-white
                                    @if($message->user) bg-roxo
                                    @else bg-amarelo text-cinza
                                    @endif">
                                    @if($message->user)
                                        {{ substr($message->user->name, 0, 1) }}
                                    @else
                                        {{ substr($message->contact->name, 0, 1) }}
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Conteúdo da Mensagem -->
                            <div class="ml-6 flex-1 min-w-0">
                                <div class="bg-branco rounded-lg border border-padrao p-4 shadow-sm relative">
                                    <!-- Cabeçalho -->
                                    <div class="flex items-center justify-between mb-3">
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
                                                @elseif($message->type === 'assignment') success
                                                @elseif($message->type === 'priority_change') danger
                                                @else secondary
                                                @endif">
                                                @if($message->type === 'assignment') Atribuição
                                                @elseif($message->type === 'priority_change') Prioridade
                                                @elseif($message->type === 'status_change') Status
                                                @elseif($message->type === 'note') Nota
                                                @else {{ ucfirst($message->type) }}
                                                @endif
                                            </x-badge>
                                            @if($message->is_internal)
                                                <x-badge variant="danger">Interno</x-badge>
                                            @endif
                                        </div>
                                        <span class="text-sm text-cinza-claro">{{ $message->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                    
                                    <!-- Mensagem -->
                                    <div class="text-cinza whitespace-pre-wrap">{{ $message->message }}</div>
                            
                                    
                                    <!-- Anexos -->
                                    @if($message->attachments->count() > 0)
                                        <div class="mt-4 pt-3 border-t border-padrao">
                                            <p class="text-sm font-medium text-cinza mb-2">Anexos:</p>
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
                                        <div class="mt-3 space-y-2">
                                            @if(isset($message->metadata['status_change']))
                                                <div>
                                                    <x-badge variant="warning">
                                                        Status alterado de "{{ $message->metadata['status_change']['from'] }}" para "{{ $message->metadata['status_change']['to'] }}"
                                                    </x-badge>
                                                </div>
                                            @endif
                                            
                                            @if(isset($message->metadata['assignment_change']))
                                                <div>
                                                    <x-badge variant="success">
                                                        @if($message->metadata['assignment_change']['from'])
                                                            Reatribuído de "{{ $message->metadata['assignment_change']['from'] }}" para "{{ $message->metadata['assignment_change']['to'] }}"
                                                        @else
                                                            Atribuído a "{{ $message->metadata['assignment_change']['to'] }}"
                                                        @endif
                                                    </x-badge>
                                                </div>
                                            @endif
                                            
                                            @if(isset($message->metadata['priority_change']))
                                                <div>
                                                    <x-badge variant="danger">
                                                        Prioridade alterada de "{{ $message->metadata['priority_change']['from'] }}" para "{{ $message->metadata['priority_change']['to'] }}"
                                                        @if($message->metadata['priority_change']['urgent_to'] && !$message->metadata['priority_change']['urgent_from'])
                                                            - Marcado como urgente
                                                        @elseif(!$message->metadata['priority_change']['urgent_to'] && $message->metadata['priority_change']['urgent_from'])
                                                            - Removido da urgência
                                                        @endif
                                                    </x-badge>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Seta apontando para o avatar -->
                                <div class="absolute left-0 top-4 w-0 h-0 border-t-8 border-b-8 border-r-8 border-transparent border-r-padrao transform -translate-x-1"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
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

    <!-- Ações do Ticket -->
    @if($ticket->status !== 'fechado' && auth()->user()->canManageTickets())
    <h2 class="section-title mb-4">Ações do Ticket</h2>
    <x-card>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Alterar Status -->
            <div class="bg-branco p-4 rounded-lg border border-padrao">
                <div class="mb-3">
                    <h3 class="text-sm font-medium text-cinza">Alterar Status</h3>
                    <p class="text-sm text-cinza-claro">Mudar o status atual</p>
                </div>
                <x-button variant="outline" size="sm" onclick="openStatusModal()" class="w-full">
                    Status: {{ ucfirst($ticket->status) }}
                </x-button>
            </div>

            <!-- Atribuir Ticket -->
            <div class="bg-branco p-4 rounded-lg border border-padrao">
                <div class="mb-3">
                    <h3 class="text-sm font-medium text-cinza">Responsável</h3>
                    <p class="text-sm text-cinza-claro">
                        @if($ticket->assignedTo)
                            {{ $ticket->assignedTo->name }}
                        @else
                            Não atribuído
                        @endif
                    </p>
                </div>
                <x-button variant="outline" size="sm" onclick="openAssignModal()" class="w-full">
                    {{ $ticket->assignedTo ? 'Reatribuir' : 'Atribuir' }}
                </x-button>
            </div>

            <!-- Alterar Prioridade -->
            <div class="bg-branco p-4 rounded-lg border border-padrao">
                <div class="mb-3">
                    <h3 class="text-sm font-medium text-cinza">Prioridade</h3>
                    <p class="text-sm text-cinza-claro">{{ ucfirst($ticket->priority) }}</p>
                </div>
                <x-button variant="outline" size="sm" onclick="openPriorityModal()" class="w-full">
                    Alterar Prioridade
                </x-button>
            </div>
        </div>
    </x-card>
    @endif

    <!-- Nova Mensagem -->
    @if($ticket->status !== 'fechado')
    <h2 class="section-title mb-4">Adicionar Mensagem</h2>
    <x-card>
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
                        <option value="note">Nota Interna</option>
                        @endif
                    </x-select>
                </div>

                @if(auth()->user()->canManageTickets())
                <div class="flex items-center">
                    <div class="flex items-center h-5">
                        <input type="checkbox" id="is_internal" name="is_internal" value="1" class="h-4 w-4 text-roxo focus:ring-roxo border-padrao rounded">
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
<!-- Modal para preview de anexos -->
<div id="previewModal" class="fixed inset-0 bg-black bg-opacity-10 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-lg max-w-3xl mx-4 max-h-[90vh] overflow-hidden transform transition-all duration-300 scale-95 opacity-0" id="previewModalContent">
            <div class="flex items-center justify-between p-6 border-b border-cinza-claro">
                <h3 id="previewModalLabel" class="text-lg font-semibold text-cinza flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    Preview do Arquivo
                </h3>
                <button type="button" onclick="closePreviewModal()" class="text-cinza-claro hover:text-cinza">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div class="overflow-y-auto" style="max-height: calc(90vh - 140px);">
                <div id="previewContent" class="flex justify-center items-center p-6 min-h-[400px]">
                    <div class="text-center">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-roxo mx-auto"></div>
                        <p class="mt-2 text-cinza-claro">Carregando preview...</p>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center justify-end gap-3 p-6 border-t border-cinza-claro">
                <x-button variant="outline" onclick="closePreviewModal()">
                    Fechar
                </x-button>
                <x-button variant="primary" id="downloadBtn" tag="a" href="#" target="_blank">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Download
                </x-button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Reabrir Ticket -->
<div id="reopen-ticket-modal" class="fixed inset-0 bg-black bg-opacity-10 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-lg max-w-sm mx-4 transform transition-all duration-300 scale-95 opacity-0" id="reopen-ticket-modal-content">
            <div class="flex items-center justify-between p-6 border-b border-cinza-claro">
                <h3 class="text-lg font-semibold text-cinza">Reabrir Ticket</h3>
                <button type="button" onclick="closeReopenTicketModal()" class="text-cinza-claro hover:text-cinza">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0 w-10 h-10 mx-auto bg-azul-claro rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-azul" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </div>
                </div>
                <div class="text-center">
                    <h4 class="text-lg font-medium text-cinza mb-2">Tem certeza?</h4>
                    <p class="text-sm text-cinza-claro mb-4">
                        Esta ação irá alterar o status do ticket <strong>{{ $ticket->ticket_number }}</strong> para <strong>"Aberto"</strong>.
                    </p>
                    <p class="text-xs text-cinza-claro">
                        O ticket voltará a aparecer na lista de tickets ativos.
                    </p>
                </div>
            </div>
            
            <div class="flex items-center justify-end gap-3 p-6 border-t border-cinza-claro">
                <x-button variant="outline" onclick="closeReopenTicketModal()">
                    Cancelar
                </x-button>
                <form action="{{ route('tickets.reopen', $ticket->ticket_number) }}" method="POST" style="display: inline;">
                    @csrf
                    <x-button variant="primary" type="submit">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Sim, Reabrir Ticket
                    </x-button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Alterar Status -->
<div id="status-modal" class="fixed inset-0 bg-black bg-opacity-10 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-lg max-w-md mx-4 transform transition-all duration-300 scale-95 opacity-0" id="status-modal-content">
            <div class="flex items-center justify-between p-6 border-b border-cinza-claro">
                <h3 class="text-lg font-semibold text-cinza flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                    </svg>
                    Alterar Status
                </h3>
                <button type="button" onclick="closeStatusModal()" class="text-cinza-claro hover:text-cinza">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form action="{{ route('tickets.change-status', $ticket->ticket_number) }}" method="POST">
                @csrf
                <div class="p-6 space-y-4">
                    <div>
                        <label for="status" class="block text-sm font-medium text-cinza mb-2">Novo Status *</label>
                        <select id="status" name="status" required class="w-full px-3 py-2 border border-cinza-claro-2 rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent">
                            <option value="">Selecione o status</option>
                            <option value="aberto" {{ $ticket->status == 'aberto' ? 'selected' : '' }}>Aberto</option>
                            <option value="em_andamento" {{ $ticket->status == 'em_andamento' ? 'selected' : '' }}>Em Andamento</option>
                            <option value="resolvido" {{ $ticket->status == 'resolvido' ? 'selected' : '' }}>Resolvido</option>
                            <option value="fechado" {{ $ticket->status == 'fechado' ? 'selected' : '' }}>Fechado</option>
                        </select>
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-medium text-cinza mb-2">Observações (opcional)</label>
                        <textarea id="notes" name="notes" rows="3" placeholder="Adicione observações sobre a mudança de status..." class="w-full px-3 py-2 border border-cinza-claro-2 rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent resize-none"></textarea>
                    </div>
                </div>
                
                <div class="flex items-center justify-end gap-3 p-6 border-t border-cinza-claro">
                    <x-button variant="outline" type="button" onclick="closeStatusModal()">
                        Cancelar
                    </x-button>
                    <x-button variant="primary" type="submit">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                        </svg>
                        Alterar Status
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Atribuir Ticket -->
<div id="assign-modal" class="fixed inset-0 bg-black bg-opacity-10 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-lg max-w-md mx-4 transform transition-all duration-300 scale-95 opacity-0" id="assign-modal-content">
            <div class="flex items-center justify-between p-6 border-b border-cinza-claro">
                <h3 class="text-lg font-semibold text-cinza flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    {{ $ticket->assignedTo ? 'Reatribuir' : 'Atribuir' }} Ticket
                </h3>
                <button type="button" onclick="closeAssignModal()" class="text-cinza-claro hover:text-cinza">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form action="{{ route('tickets.assign', $ticket->ticket_number) }}" method="POST">
                @csrf
                <div class="p-6 space-y-4">
                    <div>
                        <label for="assigned_to" class="block text-sm font-medium text-cinza mb-2">Responsável *</label>
                        <select id="assigned_to" name="assigned_to" required class="w-full px-3 py-2 border border-cinza-claro-2 rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent">
                            <option value="">Selecione o responsável</option>
                            @php
                                $technicians = \App\Models\User::where('role', 'tecnico')->orWhere('role', 'admin')->get();
                            @endphp
                            @foreach($technicians as $technician)
                                <option value="{{ $technician->id }}" {{ $ticket->assigned_to == $technician->id ? 'selected' : '' }}>
                                    {{ $technician->name }} ({{ ucfirst($technician->role) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="assign_notes" class="block text-sm font-medium text-cinza mb-2">Observações (opcional)</label>
                        <textarea id="assign_notes" name="notes" rows="3" placeholder="Adicione observações sobre a atribuição..." class="w-full px-3 py-2 border border-cinza-claro-2 rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent resize-none"></textarea>
                    </div>
                </div>
                
                <div class="flex items-center justify-end gap-3 p-6 border-t border-cinza-claro">
                    <x-button variant="outline" type="button" onclick="closeAssignModal()">
                        Cancelar
                    </x-button>
                    <x-button variant="primary" type="submit">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        {{ $ticket->assignedTo ? 'Reatribuir' : 'Atribuir' }}
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Alterar Prioridade -->
<div id="priority-modal" class="fixed inset-0 bg-black bg-opacity-10 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-lg max-w-md mx-4 transform transition-all duration-300 scale-95 opacity-0" id="priority-modal-content">
            <div class="flex items-center justify-between p-6 border-b border-cinza-claro">
                <h3 class="text-lg font-semibold text-cinza flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    Alterar Prioridade
                </h3>
                <button type="button" onclick="closePriorityModal()" class="text-cinza-claro hover:text-cinza">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form action="{{ route('tickets.change-priority', $ticket->ticket_number) }}" method="POST">
                @csrf
                <div class="p-6 space-y-4">
                    <div>
                        <label for="priority" class="block text-sm font-medium text-cinza mb-2">Nova Prioridade *</label>
                        <select id="priority" name="priority" required class="w-full px-3 py-2 border border-cinza-claro-2 rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent">
                            <option value="">Selecione a prioridade</option>
                            <option value="baixa" {{ $ticket->priority == 'baixa' ? 'selected' : '' }}>Baixa</option>
                            <option value="média" {{ $ticket->priority == 'média' ? 'selected' : '' }}>Média</option>
                            <option value="alta" {{ $ticket->priority == 'alta' ? 'selected' : '' }}>Alta</option>
                        </select>
                    </div>

                    <div class="flex items-center">
                        <div class="flex items-center h-5">
                            <input type="checkbox" id="is_urgent" name="is_urgent" value="1" {{ $ticket->is_urgent ? 'checked' : '' }} class="h-4 w-4 text-roxo focus:ring-roxo border-padrao rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="is_urgent" class="font-medium text-cinza">
                                Marcar como urgente
                            </label>
                            <p class="text-cinza-claro">Tickets urgentes têm prioridade máxima</p>
                        </div>
                    </div>

                    <div>
                        <label for="priority_notes" class="block text-sm font-medium text-cinza mb-2">Observações (opcional)</label>
                        <textarea id="priority_notes" name="notes" rows="3" placeholder="Adicione observações sobre a mudança de prioridade..." class="w-full px-3 py-2 border border-cinza-claro-2 rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent resize-none"></textarea>
                    </div>
                </div>
                
                <div class="flex items-center justify-end gap-3 p-6 border-t border-cinza-claro">
                    <x-button variant="outline" type="button" onclick="closePriorityModal()">
                        Cancelar
                    </x-button>
                    <x-button variant="primary" type="submit">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        Alterar Prioridade
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openCloseTicketModal() {
    const modal = document.getElementById('close-ticket-modal');
    const content = document.getElementById('close-ticket-modal-content');
    
    modal.classList.remove('hidden');
    
    // Animação de entrada
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeCloseTicketModal() {
    const modal = document.getElementById('close-ticket-modal');
    const content = document.getElementById('close-ticket-modal-content');
    
    // Animação de saída
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    
    // Esconder modal após animação
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

// Fechar modal ao clicar fora dele
document.addEventListener('click', function(event) {
    const closeModal = document.getElementById('close-ticket-modal');
    const reopenModal = document.getElementById('reopen-ticket-modal');
    const statusModal = document.getElementById('status-modal');
    const assignModal = document.getElementById('assign-modal');
    const priorityModal = document.getElementById('priority-modal');
    
    if (event.target === closeModal) {
        closeCloseTicketModal();
    }
    if (event.target === reopenModal) {
        closeReopenTicketModal();
    }
    if (event.target === statusModal) {
        closeStatusModal();
    }
    if (event.target === assignModal) {
        closeAssignModal();
    }
    if (event.target === priorityModal) {
        closePriorityModal();
    }
});

// Fechar modal com ESC
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeCloseTicketModal();
        closeReopenTicketModal();
        closeStatusModal();
        closeAssignModal();
        closePriorityModal();
        closePreviewModal();
    }
});

function openStatusModal() {
    const modal = document.getElementById('status-modal');
    const content = document.getElementById('status-modal-content');
    
    modal.classList.remove('hidden');
    
    // Animação de entrada
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeStatusModal() {
    const modal = document.getElementById('status-modal');
    const content = document.getElementById('status-modal-content');
    
    // Animação de saída
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    
    // Esconder modal após animação
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

function openReopenTicketModal() {
    const modal = document.getElementById('reopen-ticket-modal');
    const content = document.getElementById('reopen-ticket-modal-content');
    
    modal.classList.remove('hidden');
    
    // Animação de entrada
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeReopenTicketModal() {
    const modal = document.getElementById('reopen-ticket-modal');
    const content = document.getElementById('reopen-ticket-modal-content');
    
    // Animação de saída
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    
    // Esconder modal após animação
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

// Funções para Modal de Atribuição
function openAssignModal() {
    const modal = document.getElementById('assign-modal');
    const content = document.getElementById('assign-modal-content');
    
    modal.classList.remove('hidden');
    
    // Animação de entrada
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeAssignModal() {
    const modal = document.getElementById('assign-modal');
    const content = document.getElementById('assign-modal-content');
    
    // Animação de saída
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    
    // Esconder modal após animação
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

// Funções para Modal de Prioridade
function openPriorityModal() {
    const modal = document.getElementById('priority-modal');
    const content = document.getElementById('priority-modal-content');
    
    modal.classList.remove('hidden');
    
    // Animação de entrada
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closePriorityModal() {
    const modal = document.getElementById('priority-modal');
    const content = document.getElementById('priority-modal-content');
    
    // Animação de saída
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    
    // Esconder modal após animação
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

function closePreviewModal() {
    const modal = document.getElementById('previewModal');
    const content = document.getElementById('previewModalContent');
    
    // Animação de saída
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    
    // Esconder modal após animação
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

// Fechar modal de preview ao clicar fora dele
document.addEventListener('click', function(event) {
    const modal = document.getElementById('previewModal');
    if (event.target === modal) {
        closePreviewModal();
    }
});
    // Função changeStatus removida - agora usa formulário PHP direto

    // Funções AJAX antigas removidas - agora usa formulários PHP diretos

    // Função reopenTicket AJAX removida - agora usa formulário PHP direto

    function previewAttachment(attachmentId, filename, fileType) {
        // Mostrar modal
        const modal = document.getElementById('previewModal');
        const content = document.getElementById('previewModalContent');
        
        modal.classList.remove('hidden');
        
        // Animação de entrada
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
        
        // Atualizar título
        document.getElementById('previewModalLabel').innerHTML = `
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-roxo mx-auto"></div>
                <p class="mt-2 text-cinza-claro">Carregando preview...</p>
            </div>
        `;
        
        // Determinar como exibir o arquivo
        const previewUrl = `/attachments/${attachmentId}/preview`;
        
        if (fileType.startsWith('image/')) {
            // Para imagens
            const img = document.createElement('img');
            img.src = previewUrl;
            img.className = 'max-w-full h-auto';
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
                    pre.className = 'bg-cinza-claro-2 p-3 rounded text-left';
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
