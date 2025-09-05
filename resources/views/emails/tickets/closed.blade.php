@extends('emails.layouts.app')

@section('title', 'Ticket Fechado - 8Bits Pro')

@section('content')
<h2 style="color: #667eea; margin-bottom: 20px;">✅ Ticket Fechado com Sucesso</h2>

<p>Olá <strong>{{ $ticket->contact->name }}</strong>,</p>

<p>Seu ticket foi fechado com sucesso. Agradecemos por utilizar nosso sistema de suporte técnico.</p>

<div class="ticket-info">
    <div class="ticket-number">Ticket #{{ $ticket->ticket_number }}</div>
    
    <div class="info-row">
        <span class="info-label">Título:</span>
        <span class="info-value">{{ $ticket->title }}</span>
    </div>
    
    <div class="info-row">
        <span class="info-label">Empresa:</span>
        <span class="info-value">{{ $ticket->client->company_name }}</span>
    </div>
    
    <div class="info-row">
        <span class="info-label">Categoria:</span>
        <span class="info-value">{{ $ticket->category->name }}</span>
    </div>
    
    <div class="info-row">
        <span class="info-label">Prioridade:</span>
        <span class="info-value">
            <span class="priority-badge priority-{{ $ticket->priority }}">
                {{ ucfirst($ticket->priority) }}
            </span>
        </span>
    </div>
    
    <div class="info-row">
        <span class="info-label">Status Final:</span>
        <span class="info-value">
            <span class="status-badge status-{{ $ticket->status }}">
                {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
            </span>
        </span>
    </div>
    
    <div class="info-row">
        <span class="info-label">Data de Abertura:</span>
        <span class="info-value">{{ $ticket->created_at->format('d/m/Y H:i') }}</span>
    </div>
    
    <div class="info-row">
        <span class="info-label">Data de Fechamento:</span>
        <span class="info-value">{{ $ticket->closed_at->format('d/m/Y H:i') }}</span>
    </div>
    
    <div class="info-row">
        <span class="info-label">Tempo de Resolução:</span>
        <span class="info-value">{{ $resolutionTime }}</span>
    </div>
    
    @if($closedBy)
    <div class="info-row">
        <span class="info-label">Fechado por:</span>
        <span class="info-value">{{ $closedBy->name }}</span>
    </div>
    @endif
</div>

@if($ticket->resolution_notes)
<div style="background-color: #f8f9fa; padding: 20px; border-radius: 6px; margin: 20px 0; border-left: 4px solid #28a745;">
    <h4 style="margin: 0 0 15px 0; color: #333;">📝 Notas da Resolução:</h4>
    <div style="white-space: pre-wrap; line-height: 1.6;">{{ $ticket->resolution_notes }}</div>
</div>
@endif

<div style="text-align: center; margin: 30px 0;">
    <a href="{{ route('tickets.show', $ticket->id) }}" class="button">
        Ver Histórico do Ticket
    </a>
</div>

<div style="background-color: #d1ecf1; padding: 15px; border-radius: 6px; margin: 20px 0; border-left: 4px solid #17a2b8;">
    <h4 style="margin: 0 0 10px 0; color: #0c5460;">📊 Resumo da Atendimento</h4>
    <ul style="margin: 0; padding-left: 20px; color: #0c5460;">
        <li><strong>Tempo total de resolução:</strong> {{ $resolutionTime }}</li>
        <li><strong>Status final:</strong> {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</li>
        <li><strong>Prioridade:</strong> {{ ucfirst($ticket->priority) }}</li>
        @if($ticket->assignedTo)
        <li><strong>Técnico responsável:</strong> {{ $ticket->assignedTo->name }}</li>
        @endif
    </ul>
</div>

<div style="background-color: #fff3cd; padding: 15px; border-radius: 6px; margin: 20px 0; border-left: 4px solid #ffc107;">
    <h4 style="margin: 0 0 10px 0; color: #856404;">💡 Próximos Passos</h4>
    <ul style="margin: 0; padding-left: 20px; color: #856404;">
        <li>Se precisar de suporte adicional, abra um novo ticket</li>
        <li>Mantenha este email como comprovante do atendimento</li>
        <li>Em caso de dúvidas, entre em contato conosco</li>
    </ul>
</div>

<p>Obrigado por escolher a <strong>8Bits Pro</strong> para suas necessidades de suporte técnico. Estamos sempre à disposição para ajudá-lo.</p>

<p>Atenciosamente,<br>
<strong>Equipe de Suporte Técnico - 8Bits Pro</strong></p>
@endsection
