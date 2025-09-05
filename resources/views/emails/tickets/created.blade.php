@extends('emails.layouts.app')

@section('title', 'Ticket Criado - 8Bits Pro')

@section('content')
<h2 style="color: #667eea; margin-bottom: 20px;">🎫 Ticket Criado com Sucesso!</h2>

<p>Olá <strong>{{ $ticket->contact->name }}</strong>,</p>

<p>Seu ticket foi criado com sucesso em nosso sistema de suporte. Nossa equipe técnica analisará sua solicitação e retornará em breve.</p>

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
        <span class="info-label">Status:</span>
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
        <span class="info-label">Descrição:</span>
        <span class="info-value">{{ $ticket->description }}</span>
    </div>
</div>

<div style="text-align: center; margin: 30px 0;">
    <a href="{{ route('tickets.show', $ticket->ticket_number) }}" class="button">
        Ver Ticket Completo
    </a>
</div>

<div style="background-color: #e3f2fd; padding: 15px; border-radius: 6px; margin: 20px 0;">
    <h4 style="margin: 0 0 10px 0; color: #1976d2;">📋 Próximos Passos:</h4>
    <ul style="margin: 0; padding-left: 20px;">
        <li>Nossa equipe analisará seu ticket em até 24 horas</li>
        <li>Você receberá atualizações por email sobre o progresso</li>
        <li>Em caso de urgência, entre em contato conosco pelo telefone</li>
    </ul>
</div>

<p>Se você tiver alguma dúvida ou precisar de informações adicionais, não hesite em nos contatar.</p>

<p>Atenciosamente,<br>
<strong>Equipe de Suporte Técnico - 8Bits Pro</strong></p>
@endsection
