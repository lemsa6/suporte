@extends('layouts.app')

@section('title', 'Configurações do Sistema')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div class="mb-4 sm:mb-0">
        <span class="text-sm text-gray-500">Administração</span>
        <h1 class="page-title mt-1">Configurações do Sistema</h1>
        <p class="text-gray-600 mt-2">Configure as informações básicas do sistema e da empresa</p>
    </div>
    <x-button variant="outline" tag="a" href="{{ route('admin.settings.index') }}">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Voltar
    </x-button>
</div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <h2 class="section-title mb-4">Informações Básicas</h2>
        <x-card>
            <form method="POST" action="{{ route('admin.settings.system.update') }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <!-- Nome do Sistema -->
                    <x-input 
                        label="Nome do Sistema" 
                        name="system_name" 
                        value="{{ old('system_name', $settings['system_name']) }}"
                        required
                        error="{{ $errors->first('system_name') }}"
                    />

                    <!-- Nome da Empresa -->
                    <x-input 
                        label="Nome da Empresa" 
                        name="company_name" 
                        value="{{ old('company_name', $settings['company_name']) }}"
                        required
                        error="{{ $errors->first('company_name') }}"
                    />

                    <!-- Email da Empresa -->
                    <x-input 
                        label="Email da Empresa" 
                        name="company_email" 
                        type="email"
                        value="{{ old('company_email', $settings['company_email']) }}"
                        required
                        error="{{ $errors->first('company_email') }}"
                    />

                    <!-- Telefone da Empresa -->
                    <x-input 
                        label="Telefone da Empresa" 
                        name="company_phone" 
                        value="{{ old('company_phone', $settings['company_phone']) }}"
                        required
                        error="{{ $errors->first('company_phone') }}"
                    />

                    <!-- Fuso Horário -->
                    <x-select 
                        label="Fuso Horário" 
                        name="timezone"
                        :options="[
                            'America/Sao_Paulo' => 'América/São_Paulo (GMT-3)',
                            'America/New_York' => 'América/New_York (GMT-5)',
                            'Europe/London' => 'Europa/Londres (GMT+0)',
                            'Asia/Tokyo' => 'Ásia/Tóquio (GMT+9)'
                        ]"
                        :selected="old('timezone', $settings['timezone'])"
                        required
                        error="{{ $errors->first('timezone') }}"
                    />

                    <!-- Endereço da Empresa -->
                    <x-textarea 
                        label="Endereço da Empresa" 
                        name="company_address" 
                        rows="3"
                        value="{{ old('company_address', $settings['company_address']) }}"
                        error="{{ $errors->first('company_address') }}"
                    />

                    <!-- Website da Empresa -->
                    <x-input 
                        label="Website da Empresa" 
                        name="company_website" 
                        type="url"
                        value="{{ old('company_website', $settings['company_website']) }}"
                        placeholder="https://www.empresa.com.br"
                        error="{{ $errors->first('company_website') }}"
                    />

                    <!-- Horário de Atendimento -->
                    <x-input 
                        label="Horário de Atendimento" 
                        name="company_working_hours" 
                        value="{{ old('company_working_hours', $settings['company_working_hours']) }}"
                        placeholder="Segunda a Sexta, 8h às 18h"
                        error="{{ $errors->first('company_working_hours') }}"
                    />
                </div>

                <div class="flex justify-end">
                    <x-button variant="primary" type="submit">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Salvar Configurações
                    </x-button>
                </div>
            </form>
        </x-card>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0 fw-semibold">Informações do Sistema</h5>
            </div>
            <div class="card-body">
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Versão do Laravel:</span>
                        <span class="fw-medium">{{ app()->version() }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Versão do PHP:</span>
                        <span class="fw-medium">{{ PHP_VERSION }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Ambiente:</span>
                        <span class="fw-medium">
                            <span class="badge bg-{{ app()->environment('production') ? 'success' : 'warning' }}">
                                {{ app()->environment() }}
                            </span>
                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Debug:</span>
                        <span class="fw-medium">
                            <span class="badge bg-{{ config('app.debug') ? 'danger' : 'success' }}">
                                {{ config('app.debug') ? 'Ativado' : 'Desativado' }}
                            </span>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0 fw-semibold">Ações Rápidas</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <x-button variant="outline" size="sm" tag="a" href="{{ route('admin.settings.email') }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Configurar Email
                    </x-button>
                    <x-button variant="outline" size="sm" tag="a" href="{{ route('admin.settings.templates') }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Editar Templates
                    </x-button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
