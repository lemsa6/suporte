@extends('layouts.app')

@section('title', 'Configurações do Sistema')

@section('header')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between">
    <div class="flex-grow-1">
        <h2 class="fs-2 fw-bold text-dark mb-1">
            Configurações do Sistema
        </h2>
        <p class="text-muted">
            Configure as informações básicas do sistema e da empresa
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
                <h5 class="mb-0 fw-semibold">Informações Básicas</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.settings.system.update') }}" class="d-flex flex-column gap-4">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <!-- Nome do Sistema -->
                        <div class="col-12">
                            <label for="system_name" class="form-label fw-medium text-dark">Nome do Sistema *</label>
                            <input type="text" id="system_name" name="system_name" 
                                class="form-control @error('system_name') is-invalid @enderror"
                                value="{{ old('system_name', $settings['system_name']) }}" required>
                            @error('system_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nome da Empresa -->
                        <div class="col-12 col-md-6">
                            <label for="company_name" class="form-label fw-medium text-dark">Nome da Empresa *</label>
                            <input type="text" id="company_name" name="company_name" 
                                class="form-control @error('company_name') is-invalid @enderror"
                                value="{{ old('company_name', $settings['company_name']) }}" required>
                            @error('company_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email da Empresa -->
                        <div class="col-12 col-md-6">
                            <label for="company_email" class="form-label fw-medium text-dark">Email da Empresa *</label>
                            <input type="email" id="company_email" name="company_email" 
                                class="form-control @error('company_email') is-invalid @enderror"
                                value="{{ old('company_email', $settings['company_email']) }}" required>
                            @error('company_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Telefone da Empresa -->
                        <div class="col-12 col-md-6">
                            <label for="company_phone" class="form-label fw-medium text-dark">Telefone da Empresa *</label>
                            <input type="text" id="company_phone" name="company_phone" 
                                class="form-control @error('company_phone') is-invalid @enderror"
                                value="{{ old('company_phone', $settings['company_phone']) }}" required>
                            @error('company_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Fuso Horário -->
                        <div class="col-12 col-md-6">
                            <label for="timezone" class="form-label fw-medium text-dark">Fuso Horário *</label>
                            <select id="timezone" name="timezone" 
                                class="form-select @error('timezone') is-invalid @enderror" required>
                                <option value="America/Sao_Paulo" {{ old('timezone', $settings['timezone']) == 'America/Sao_Paulo' ? 'selected' : '' }}>
                                    América/São_Paulo (GMT-3)
                                </option>
                                <option value="America/New_York" {{ old('timezone', $settings['timezone']) == 'America/New_York' ? 'selected' : '' }}>
                                    América/New_York (GMT-5)
                                </option>
                                <option value="Europe/London" {{ old('timezone', $settings['timezone']) == 'Europe/London' ? 'selected' : '' }}>
                                    Europa/Londres (GMT+0)
                                </option>
                                <option value="Asia/Tokyo" {{ old('timezone', $settings['timezone']) == 'Asia/Tokyo' ? 'selected' : '' }}>
                                    Ásia/Tóquio (GMT+9)
                                </option>
                            </select>
                            @error('timezone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Endereço da Empresa -->
                        <div class="col-12">
                            <label for="company_address" class="form-label fw-medium text-dark">Endereço da Empresa</label>
                            <textarea id="company_address" name="company_address" rows="3"
                                class="form-control @error('company_address') is-invalid @enderror">{{ old('company_address', $settings['company_address']) }}</textarea>
                            @error('company_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary d-inline-flex align-items-center">
                            <svg class="me-2" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Salvar Configurações
                        </button>
                    </div>
                </form>
            </div>
        </div>
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
                    <a href="{{ route('admin.settings.email') }}" class="btn btn-outline-primary btn-sm">
                        <svg class="me-2" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Configurar Email
                    </a>
                    <a href="{{ route('admin.settings.templates') }}" class="btn btn-outline-warning btn-sm">
                        <svg class="me-2" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Editar Templates
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
