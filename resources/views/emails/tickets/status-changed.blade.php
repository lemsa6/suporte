@extends('emails.layouts.app')

@section('title', 'Status do Ticket Alterado - 8Bits Pro')

@section('content')
<h2 style="color: #667eea; margin-bottom: 20px;">🔄 Status do Ticket Alterado</h2>

<p>Olá <strong>{{ $ticket->contact->name }}</strong>,</p>

<p>O status do seu ticket foi alterado. Aqui estão os detalhes da mudança:</p>

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
        <span class="info-label">Status Anterior:</span>
        <span class="info-value">
            <span class="status-badge status-{{ $oldStatus }}">
                {{ ucfirst(str_replace('_', ' ', $oldStatus)) }}
            </span>
        </span>
    </div>
    
    <div class="info-row">
        <span class="info-label">Novo Status:</span>
        <span class="info-value">
            <span class="status-badge status-{{ $newStatus }}">
                {{ ucfirst(str_replace('_', ' ', $newStatus)) }}
            </span>
        </span>
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
        <span class="info-label">Alterado em:</span>
        <span class="info-value">{{ now()->format('d/m/Y H:i') }}</span>
    </div>
    
    @if($changedBy)
    <div class="info-row">
        <span class="info-label">Alterado por:</span>
        <span class="info-value">{{ $changedBy->name }}</span>
    </div>
    @endif
</div>

@if($newStatus === 'em_andamento')
<div style="background-color: #cce5ff; padding: 15px; border-radius: 6px; margin: 20px 0; border-left: 4px solid #007bff;">
    <h4 style="margin: 0 0 10px 0; color: #004085;">🚀 Ticket em Andamento</h4>
    <p style="margin: 0; color: #004085;">Seu ticket está sendo trabalhado por nossa equipe técnica. Você receberá atualizações conforme o progresso.</p>
</div>
@elseif($newStatus === 'resolvido')
<div style="background-color: #d4edda; padding: 15px; border-radius: 6px; margin: 20px 0; border-left: 4px solid #28a745;">
    <h4 style="margin: 0 0 10px 0; color: #155724;">✅ Ticket Resolvido</h4>
    <p style="margin: 0; color: #155724;">Seu ticket foi marcado como resolvido. Por favor, verifique se a solução atende suas necessidades e confirme o fechamento.</p>
</div>
@elseif($newStatus === 'fechado')
<div style="background-color: #e2e3e5; padding: 15px; border-radius: 6px; margin: 20px 0; border-left: 4px solid #6c757d;">
    <h4 style="margin: 0 0 10px 0; color: #383d41;">🔒 Ticket Fechado</h4>
    <p style="margin: 0; color: #383d41;">Seu ticket foi fechado. Se precisar de suporte adicional, abra um novo ticket.</p>
</div>
@endif

<div style="text-align: center; margin: 30px 0;">
    <a href="{{ route('tickets.show', $ticket->ticket_number) }}" class="button">
        Ver Ticket Completo
    </a>
</div>

@if($newStatus === 'resolvido')
<div style="background-color: #fff3cd; padding: 15px; border-radius: 6px; margin: 20px 0; border-left: 4px solid #ffc107;">
    <h4 style="margin: 0 0 10px 0; color: #856404;">⚠️ Importante</h4>
    <p style="margin: 0; color: #856404;">Se a solução não atender suas necessidades, responda ao ticket para reabri-lo. Caso contrário, o ticket será fechado automaticamente em alguns dias.</p>
</div>
@endif

<p>Se você tiver alguma dúvida sobre esta mudança de status, não hesite em nos contatar.</p>

<p>Atenciosamente,<br>
<strong>Equipe de Suporte Técnico - 8Bits Pro</strong></p>
@endsection
