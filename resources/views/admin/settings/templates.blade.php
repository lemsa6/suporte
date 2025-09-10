@extends('layouts.app')

@section('title', 'Templates de Notificação')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div class="mb-4 sm:mb-0">
        <span class="text-sm text-cinza-claro">Administração</span>
        <h1 class="page-title mt-1">Templates de Notificação</h1>
        <p class="text-cinza mt-2">Edite e personalize os templates de email para notificações do sistema</p>
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
<div class="space-y-6">
    <!-- Templates Disponíveis -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($templates as $template)
            <x-card>
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-cinza">{{ ucfirst(str_replace('_', ' ', $template)) }}</h3>
                        <p class="text-sm text-cinza-claro">Template de notificação</p>
                    </div>
                    <x-badge variant="info">{{ $template }}</x-badge>
                </div>
                
                <div class="space-y-3">
                    <div class="text-sm text-cinza-claro">
                        <strong>Assunto:</strong> {{ $templateSettings[$template]['subject'] ?? 'Não definido' }}
                    </div>
                    
                    <div class="text-sm text-cinza-claro">
                        <strong>Status:</strong> 
                        <x-badge variant="{{ $templateSettings[$template]['enabled'] ?? false ? 'success' : 'danger' }}">
                            {{ $templateSettings[$template]['enabled'] ?? false ? 'Ativo' : 'Inativo' }}
                        </x-badge>
                    </div>
                </div>

                <div class="flex gap-2 mt-4">
                    <x-button variant="outline" size="sm" onclick="previewTemplate('{{ $template }}')">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Visualizar
                    </x-button>
                    
                    <x-button variant="outline" size="sm" onclick="testEmail('{{ $template }}')">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Testar
                    </x-button>
                    
                    <x-button variant="primary" size="sm" onclick="editTemplate('{{ $template }}')">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Editar
                    </x-button>
                </div>
            </x-card>
        @endforeach
    </div>

    <!-- Configurações Gerais -->
    <h2 class="section-title mb-4">Configurações Gerais</h2>
    <x-card>
        <form method="POST" action="{{ route('admin.settings.templates.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Email Padrão -->
                <div>
                    <label for="default_from_email" class="block text-sm font-medium text-cinza mb-2">Email Padrão de Envio</label>
                    <input type="email" id="default_from_email" name="default_from_email" 
                        class="w-full px-3 py-2 border border-cinza-claro-2 rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent @error('default_from_email') border-vermelho @enderror"
                        value="{{ old('default_from_email', $settings['default_from_email'] ?? '') }}">
                    @error('default_from_email')
                        <div class="text-vermelho text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Nome Padrão -->
                <div>
                    <label for="default_from_name" class="block text-sm font-medium text-cinza mb-2">Nome Padrão de Envio</label>
                    <input type="text" id="default_from_name" name="default_from_name" 
                        class="w-full px-3 py-2 border border-cinza-claro-2 rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent @error('default_from_name') border-vermelho @enderror"
                        value="{{ old('default_from_name', $settings['default_from_name'] ?? '') }}">
                    @error('default_from_name')
                        <div class="text-vermelho text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Botões -->
            <div class="flex justify-end gap-3 pt-6 border-t border-cinza-claro-2">
                <x-button variant="outline" type="button" onclick="resetTemplates()">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Resetar Templates
                </x-button>
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

<!-- Modal de Edição -->
<div id="editTemplateModal" class="fixed inset-0 bg-cinza-escuro bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-cinza">Editar Template</h3>
                    <button onclick="closeEditModal()" class="text-cinza-claro hover:text-cinza">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form id="editTemplateForm" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label for="template_subject" class="block text-sm font-medium text-cinza mb-2">Assunto do Email</label>
                        <input type="text" id="template_subject" name="subject" 
                            class="w-full px-3 py-2 border border-cinza-claro-2 rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent">
                    </div>
                    
                    <div>
                        <label for="template_body" class="block text-sm font-medium text-cinza mb-2">Corpo do Email</label>
                        <textarea id="template_body" name="body" rows="10" 
                            class="w-full px-3 py-2 border border-cinza-claro-2 rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent"></textarea>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" id="template_enabled" name="enabled" 
                            class="w-4 h-4 text-roxo border-cinza-claro-2 rounded focus:ring-roxo">
                        <label for="template_enabled" class="ml-2 text-sm text-cinza">Template ativo</label>
                    </div>
                    
                    <div class="flex justify-end gap-3 pt-4 border-t border-cinza-claro-2">
                        <x-button variant="outline" type="button" onclick="closeEditModal()">
                            Cancelar
                        </x-button>
                        <x-button variant="primary" type="submit">
                            Salvar Template
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function previewTemplate(template) {
    // Implementar visualização do template
    alert('Visualização do template "' + template + '" será implementada em breve.');
}

function testEmail(template) {
    // Implementar teste de email
    alert('Teste de email para template "' + template + '" será implementado em breve.');
}

function editTemplate(template) {
    document.getElementById('editTemplateModal').classList.remove('hidden');
    // Carregar dados do template
    document.getElementById('editTemplateForm').action = '{{ route("admin.settings.templates.update") }}/' + template;
}

function closeEditModal() {
    document.getElementById('editTemplateModal').classList.add('hidden');
}

function resetTemplates() {
    if (confirm('Tem certeza que deseja resetar todos os templates para os valores padrão?')) {
        // Implementar reset dos templates
        alert('Reset dos templates será implementado em breve.');
    }
}
</script>
@endsection