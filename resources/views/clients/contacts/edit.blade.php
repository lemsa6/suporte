@extends('layouts.app')

@section('title', 'Editar Contato')

@section('header')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between">
    <div class="flex-grow-1">
        <div class="d-flex align-items-center mb-2">
            <a href="{{ route('clients.show', $client) }}" class="btn btn-outline-secondary btn-sm me-3">
                <svg class="me-1" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Voltar
            </a>
            <h2 class="fs-2 fw-bold text-dark mb-0">
                Editar Contato
            </h2>
        </div>
        <p class="text-muted mb-0">
            <strong>Empresa:</strong> {{ $client->company_name }} | 
            <strong>Contato:</strong> {{ $contact->name }}
        </p>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row g-4">
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0 fw-semibold">Informações do Contato</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('clients.contacts.update', ['client' => $client, 'contact' => $contact]) }}" method="POST" class="d-flex flex-column gap-4">
                        @csrf
                        @method('PUT')
                        
                        <!-- Informações Pessoais -->
                        <div>
                            <h6 class="fw-semibold text-dark mb-3">Informações Pessoais</h6>
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label for="name" class="form-label fw-medium text-dark">Nome Completo *</label>
                                    <input type="text" id="name" name="name" value="{{ old('name', $contact->name) }}" required
                                        class="form-control @error('name') is-invalid @enderror">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-12 col-md-6">
                                    <label for="email" class="form-label fw-medium text-dark">E-mail *</label>
                                    <input type="email" id="email" name="email" value="{{ old('email', $contact->email) }}" required
                                        class="form-control @error('email') is-invalid @enderror">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Informações Profissionais -->
                        <div>
                            <h6 class="fw-semibold text-dark mb-3">Informações Profissionais</h6>
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label for="position" class="form-label fw-medium text-dark">Cargo</label>
                                    <input type="text" id="position" name="position" value="{{ old('position', $contact->position) }}"
                                        class="form-control @error('position') is-invalid @enderror">
                                    @error('position')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-12 col-md-6">
                                    <label for="department" class="form-label fw-medium text-dark">Departamento</label>
                                    <input type="text" id="department" name="department" value="{{ old('department', $contact->department) }}"
                                        class="form-control @error('department') is-invalid @enderror">
                                    @error('department')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Contato -->
                        <div>
                            <h6 class="fw-semibold text-dark mb-3">Contato</h6>
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label for="phone" class="form-label fw-medium text-dark">Telefone</label>
                                    <input type="tel" id="phone" name="phone" value="{{ old('phone', $contact->phone) }}"
                                        class="form-control @error('phone') is-invalid @enderror" placeholder="(11) 99999-9999">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Configurações -->
                        <div>
                            <h6 class="fw-semibold text-dark mb-3">Configurações</h6>
                            <div class="d-flex flex-column gap-3">
                                <div class="form-check">
                                    <input type="checkbox" id="is_primary" name="is_primary" value="1" 
                                        {{ old('is_primary', $contact->is_primary) ? 'checked' : '' }}
                                        class="form-check-input">
                                    <label for="is_primary" class="form-check-label fw-medium text-dark">
                                        Contato principal
                                    </label>
                                </div>
                                
                                <div>
                                    <label for="user_type" class="form-label fw-medium text-dark">Tipo de Usuário *</label>
                                    <select id="user_type" name="user_type" required
                                        class="form-select @error('user_type') is-invalid @enderror">
                                        <option value="cliente_funcionario" {{ old('user_type', $contact->user_type) === 'cliente_funcionario' ? 'selected' : '' }}>
                                            Funcionário da Empresa
                                        </option>
                                        <option value="cliente_gestor" {{ old('user_type', $contact->user_type) === 'cliente_gestor' ? 'selected' : '' }}>
                                            Gestor da Empresa
                                        </option>
                                    </select>
                                    <div class="form-text">
                                        <strong>Gestor:</strong> Pode criar usuários e ver todos os tickets da empresa<br>
                                        <strong>Funcionário:</strong> Apenas seus próprios tickets
                                    </div>
                                    @error('user_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Redefinir Senha -->
                        <div class="border-top pt-4">
                            <h6 class="fw-semibold text-dark mb-3">Redefinir Senha</h6>
                            <div class="alert alert-info">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <svg class="text-info" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="ms-3">
                                        <h6 class="alert-heading fw-semibold">Informações sobre senha</h6>
                                        <p class="mb-1">Deixe os campos de senha em branco se não quiser alterar a senha atual.</p>
                                        <p class="mb-0">Se preencher, uma nova senha será definida para este usuário.</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label for="new_password" class="form-label fw-medium text-dark">Nova Senha</label>
                                    <input type="password" id="new_password" name="new_password" minlength="8"
                                        class="form-control @error('new_password') is-invalid @enderror">
                                    <div class="form-text">Mínimo de 8 caracteres</div>
                                    @error('new_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-12 col-md-6">
                                    <label for="new_password_confirmation" class="form-label fw-medium text-dark">Confirmar Nova Senha</label>
                                    <input type="password" id="new_password_confirmation" name="new_password_confirmation" minlength="8"
                                        class="form-control @error('new_password_confirmation') is-invalid @enderror">
                                    @error('new_password_confirmation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Botões -->
                        <div class="d-flex justify-content-end gap-2 pt-4 border-top">
                            <a href="{{ route('clients.show', $client) }}" 
                                class="btn btn-outline-secondary d-inline-flex align-items-center">
                                <svg class="me-2" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Cancelar
                            </a>
                            <button type="submit" 
                                class="btn btn-primary d-inline-flex align-items-center">
                                <svg class="me-2" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Atualizar Contato
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0 fw-semibold">Informações do Contato</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                <svg class="text-primary" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="fw-medium text-dark">{{ $contact->name }}</div>
                                <small class="text-muted">{{ $contact->email }}</small>
                            </div>
                        </div>
                        
                        @if($contact->position)
                        <div class="d-flex align-items-center">
                            <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                                <svg class="text-info" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 01-2 2H8a2 2 0 01-2-2V6m8 0H8m8 0v6a2 2 0 01-2 2H8a2 2 0 01-2-2V6"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="fw-medium text-dark">{{ $contact->position }}</div>
                                @if($contact->department)
                                    <small class="text-muted">{{ $contact->department }}</small>
                                @endif
                            </div>
                        </div>
                        @endif
                        
                        @if($contact->phone)
                        <div class="d-flex align-items-center">
                            <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                                <svg class="text-success" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="fw-medium text-dark">{{ $contact->phone }}</div>
                            </div>
                        </div>
                        @endif
                        
                        <div class="d-flex align-items-center">
                            <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                                <svg class="text-warning" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="fw-medium text-dark">
                                    @if($contact->is_primary)
                                        Contato Principal
                                    @else
                                        Contato Secundário
                                    @endif
                                </div>
                                <small class="text-muted">{{ ucfirst(str_replace('_', ' ', $contact->user_type)) }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection