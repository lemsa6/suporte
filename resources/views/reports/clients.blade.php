@extends('layouts.app')

@section('title', 'Relatório de Clientes')

@section('header')
<div class="md:d-flex md:align-items-center md:justify-content-between">
    <div class="min-w-0 d-flex-grow-1">
        <h2 class="fs-2 fw-bold leading-7 text-dark sm:truncate sm:fs-1 sm:tracking-tight">
            Relatório de Clientes
        </h2>
        <p class="mt-1 fs-6 text-muted">
            Analise e filtre todos os clientes do sistema
        </p>
    </div>
    <div class="mt-4 d-flex md:ml-4 md:mt-0">
        <a href="{{ route('reports.index') }}" 
            class="inline-d-flex align-items-center px-4 py-2 border border-light shadow-sm-sm fs-6 fw-medium rounded text-dark bg-white hover:bg-light focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500">
            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            Voltar ao Dashboard
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Filtros -->
    <div class="bg-white shadow-sm rounded">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="fs-4 fw-medium text-dark mb-4">Filtros</h3>
            
            <form method="GET" action="{{ route('reports.clients') }}" class="grid row-cols-1 md:row-cols-3 gap-4">
                <!-- Status -->
                <div>
                    <label for="status" class="block fs-6 fw-medium text-dark">Status</label>
                    <select name="status" id="status" class="mt-1 block w-full rounded border-light shadow-sm-sm focus:border-brand-500 focus:ring-brand-500 sm:fs-6">
                        <option value="">Todos os status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Ativo</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inativo</option>
                    </select>
                </div>

                <!-- Data Inicial -->
                <div>
                    <label for="date_from" class="block fs-6 fw-medium text-dark">Data Inicial</label>
                    <input type="date" name="date_from" id="date_from" 
                        class="mt-1 block w-full rounded border-light shadow-sm-sm focus:border-brand-500 focus:ring-brand-500 sm:fs-6"
                        value="{{ request('date_from') }}">
                </div>

                <!-- Data Final -->
                <div>
                    <label for="date_to" class="block fs-6 fw-medium text-dark">Data Final</label>
                    <input type="date" name="date_to" id="date_to" 
                        class="mt-1 block w-full rounded border-light shadow-sm-sm focus:border-brand-500 focus:ring-brand-500 sm:fs-6"
                        value="{{ request('date_to') }}">
                </div>

                <!-- Botões -->
                <div class="md:col-span-3 d-flex justify-content-end space-x-3">
                    <a href="{{ route('reports.clients') }}" 
                        class="inline-d-flex align-items-center px-4 py-2 border border-light shadow-sm-sm fs-6 fw-medium rounded text-dark bg-white hover:bg-light focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500">
                        Limpar Filtros
                    </a>
                    
                    <button type="submit" 
                        class="inline-d-flex align-items-center px-4 py-2 border border-transparent shadow-sm-sm fs-6 fw-medium rounded text-white bg-primary hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Aplicar Filtros
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Resultados -->
    <div class="bg-white shadow-sm rounded">
        <div class="px-4 py-5 sm:p-6">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h3 class="fs-4 fw-medium text-dark">
                    Resultados ({{ $clients->total() }} clientes encontrados)
                </h3>
                
                @if(request()->hasAny(['status', 'date_from', 'date_to']))
                    <span class="inline-d-flex align-items-center px-2.5 py-0.5 rounded-circle fs-6 fw-medium bg-info bg-opacity-10 text-blue-800">
                        Filtros aplicados
                    </span>
                @endif
            </div>

            @if($clients->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-6 py-3 text-left fs-6 fw-medium text-muted uppercase tracking-wider">
                                    Cliente
                                </th>
                                <th class="px-6 py-3 text-left fs-6 fw-medium text-muted uppercase tracking-wider">
                                    CNPJ
                                </th>
                                <th class="px-6 py-3 text-left fs-6 fw-medium text-muted uppercase tracking-wider">
                                    Contatos
                                </th>
                                <th class="px-6 py-3 text-left fs-6 fw-medium text-muted uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left fs-6 fw-medium text-muted uppercase tracking-wider">
                                    Tickets
                                </th>
                                <th class="px-6 py-3 text-left fs-6 fw-medium text-muted uppercase tracking-wider">
                                    Criado
                                </th>
                                <th class="px-6 py-3 text-left fs-6 fw-medium text-muted uppercase tracking-wider">
                                    Ações
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($clients as $client)
                                <tr class="hover:bg-light">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="d-flex align-items-center">
                                            <div class="d-flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded bg-brand-100 d-flex align-items-center justify-content-center">
                                                    <svg class="h-6 w-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="fs-6 fw-medium text-dark">
                                                    {{ $client->company_name }}
                                                </div>
                                                @if($client->trade_name)
                                                    <div class="fs-6 text-muted">
                                                        {{ $client->trade_name }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="fs-6 text-dark font-mono">
                                            {{ $client->cnpj }}
                                        </div>
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="fs-6 text-dark">
                                            @if($client->contacts_count > 0)
                                                <span class="inline-d-flex align-items-center px-2.5 py-0.5 rounded-circle fs-6 fw-medium bg-success bg-opacity-10 text-green-800">
                                                    {{ $client->contacts_count }} contato(s)
                                                </span>
                                            @else
                                                <span class="inline-d-flex align-items-center px-2.5 py-0.5 rounded-circle fs-6 fw-medium bg-danger bg-opacity-10 text-red-800">
                                                    Sem contatos
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($client->is_active)
                                            <span class="inline-d-flex align-items-center px-2.5 py-0.5 rounded-circle fs-6 fw-medium bg-success bg-opacity-10 text-green-800">
                                                Ativo
                                            </span>
                                        @else
                                            <span class="inline-d-flex align-items-center px-2.5 py-0.5 rounded-circle fs-6 fw-medium bg-danger bg-opacity-10 text-red-800">
                                                Inativo
                                            </span>
                                        @endif
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="fs-6 text-dark">
                                            @if($client->tickets_count > 0)
                                                <span class="inline-d-flex align-items-center px-2.5 py-0.5 rounded-circle fs-6 fw-medium bg-info bg-opacity-10 text-blue-800">
                                                    {{ $client->tickets_count }} ticket(s)
                                                </span>
                                            @else
                                                <span class="inline-d-flex align-items-center px-2.5 py-0.5 rounded-circle fs-6 fw-medium bg-light text-dark">
                                                    Sem tickets
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap fs-6 text-muted">
                                        {{ $client->created_at->format('d/m/Y') }}
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap fs-6 fw-medium">
                                        <a href="{{ route('clients.show', $client) }}" 
                                            class="text-primary hover:text-brand-900">
                                            Ver detalhes
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginação -->
                <div class="mt-6">
                    {{ $clients->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <h3 class="mt-2 fs-6 fw-medium text-dark">Nenhum cliente encontrado</h3>
                    <p class="mt-1 fs-6 text-muted">
                        @if(request()->hasAny(['status', 'date_from', 'date_to']))
                            Tente ajustar os filtros aplicados.
                        @else
                            Não há clientes cadastrados no sistema.
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
