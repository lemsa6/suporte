@extends('layouts.app')

@section('title', 'Configurações de Notificações')

@section('header')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between">
    <div class="flex-grow-1">
        <h2 class="fs-2 fw-bold text-dark mb-1">
            Configurações de Notificações
        </h2>
        <p class="text-muted">
            Configure tipos de notificação, frequência e preferências do sistema
        </p>
    </div>
    <div class="mt-3 mt-md-0">
        <a href="{{ route('admin.settings.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center">
            <svg class="me-2" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Voltar
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="row g-4">
    <div class="col-12 col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0 fw-semibold">Configurações de Notificações</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <svg class="me-2" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <strong>Em desenvolvimento:</strong> Esta funcionalidade será implementada em breve.
                </div>
                
                <div class="row g-3">
                    <div class="col-12">
                        <h6 class="fw-semibold text-dark mb-3">Tipos de Notificação</h6>
                        <div class="d-flex flex-column gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="notify_ticket_created" checked disabled>
                                <label class="form-check-label fw-medium text-dark" for="notify_ticket_created">
                                    Novo ticket criado
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="notify_ticket_replied" checked disabled>
                                <label class="form-check-label fw-medium text-dark" for="notify_ticket_replied">
                                    Resposta ao ticket
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="notify_ticket_status_changed" checked disabled>
                                <label class="form-check-label fw-medium text-dark" for="notify_ticket_status_changed">
                                    Status do ticket alterado
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="notify_ticket_closed" checked disabled>
                                <label class="form-check-label fw-medium text-dark" for="notify_ticket_closed">
                                    Ticket fechado
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0 fw-semibold">Status Atual</h5>
            </div>
            <div class="card-body">
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                            <svg class="text-success" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="fw-medium text-dark">Sistema Ativo</div>
                            <small class="text-muted">Notificações funcionando</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                            <svg class="text-info" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="fw-medium text-dark">Email Configurado</div>
                            <small class="text-muted">SMTP funcionando</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
