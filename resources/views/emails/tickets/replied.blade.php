@extends('emails.layouts.app')

@section('title', 'Nova Resposta no Ticket - 8Bits Pro')

@section('content')
<h2 style="color: #667eea; margin-bottom: 20px;">💬 Nova Resposta no Seu Ticket</h2>

<p>Olá <strong>{{ $ticket->contact->name }}</strong>,</p>

<p>Uma nova resposta foi adicionada ao seu ticket. Nossa equipe de suporte respondeu à sua solicitação.</p>

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
        <span class="info-label">Status Atual:</span>
        <span class="info-value">
            <span class="status-badge status-{{ $ticket->status }}">
                {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
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
        <span class="info-label">Respondido em:</span>
        <span class="info-value">{{ $reply->created_at->format('d/m/Y H:i') }}</span>
    </div>
    
    @if($repliedBy)
    <div class="info-row">
        <span class="info-label">Respondido por:</span>
        <span class="info-value">{{ $repliedBy->name }}</span>
    </div>
    @endif
</div>

<div style="background-color: #f8f9fa; padding: 20px; border-radius: 6px; margin: 20px 0; border-left: 4px solid #667eea;">
    <h4 style="margin: 0 0 15px 0; color: #333;">📝 Nova Resposta:</h4>
    <div style="white-space: pre-wrap; line-height: 1.6;">{{ $reply->content }}</div>
</div>

<div style="text-align: center; margin: 30px 0;">
    <a href="{{ route('tickets.show', $ticket->id) }}" class="button">
        Ver Ticket Completo
    </a>
</div>

@if($ticket->status === 'resolvido')
<div style="background-color: #d4edda; padding: 15px; border-radius: 6px; margin: 20px 0; border-left: 4px solid #28a745;">
    <h4 style="margin: 0 0 10px 0; color: #155724;">✅ Ticket Resolvido!</h4>
    <p style="margin: 0; color: #155724;">Seu ticket foi marcado como resolvido. Se a solução atendeu suas necessidades, você pode fechá-lo. Caso contrário, responda ao ticket para reabri-lo.</p>
</div>
@endif

<p>Se você tiver alguma dúvida sobre a resposta ou precisar de esclarecimentos adicionais, responda diretamente a este email ou acesse o ticket no sistema.</p>

<p>Atenciosamente,<br>
<strong>Equipe de Suporte Técnico - 8Bits Pro</strong></p>
@endsection
