@extends('layouts.app')

@section('title', 'Relatório de Tickets')

@section('header')
<div class="d-flex align-items-center justify-content-between">
    <div class="d-flex align-items-center">
        <x-button variant="outline" size="sm" tag="a" href="{{ route('reports.index') }}" class="me-3">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Voltar
        </x-button>
        <div>
            <h2 class="h4 mb-0 fw-bold text-dark">
                Relatório de Tickets
            </h2>
            <p class="text-muted mb-0">
                Analise e filtre todos os tickets do sistema
            </p>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="d-flex flex-column gap-4">
    <!-- Filtros -->
    <div class="card border-0">
        <div class="card-header">
            <h3 class="h5 mb-0 fw-medium text-dark">Filtros</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('reports.tickets') }}" class="row g-3">
                <!-- Status -->
                <div class="col-12 col-sm-6 col-lg-2">
                    <label for="status" class="form-label fw-medium">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">Todos os status</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $status)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Prioridade -->
                <div class="col-12 col-sm-6 col-lg-2">
                    <label for="priority" class="form-label fw-medium">Prioridade</label>
                    <select name="priority" id="priority" class="form-select">
                        <option value="">Todas as prioridades</option>
                        @foreach($priorities as $priority)
                            <option value="{{ $priority }}" {{ request('priority') == $priority ? 'selected' : '' }}>
                                {{ ucfirst($priority) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Categoria -->
                <div class="col-12 col-sm-6 col-lg-2">
                    <label for="category_id" class="form-label fw-medium">Categoria</label>
                    <select name="category_id" id="category_id" class="form-select">
                        <option value="">Todas as categorias</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Data Inicial -->
                <div class="col-12 col-sm-6 col-lg-2">
                    <label for="date_from" class="form-label fw-medium">Data Inicial</label>
                    <input type="date" name="date_from" id="date_from" 
                        class="form-control"
                        value="{{ request('date_from') }}">
                </div>

                <!-- Data Final -->
                <div class="col-12 col-sm-6 col-lg-2">
                    <label for="date_to" class="form-label fw-medium">Data Final</label>
                    <input type="date" name="date_to" id="date_to" 
                        class="form-control"
                        value="{{ request('date_to') }}">
                </div>

                <!-- Botões -->
                <div class="col-12 col-lg-2 d-flex align-items-end gap-2">
                    <x-button variant="outline" tag="a" href="{{ route('reports.tickets') }}">
                        Limpar
                    </x-button>
                    
                    <x-button variant="primary" type="submit">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Filtrar
                    </x-button>
                </div>
            </form>
        </div>
    </div>

    <!-- Resultados -->
    <div class="card border-0">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="h5 mb-0 fw-medium text-dark">
                Resultados ({{ $tickets->total() }} tickets encontrados)
            </h3>
            
            @if(request()->hasAny(['status', 'priority', 'category_id', 'date_from', 'date_to']))
                <span class="badge bg-info">
                    Filtros aplicados
                </span>
            @endif
        </div>
        <div class="card-body p-0">

            @if($tickets->count() > 0)
                <div class="data-table">
                    <div class="table-container">
                        <table class="table table-hover mb-0">
                            <thead class="table-header">
                                <tr>
                                    <th class="border-0">Ticket</th>
                                    <th class="border-0">Cliente</th>
                                    <th class="border-0">Categoria</th>
                                    <th class="border-0">Status</th>
                                    <th class="border-0">Prioridade</th>
                                    <th class="border-0">Atribuído</th>
                                    <th class="border-0">Criado</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($tickets as $ticket)
                                <tr class="clickable" onclick="window.location.href='{{ route('tickets.show', $ticket->ticket_number) }}'">
                                    <td class="align-middle">
                                        <div class="d-flex align-items-center">
                                            <div class="category-color-avatar me-3" style="--category-color: {{ $ticket->category->color ?? '#6B7280' }};">
                                                <span class="text-white fw-bold">#{{ $ticket->ticket_number }}</span>
                                            </div>
                                            <div>
                                                <div class="fw-medium text-dark">
                                                    {{ Str::limit($ticket->title, 50) }}
                                                </div>
                                                @if($ticket->is_urgent)
                                                    <span class="badge bg-danger">Urgente</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="align-middle">
                                        <div class="fw-medium text-dark">{{ $ticket->client->company_name }}</div>
                                        <div class="text-muted small">{{ $ticket->client->trade_name }}</div>
                                    </td>
                                    
                                    <td class="align-middle">
                                        <span class="category-color-preview" 
                                            style="--category-color: {{ $ticket->category->color ?? '#6B7280' }}; background-color: var(--category-color)20; color: var(--category-color);">
                                            {{ $ticket->category->name }}
                                        </span>
                                    </td>
                                    
                                    <td class="align-middle">
                                        @switch($ticket->status)
                                            @case('aberto')
                                                <span class="badge bg-warning text-dark">Aberto</span>
                                                @break
                                            @case('em_andamento')
                                                <span class="badge bg-info">Em Andamento</span>
                                                @break
                                            @case('resolvido')
                                                <span class="badge bg-success">Resolvido</span>
                                                @break
                                            @case('fechado')
                                                <span class="badge bg-secondary">Fechado</span>
                                                @break
                                            @default
                                                <span class="badge bg-light text-dark">{{ ucfirst($ticket->status) }}</span>
                                        @endswitch
                                    </td>
                                    
                                    <td class="align-middle">
                                        @switch($ticket->priority)
                                            @case('baixa')
                                                <span class="badge bg-success">Baixa</span>
                                                @break
                                            @case('média')
                                                <span class="badge bg-warning text-dark">Média</span>
                                                @break
                                            @case('alta')
                                                <span class="badge bg-danger">Alta</span>
                                                @break
                                            @default
                                                <span class="badge bg-light text-dark">{{ ucfirst($ticket->priority) }}</span>
                                        @endswitch
                                    </td>
                                    
                                    <td class="align-middle">
                                        @if($ticket->assignedTo)
                                            <div class="d-flex align-items-center">
                                                <div class="user-avatar-small me-2">
                                                    <span class="fw-medium">{{ substr($ticket->assignedTo->name, 0, 1) }}</span>
                                                </div>
                                                <div>
                                                    <div class="fw-medium text-dark">{{ $ticket->assignedTo->name }}</div>
                                                    <div class="text-muted small">{{ ucfirst($ticket->assignedTo->role) }}</div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">Não atribuído</span>
                                        @endif
                                    </td>
                                    
                                    <td class="align-middle text-muted">
                                        {{ $ticket->created_at->format('d/m/Y H:i') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        </table>
                    </div>
                </div>

                <!-- Paginação -->
                <div class="p-3">
                    {{ $tickets->appends(request()->query())->links() }}
                </div>
            @else
                <div class="empty-state">
                    <svg width="48" height="48" fill="none" stroke="currentColor" class="mb-3" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <h6>Nenhum ticket encontrado</h6>
                    <p class="text-muted">
                        @if(request()->hasAny(['status', 'priority', 'category_id', 'date_from', 'date_to']))
                            Tente ajustar os filtros aplicados.
                        @else
                            Não há tickets cadastrados no sistema.
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
